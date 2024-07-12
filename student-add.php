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

if (isset($_POST['reg'])) {
    $sName = mysqli_real_escape_string($con, $_POST['sName']);
    $sIC = mysqli_real_escape_string($con, $_POST['sIC']);
    // $sReg = mysqli_real_escape_string($con, $_POST['sReg']);
    $sProg = $_POST['sProg'];
    $sEDU = $_POST['sEdu'];
    $sSes = $_POST['sSes'];
    $sRes = $_POST['sRes'];

    $insertQuery = "INSERT INTO student 
    (`full_name`,`nokp`,`programme`,`year_s`,`edu`,`residence`)
    VALUES 
    ('$sName','$sIC','$sProg','$sSes','$sEDU','$sRes')";
    $runInsert = mysqli_query($con, $insertQuery);
    if (!$runInsert) {
        $msg = "<p class='text-bg-danger p-2'>Ralat ketika pendaftaran pelajar. Sila cuba lagi.</p>";
    } else {
        // $msg = "<p class='text-bg-success p-2'>Pendaftaran pelajar berjaya!</p>";
        // echo "
        // <script>
        //     window.alert('Data pelajar berjaya di tambah!');
        //     window.location = 'student-app.php';
        // </script>
        // ";
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
        <img src="../../images/kpmkvks.png" class="w-75">
        <h3 class="h3 p-3">Tambah Rekod Pelajar Diploma</h3>
    </div>

    <form method="post">
        <div class="container bg-primary bg-opacity-50 p-2">
            <?php echo $msg; ?>
            <div class="row p-2">
                <div class="col-sm">
                    <p><strong>Nama Pelajar</strong></p>
                    <input type="text" name="sName" class="form-control" placeholder="Masukkan nama pelajar disini" required>
                </div>
            </div>

            <div class="row p-2">
                <div class="col-sm">
                    <p><strong>Program</strong></p>
                    <select name="sProg" class="form-control">
                        <?php
                        $getProg = "SELECT * FROM programme ORDER BY id_program ASC";
                        $run = mysqli_query($con, $getProg);
                        while ($p = mysqli_fetch_array($run)) {
                        ?>
                            <option value="<?php echo $p['id_program']; ?>"><?php echo $p['programme_name']; ?></option>
                        <?php } 
                        ?>
                    </select>
                </div>
                <div class="col-sm">
                    <p><strong>No Kad Pengenalan</strong></p>
                    <input type="text" name="sIC" class="form-control" placeholder="Masukkan no kad pengenalan" required>
                </div>

            </div>
            <div class="row p-2">
                <div class="col-sm">
                    <p><strong>Sesi Pengajian</strong></p>
                    <select name="sSes" class="form-control">
                        <option value="2021/2022">2021/2022</option>
                        <option value="2022/2023">2022/2023</option>
                        <option value="2023/2024">2023/2024</option>
                        <option value="2024/2025">2024/2025</option>
                        <option value="2025/2026">2025/2026</option>
                        <option value="2026/2027">2026/2027</option>
                        <option value="2027/2028">2027/2028</option>
                    </select>
                </div>
                <div class="col-sm">
                    <p><strong>Jenis Pendidikan</strong></p>
                    <select name="sEdu" class="form-control">
                        <option value="DVM">DVM</option>
                        <option value="SVM">SVM</option>
                    </select>
                </div>
                <div class="col-sm">
                    <p><strong>Penginapan</strong></p>
                    <select class="form-control" name="sRes">
                        <option value="KOLEJ KEDIAMAN">KOLEJ KEDIAMAN</option>
                        <option value="PELAJAR LUAR (UNIT KEDIAMAN LUAR KOLEJ)">PELAJAR LUAR (UNIT KEDIAMAN LUAR KOLEJ)</option>
                    </select>
                </div>
            </div>
            <button onclick="window.location='student-app.php'" class="btn btn-secondary">Kembali</button>
            <button class="btn btn-success" type="submit" name="reg"><i class="far fa-plus"></i> Tambah Rekod</button>
        </div>
    </form><br>

</body>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</html>