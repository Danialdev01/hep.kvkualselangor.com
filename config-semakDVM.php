<?php
include './databases/config.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ./index.php");
    exit();
} else {
    $username = $_SESSION['username'];
}

$msg = "";

// Function to handle document update
function updateDocument($con) {
    global $msg;
    
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $date_issued = $_POST['date_issued'];
    $reportD_a = $_POST['reportD-a'];
    $reportP_a = $_POST['reportP-a'];
    $reportT_a = $_POST['reportT-a'];
    $reportT_a2 = $_POST['reportT-a2'];
    $reportD_o = $_POST['reportD-o'];
    $reportP_o = $_POST['reportP-o'];
    $reportT_o = $_POST['reportT-o'];

    $setDocs = "UPDATE document SET 
        title='$title',
        date_issued='$date_issued',
        reportD_asrama='$reportD_a',
        reportP_asrama='$reportP_a',
        reportT_asrama='$reportT_a',
        reportT2_asrama='$reportT_a2',
        reportD_outsider='$reportD_o',
        reportP_outsider='$reportP_o',
        reportT_outsider='$reportT_o'";
    
    $execSet = mysqli_query($con, $setDocs);

    if ($execSet) {
        $msg = "<p class='text-bg-success p-3'>Surat Tawaran Sistem SemakDVM berjaya dikemaskini!</p>";
    } else {
        $msg = "<p class='text-bg-danger p-3'>Surat Tawaran Sistem SemakDVM gagal dikemaskini!</p>";
    }
}

// Function to handle file upload
function uploadFiles($con) {
    $file_type = mysqli_real_escape_string($con, $_POST['file_type']);
    $upload_errors = [];
    $upload_success = [];

    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $_FILES['files']['name'][$key];
        $file_size = $_FILES['files']['size'][$key];
        $file_tmp = $_FILES['files']['tmp_name'][$key];
        $file_error = $_FILES['files']['error'][$key];

        // Validate file size (max 10MB)
        if ($file_size > 10485760) {
            $upload_errors[] = "File $file_name is too large (max 10MB)";
            continue;
        }

        // Create uploads directory if it doesn't exist
        if (!is_dir('./uploads')) {
            mkdir('./uploads', 0777, true);
        }

        // Generate unique filename
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_filename = uniqid() . '_' . date('Ymd_His') . '.' . $file_ext;
        $upload_path = './uploads/' . $new_filename;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $insert_file = "INSERT INTO file (path_file, type_file, file_name) 
                          VALUES ('$upload_path', '$file_type', '$file_name')";
            if (mysqli_query($con, $insert_file)) {
                $upload_success[] = $file_name;
            } else {
                $upload_errors[] = "Failed to save $file_name to database";
                unlink($upload_path);
            }
        } else {
            $upload_errors[] = "Failed to upload $file_name";
        }
    }

    return ['success' => $upload_success, 'errors' => $upload_errors];
}

// Function to delete file
function deleteFile($con, $file_id) {
    $file_id = mysqli_real_escape_string($con, $file_id);
    
    // Get file path first
    $get_file = "SELECT * FROM file WHERE id_file = '$file_id'";
    $file_result = mysqli_query($con, $get_file);
    $file_data = mysqli_fetch_assoc($file_result);
    
    if ($file_data) {
        // Delete from database
        $delete_query = "DELETE FROM file WHERE id_file = '$file_id'";
        if (mysqli_query($con, $delete_query)) {
            // Delete physical file
            if (file_exists($file_data['path_file'])) {
                unlink($file_data['path_file']);
            }
            return true;
        }
    }
    return false;
}

// Function to update file visibility
function updateFileVisibility($con, $file_id, $new_type) {
    $file_id = mysqli_real_escape_string($con, $file_id);
    $new_type = mysqli_real_escape_string($con, $new_type);
    
    $update_query = "UPDATE file SET type_file = '$new_type' WHERE id_file = '$file_id'";
    return mysqli_query($con, $update_query);
}

// Function to get all files
function getAllFiles($con) {
    $files_query = "SELECT * FROM file ORDER BY created_date DESC";
    return mysqli_query($con, $files_query);
}

// Function to get current document
function getCurrentDocument($con) {
    $currentDocSearch = "SELECT * FROM document";
    $currentDocSet = mysqli_query($con, $currentDocSearch);
    return mysqli_fetch_array($currentDocSet);
}

// Handle form submissions
if (isset($_POST['update'])) {
    updateDocument($con);
}

if (isset($_POST['upload_files'])) {
    $upload_result = uploadFiles($con);
}

if (isset($_GET['delete_file'])) {
    $delete_result = deleteFile($con, $_GET['delete_file']);
    if ($delete_result) {
        $msg = "<p class='text-bg-success p-3'>File deleted successfully</p>";
    } else {
        $msg = "<p class='text-bg-danger p-3'>Failed to delete file</p>";
    }
}

if (isset($_POST['update_visibility'])) {
    $update_result = updateFileVisibility($con, $_POST['file_id'], $_POST['new_type']);
    if ($update_result) {
        $msg = "<p class='text-bg-success p-3'>File visibility updated successfully</p>";
    } else {
        $msg = "<p class='text-bg-danger p-3'>Failed to update file visibility</p>";
    }
}

// Get current data
$currentDoc = getCurrentDocument($con);
$files_result = getAllFiles($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>HEP - Kolej Vokasional Kuala Selangor</title>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        footer {
            margin-top: auto;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }
        .table th {
            border-top: none;
            font-weight: 600;
        }
        .form-select-sm {
            width: auto;
            display: inline-block;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
    </style>
</head>
<body>
    <?php $page="config"; require_once('components/navbar.php'); ?>
    
    <div class="container text-center mt-3 bg-light">
        <img src="./images/kpmkvks.png" class="w-75">
        <h3 class="h3 p-3">UNIT HAL EHWAL PELAJAR<br>KOLEJ VOKASIONAL KUALA SELANGOR</h3>
    </div>

    <!-- Document Configuration Form -->
    <form method="post">
        <div class="container">
            <?php echo $msg; ?>
            <div class="row">
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Diploma Sesi / Kohort</strong></label>
                        <input type="text" class="form-control" id="exampleInputPassword1" name="title" required value="<?php echo $currentDoc['title']; ?>">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Tarikh Surat Rasmi</strong></label>
                        <input type="date" class="form-control" id="exampleInputPassword1" name="date_issued" required value="<?php echo $currentDoc['date_issued']; ?>">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Tarikh Lapor Diri Pelajar Asrama</strong></label>
                        <input type="date" class="form-control" id="exampleInputPassword1" name="reportD-a" required value="<?php echo $currentDoc['reportD_asrama']; ?>">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Tempat Lapor Diri Pelajar Asrama</strong></label>
                        <input type="text" class="form-control" id="exampleInputPassword1" name="reportP-a" required value="<?php echo $currentDoc['reportP_asrama']; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Masa Lapor Diri Pelajar Asrama</strong></label>
                        <input type="time" class="form-control" id="exampleInputPassword1" name="reportT-a" required value="<?php echo $currentDoc['reportT_asrama']; ?>">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Masa Lapor Diri Pelajar Asrama Hingga</strong></label>
                        <input type="time" class="form-control" id="exampleInputPassword1" name="reportT-a2" required value="<?php echo $currentDoc['reportT2_asrama']; ?>">
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Tarikh Lapor Diri Pelajar Outsider</strong></label>
                        <input type="date" class="form-control" id="exampleInputPassword1" name="reportD-o" required value="<?php echo $currentDoc['reportD_outsider']; ?>">
                    </div>
                </div>
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Masa Lapor Diri Pelajar Outsider</strong></label>
                        <input type="time" class="form-control" id="exampleInputPassword1" name="reportT-o" required value="<?php echo $currentDoc['reportT_outsider']; ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label"><strong>Tempat Lapor Diri Pelajar Outsider</strong></label>
                        <input type="text" class="form-control" id="exampleInputPassword1" name="reportP-o" required value="<?php echo $currentDoc['reportP_outsider']; ?>">
                    </div>
                </div>
                <div class="col-sm"> </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <button type="submit" name="update" class="btn btn-primary">Kemaskini Surat Tawaran SemakDVM</button>
                </div>
            </div>
        </div>
    </form>

    <!-- File Upload Section -->
    <div class="container mt-5 mb-20">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Bahan Sampingan</h4>
            </div>
            <div class="card-body">
                <!-- Upload New Files Form -->
                <div class="mb-4">
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="files" class="form-label">Select Files</label>
                                    <input type="file" class="form-control" id="files" name="files[]" multiple required>
                                    <div class="form-text">You can select multiple files (max 10MB each)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="file_type" class="form-label">Jenis</label>
                                    <select class="form-select" id="file_type" name="file_type" required>
                                        <option value="1">Kedua yang sudah semak</option>
                                        <option value="2">Pelajar yang menerima</option>
                                        <option value="3">Pelajar yang  menolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="upload_files" class="btn btn-success">Upload Files</button>
                    </form>
                    
                    <?php if (isset($upload_result)): ?>
                        <?php if (!empty($upload_result['success'])): ?>
                            <div class="alert alert-success mt-3">
                                <?php foreach ($upload_result['success'] as $success): ?>
                                    <p>✓ <?php echo htmlspecialchars($success); ?> uploaded successfully</p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($upload_result['errors'])): ?>
                            <div class="alert alert-danger mt-3">
                                <?php foreach ($upload_result['errors'] as $error): ?>
                                    <p>✗ <?php echo htmlspecialchars($error); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <hr>

                <!-- Current Files Table -->
                <div>
                    <h5>Current Files</h5>
                    <?php if (mysqli_num_rows($files_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>File Name</th>
                                    <th>Upload Date</th>
                                    <th>Visibility</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($file = mysqli_fetch_assoc($files_result)): ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo $file['path_file']; ?>" target="_blank" class="text-decoration-none">
                                            <?php echo htmlspecialchars($file['file_name']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($file['created_date'])); ?></td>
                                    <td>
                                        <form method="post" class="d-inline">
                                            <input type="hidden" name="file_id" value="<?php echo $file['id_file']; ?>">
                                            <select name="new_type" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="1" <?php echo $file['type_file'] == 1 ? 'selected' : ''; ?>>Both</option>
                                                <option value="2" <?php echo $file['type_file'] == 2 ? 'selected' : ''; ?>>Menerima Only</option>
                                                <option value="3" <?php echo $file['type_file'] == 3 ? 'selected' : ''; ?>>Menolak Only</option>
                                            </select>
                                            <input type="hidden" name="update_visibility" value="1">
                                        </form>
                                    </td>
                                    <td>
                                        <a href="<?php echo $file['path_file']; ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="?delete_file=<?php echo $file['id_file']; ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this file?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                        <div class="alert alert-info">No files uploaded yet.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <br><br><br><br><br>
    </div>



</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
</html>