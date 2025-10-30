<?php

include 'databases/config.php';
session_start();
$redirect = "dashboard.php";
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
} else {
    $username = $_SESSION['username'];
}

$msg = "";

// Get filter values from GET parameters
$ic_prefix = isset($_GET['ic_prefix']) ? $_GET['ic_prefix'] : '';
$program_filter = isset($_GET['program_filter']) ? $_GET['program_filter'] : '';

// Build the base query
$getAllStudent = "SELECT 
                    s.*,
                    p.programme_name as program
                FROM 
                    student as s
                LEFT JOIN
                    programme as p
                ON
                    s.programme = p.id_program";

// Add WHERE conditions based on filters
$where_conditions = [];
if (!empty($ic_prefix)) {
    $where_conditions[] = "s.nokp LIKE '$ic_prefix%'";
}
if (!empty($program_filter)) {
    $where_conditions[] = "s.programme = '$program_filter'";
}

if (!empty($where_conditions)) {
    $getAllStudent .= " WHERE " . implode(' AND ', $where_conditions);
}

$getAllStudent .= " ORDER BY programme ASC";

// Get distinct IC prefixes for dropdown
$ic_prefixes_query = "SELECT DISTINCT LEFT(nokp, 2) as prefix FROM student ORDER BY prefix";
$ic_prefixes_result = mysqli_query($con, $ic_prefixes_query);
$ic_prefixes = [];
while ($row = mysqli_fetch_assoc($ic_prefixes_result)) {
    $ic_prefixes[] = $row['prefix'];
}

// Get programs for dropdown
$programs_query = "SELECT * FROM programme ORDER BY programme_name";
$programs_result = mysqli_query($con, $programs_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <title>HEP - Kolej Vokasional Kuala Selangor</title>
</head>

<style>
    body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
    }

    footer {
        margin-top: auto;
    }
    
    .filter-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
    }
    
    .filter-badge {
        background-color: #6c757d;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        margin-left: 10px;
    }
    
    .active-filter {
        background-color: #0d6efd !important;
    }
</style>

<body>
    <?php $page = 'data';
    require_once('components/navbar.php'); ?>
    <div class="container text-center mt-3 bg-light">
        <img src="./images/kpmkvks.png" class="w-75">
        <h3 class="h3 p-3">SENARAI NAMA PELAJAR<br>LAYAK KE PROGRAM DIPLOMA</h3>
    </div>

    <div class="container">
        <!-- Filter Section -->
        <div class="filter-section">
            <h5><i class="fas fa-filter"></i> Penapis Data</h5>
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="ic_prefix" class="form-label">Awalan No. Kad Pengenalan</label>
                    <select class="form-select" id="ic_prefix" name="ic_prefix">
                        <option value="">Semua Awalan</option>
                        <?php foreach ($ic_prefixes as $prefix): ?>
                            <option value="<?php echo $prefix; ?>" <?php echo ($ic_prefix == $prefix) ? 'selected' : ''; ?>>
                                <?php echo $prefix; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="program_filter" class="form-label">Program</label>
                    <select class="form-select" id="program_filter" name="program_filter">
                        <option value="">Semua Program</option>
                        <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                            <option value="<?php echo $program['id_program']; ?>" <?php echo ($program_filter == $program['id_program']) ? 'selected' : ''; ?>>
                                <?php echo $program['programme_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Gunakan Penapis
                    </button>
                    <?php if (!empty($ic_prefix) || !empty($program_filter)): ?>
                        <a href="data.php" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-times"></i> Padam Penapis
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Active Filters Display -->
            <?php if (!empty($ic_prefix) || !empty($program_filter)): ?>
                <div class="mt-3">
                    <strong>Penapis Aktif:</strong>
                    <?php if (!empty($ic_prefix)): ?>
                        <span class="filter-badge active-filter">
                            No. KP: <?php echo $ic_prefix; ?>*
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['ic_prefix' => ''])); ?>" class="text-white ms-2" style="text-decoration: none;">×</a>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($program_filter)): 
                        // Get program name for display
                        $program_name_query = "SELECT programme_name FROM programme WHERE id_program = '$program_filter'";
                        $program_name_result = mysqli_query($con, $program_name_query);
                        $program_name_row = mysqli_fetch_assoc($program_name_result);
                        $program_name = $program_name_row['programme_name'];
                    ?>
                        <span class="filter-badge active-filter">
                            Program: <?php echo $program_name; ?>
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['program_filter' => ''])); ?>" class="text-white ms-2" style="text-decoration: none;">×</a>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="student-import.php" class="btn btn-primary">
                <i class="fad fa-database"></i> MUAT NAIK DATA PUKAL (CSV)
            </a>
            <a href="student-add.php" class="btn btn-warning">
                <i class="fad fa-address-card"></i> TAMBAH PELAJAR
            </a>
            <?php if (!empty($ic_prefix) || !empty($program_filter)): ?>
                <button type="button" class="btn btn-info" onclick="exportFilteredData()">
                    <i class="fas fa-download"></i> EKSPORT DATA TERSARING
                </button>
            <?php endif; ?>
        </div>

        <!-- Student Table -->
        <table id="myTable" class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Nama Pelajar</th>
                    <th>No. Kad Pengenalan</th>
                    <th>Program</th>
                    <th>Kemaskini</th>
                    <th>Semak</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $run = mysqli_query($con, $getAllStudent);
                $total_students = mysqli_num_rows($run);
                
                while ($stdnt = mysqli_fetch_array($run)) {
                    echo "
                    <tr>
                        <td>" . htmlspecialchars($stdnt['full_name']) . "</td>
                        <td>" . htmlspecialchars($stdnt['nokp']) . "</td>
                        <td>" . htmlspecialchars($stdnt['program']) . "</td>
                        <td>
                            <a href='student-edit.php?nokp=" . $stdnt['nokp'] . "' class='btn btn-info btn-sm'>
                                <i class='fas fa-edit'></i> Kemaskini
                            </a>
                        </td>
                        <td>
                            <a href='student-view.php?nokp=" . $stdnt['nokp'] . "' class='btn btn-info btn-sm'>
                                <i class='fas fa-search'></i> Semak
                            </a>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>

        <!-- Results Summary -->
        <div class="alert alert-info">
            <strong>Jumlah Rekod: <?php echo $total_students; ?> pelajar</strong>
            <?php if (!empty($ic_prefix)): ?>
                <br>Dengan awalan No. KP: <strong><?php echo $ic_prefix; ?></strong>
            <?php endif; ?>
            <?php if (!empty($program_filter)): ?>
                <br>Program: <strong><?php echo isset($program_name) ? $program_name : ''; ?></strong>
            <?php endif; ?>
        </div>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script>
    // Initialize DataTable
    new DataTable('#myTable', {
        order: [
            [2, 'asc'],  // Program
            [0, 'asc']   // Name
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Papar _MENU_ rekod",
            info: "Memaparkan _START_ hingga _END_ daripada _TOTAL_ rekod",
            infoEmpty: "Memaparkan 0 hingga 0 daripada 0 rekod",
            infoFiltered: "(ditapis daripada _MAX_ jumlah rekod)",
            paginate: {
                first: "Pertama",
                last: "Akhir",
                next: "Seterusnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Export filtered data function
    function exportFilteredData() {
        // Get current filter parameters
        const urlParams = new URLSearchParams(window.location.search);
        const icPrefix = urlParams.get('ic_prefix') || '';
        const programFilter = urlParams.get('program_filter') || '';
        
        // Create export URL
        let exportUrl = 'export-students.php?';
        if (icPrefix) exportUrl += 'ic_prefix=' + icPrefix + '&';
        if (programFilter) exportUrl += 'program_filter=' + programFilter;
        
        // Open export in new window
        window.open(exportUrl, '_blank');
    }

    // Auto-submit form when filters change (optional)
    document.addEventListener('DOMContentLoaded', function() {
        const icPrefixSelect = document.getElementById('ic_prefix');
        const programFilterSelect = document.getElementById('program_filter');
        
        // Uncomment below if you want auto-submit on change
        /*
        icPrefixSelect.addEventListener('change', function() {
            this.form.submit();
        });
        
        programFilterSelect.addEventListener('change', function() {
            this.form.submit();
        });
        */
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</html>