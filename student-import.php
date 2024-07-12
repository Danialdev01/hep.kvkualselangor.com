<?php

include 'databases/config.php';
// session_start();
// $redirect = "dashboard.php";
// if (!isset($_SESSION['username'])) {
//     header("Location: index.php");
// } else {
//     $username = $_SESSION['username'];
// }

$msg = "";

if (isset($_POST['reg'])) {
    $sName = mysqli_real_escape_string($con, $_POST['sName']);
    $sIC = mysqli_real_escape_string($con, $_POST['sIC']);
    $sReg = mysqli_real_escape_string($con, $_POST['sReg']);
    $sProg = $_POST['sProg'];
    $sKohot = $_POST['sKohot'];
    $sEDU = $_POST['sEdu'];
    $sRes = $_POST['sRes'];
    $sSes = $_POST['sSes'];

    $insertQuery = "INSERT INTO student (`full_name`,`nokp`,`reg_no`,`programme`,`kohort`,`year_s`,`edu`,`residence`)
    VALUES ('$sName','$sIC','$sReg','$sProg','$sKohot','$sSes','$sEDU','$sRes')";
    $runInsert = mysqli_query($con, $insertQuery);
    if (!$runInsert) {
        $msg = "<p class='text-bg-danger p-2'>Ralat ketika pendaftaran pelajar. Sila cuba lagi.</p>";
    } else {
        $msg = "<p class='text-bg-success p-2'>Pendaftaran pelajar berjaya!</p>";
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
    <?php require_once('components/header.php'); ?>

    
        <div class="container bg-primary bg-opacity-50 p-2">
        <div class="container-sm col-xl-8 col-sm-3 p-3 bg-white text-dark p-3">
            <p class="h4 p-3 text-center">APLIKASI PENGURUSAN PELAJAR<br>KOLEJ VOKASIONAL KUALA SELANGOR</p>
            <p class="h2 p-3 text-center">MUAT NAIK MAKLUMAT PELAJAR MELALUI DATA CSV</p>
            <p>
                <strong>Manual Muat Naik Data CSV</strong>
                <ol>
                    <li>Muat turun <em>template</em> data CSV <a href="files/TEMPLATE.csv">di sini</a></li>
                    <li>
                        Berikut merupakan kod program yang perlu di isi di dalam template (pastikan no kod program adalah tepat!).
                        <br>
                        "1" = TEKNOLOGI SISTEM PENGURUSAN PANGKALAN DATA DAN APLIKASI WEB<br>
                        "2" = DIPLOMA TEKNOLOGI KOMPUTERAN<br>
                        "3" = TEKNOLOGI MULTIMEDIA KREATIF ANIMASI<br>
                        "4" = DIPLOMA TEKNOLOGI ANIMASI 3D<br>
                        "5" = BAKERI DAN PASTERI<br>
                        "6" = DIPLOMA HOSPITALITI BAKERI DAN PASTERI<br>
                        "7" = SENI KULINARI<br>
                        "8" = DIPLOMA HOSPITALITI SENI KULINARI<br>
                        "9" = PERAKAUNAN<br>
                        "10" = DIPLOMA PERAKAUNAN<br>
                        "11" = PEMASARAN<br>
                        "12" = DIPLOMA PEMASARAN<br>
                        "13" = OPERASI PEMBUATAN PERABOT
                    </li>
                    <li>Pastikan data di simpan di dalam format <strong>.CSV (Comma Delimited)</strong></li>
                </ol>
            </p>
            <form action="import.php" method="post" name="upload_excel" enctype="multipart/form-data">
    	        <input type="file" name="file" id="file" class="form-control" value="Klik Untuk Muat Naik Excel" required>
                <button type="submit" name="Import" class="btn btn-primary"><i class="fad fa-upload"></i> Muat Naik</button>
            </form>
        </div>
        </div>
    

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