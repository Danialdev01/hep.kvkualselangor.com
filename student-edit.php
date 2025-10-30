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



if (isset($_REQUEST['nokp'])) {
    $nokp = $_REQUEST['nokp'];
    $specific = "SELECT 
                    s.*,
                    p.programme_name as programme,
                    p.period as period 
                FROM 
                    student as s
                LEFT JOIN
                    programme as p
                ON
                    s.programme = p.id_program 
                WHERE nokp = '$nokp'";
    $run = mysqli_query($con, $specific);
    $fetch = mysqli_fetch_array($run);
}

if (isset($_POST['update'])) {
    $sName = mysqli_real_escape_string($con, $_POST['sName']);
    $sIC = mysqli_real_escape_string($con, $_POST['sIC']);
    // $sReg = mysqli_real_escape_string($con, $_POST['sReg']);
    $sProg = $_POST['sProg'];
    $sEDU = $_POST['sEdu'];
    $sSes = $_POST['sSes'];
    $sRes = $_POST['sRes'];
    $sId = $fetch['id'];
    $nokpref = $fetch['nokp'];

    $updateQuery = "UPDATE student SET `id`='$sId',`full_name`='$sName',`nokp`='$sIC',`programme`='$sProg',`year_s`='$sSes',`edu`='$sEDU',`residence`='$sRes' WHERE `nokp`='$nokpref'";
    $runInsert = mysqli_query($con, $updateQuery);
    if (!$runInsert) {
        $msg = "<p class='text-bg-danger p-2'>Ralat ketika pendaftaran pelajar. Sila cuba lagi.</p>";
    } else {
        header("Refresh:0");
    }
}

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
</style>

<body>
    <?php require_once('components/navbar.php'); ?>
    <div class="container text-center mt-3 bg-light">
        <img src="./images/kpmkvks.png" class="w-75">
        <h3 class="h3 p-3">Kemaskini Rekod Pelajar Diploma</h3>
    </div>

    <div class="container bg-primary bg-opacity-50 p-2">
        <?php echo $msg; ?>
        <form method="post">

            <div class="row p-2">
                <div class="col-sm">
                    <p><strong>Nama Pelajar</strong></p>
                    <input type="text" name="sName" class="form-control" placeholder="Masukkan nama pelajar disini" value="<?php echo $fetch['full_name'] ?>" required>
                </div>
            </div>

            <div class="row p-2">
                <div class="col-sm">
                    <p><strong>Program</strong></p>
                    <select name="sProg" class="form-control">
                        <?php
                        $getProg = "SELECT * FROM programme ORDER BY id_program ASC";
                        $run = mysqli_query($con, $getProg);
                        while ($pa = mysqli_fetch_array($run)) {
                        ?>
                            <option value="<?php echo $pa['id_program']; ?>" <?php
                                                                                if ($pa['programme_name'] != $fetch['programme']) {
                                                                                    echo "";
                                                                                } else {
                                                                                    echo "selected";
                                                                                }
                                                                                ?>><?php echo $pa['programme_name']; ?></option>
                        <?php }
                        ?>
                    </select>
                </div>
                <div class="col-sm">
                    <p><strong>No Kad Pengenalan</strong></p>
                    <input type="text" name="sIC" class="form-control" placeholder="Masukkan no kad pengenalan" value="<?php echo $fetch['nokp']; ?>" required>
                </div>

            </div>
            <div class="row p-2">
                <div class="col-sm">
                    <p><strong>Sesi Pengajian</strong></p>
                    <select name="sSes" class="form-control">
                        <option value="2021/2022" <?php echo $fetch['year_s'] == '2021/2022' ? 'selected' : ''; ?>>2021/2022</option>
                        <option value="2022/2023" <?php echo $fetch['year_s'] == '2022/2023' ? 'selected' : ''; ?>>2022/2023</option>
                        <option value="2023/2024" <?php echo $fetch['year_s'] == '2023/2024' ? 'selected' : ''; ?>>2023/2024</option>
                        <option value="2024/2025" <?php echo $fetch['year_s'] == '2024/2025' ? 'selected' : ''; ?>>2024/2025</option>
                        <option value="2025/2026" <?php echo $fetch['year_s'] == '2025/2026' ? 'selected' : ''; ?>>2025/2026</option>
                        <option value="2026/2027" <?php echo $fetch['year_s'] == '2026/2027' ? 'selected' : ''; ?>>2026/2027</option>
                        <option value="2027/2028" <?php echo $fetch['year_s'] == '2027/2028' ? 'selected' : ''; ?>>2027/2028</option>
                    </select>
                </div>
                <div class="col-sm">
                    <p><strong>Jenis Pendidikan</strong></p>
                    <select name="sEdu" class="form-control">
                        <option value="DVM" <?php echo $fetch['edu'] == 'DVM' ? 'selected' : ''; ?>>DVM</option>
                        <option value="SVM" <?php echo $fetch['edu'] == 'SVM' ? 'selected' : ''; ?>>SVM</option>
                    </select>
                </div>
                <div class="col-sm">
                    <p><strong>Penginapan</strong></p>
                    <select class="form-control" name="sRes">
                        <option value="KOLEJ KEDIAMAN" <?php echo $fetch['residence'] == 'KOLEJ KEDIAMAN' ? 'selected' : ''; ?>>KOLEJ KEDIAMAN</option>
                        <option value="PELAJAR LUAR (UNIT KEDIAMAN LUAR KOLEJ)" <?php echo $fetch['residence'] == 'PELAJAR LUAR (UNIT KEDIAMAN LUAR KOLEJ)' ? 'selected' : ''; ?>>PELAJAR LUAR (UNIT KEDIAMAN LUAR KOLEJ)</option>
                    </select>
                </div>
            </div>
            <a class="btn btn-secondary" href="student-app.php">Kembali</a>

            <button class="btn btn-success" type="submit" name="update"></i>Kemaskini Rekod</button>
        </form>
    </div>
    <br>

</body>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            order: [[2, desc]]
    });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</html>