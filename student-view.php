<?php

include 'databases/config.php';
session_start();
$redirect = "dashboard.php";
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
} else {
    $username = $_SESSION['username'];
}

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
$msg = "";




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <h3 class="h3 p-3">Maklumat Pelajar Diploma</h3>
    </div>

    <div class="container p-3 text-bg-primary">
        <p class="h4 text-center text-bg-light p-3"><?php echo $fetch['full_name'] ?></p>
        <div class="row p-2">
            <div class="col-sm">
                <p><strong>No. Kad Pengenalan</strong></p>
                <input type="text" name="sIC" value="<?php echo $fetch['nokp']; ?>" readonly required class="form-control">
            </div>
            <div class="col-sm">
                <p><strong>Tempoh Pengajian</strong></p>
                <input type="text" name="sReg" value="<?php echo $fetch['period']; ?>" readonly required class="form-control">
            </div>
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <p><strong>Program</strong></p>
                <input type="text" name="sProg" value="<?php echo $fetch['programme']; ?>" readonly class="form-control">

            </div>
            <!--<div class="col-sm">-->
            <!--    <p><strong>Kohot</strong></p>-->
            <!--    <input type="text" name="sReg" value="<?php echo $fetch['kohort']; ?>" readonly required class="form-control">-->
            <!--</div>-->
        </div>
        <div class="row p-2">
            <div class="col-sm">
                <p><strong>Sesi Pengajian</strong></p>
                <input type="text" name="sIC" value="<?php echo $fetch['year_s']; ?>" readonly required class="form-control">
            </div>
            <!--<div class="col-sm">-->
            <!--    <p><strong>Jenis Pengajian</strong></p>-->
            <!--    <input type="text" name="sReg" value="<?php echo $fetch['edu']; ?>" readonly required class="form-control">-->
            <!--</div>-->
            <div class="col-sm">
                <p><strong>Penginapan</strong></p>
                <input type="text" name="sReg" value="<?php echo $fetch['residence']; ?>" readonly required class="form-control">
            </div>
        </div>
        <button onclick="window.location='student-app.php'" class="btn btn-secondary">Kembali</button>
        <a class="btn btn-danger" onclick="return confirm('Adakah anda pasti ingin memadam data pelajar ini?');" href="delete.php?nokp=<?php echo $fetch['nokp']?>">PADAM Data Pelajar</a>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</html>