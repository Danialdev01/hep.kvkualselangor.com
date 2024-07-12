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
    <?php $page = 'data';
    require_once('components/navbar.php'); ?>
    <div class="container text-center mt-3 bg-light">
        <img src="../../images/kpmkvks.png" class="w-75">
        <h3 class="h3 p-3">SENARAI NAMA PELAJAR<br>LAYAK KE PROGRAM DIPLOMA</h3>
    </div>

    <div class="container">
        <a href="student-import.php" class="btn btn-primary mt-2"><i class="fad fa-database"></i> MUAT NAIK DATA PUKAL (CSV)</a>
        <a href="student-add.php" class="btn btn-warning mt-2"><i class="fad fa-address-card"></i> TAMBAH PELAJAR</a>
        <table id="myTable" class="table table-striped mt-2">
            <thead>
                <th>Nama Pelajar</th>
                <th>No. Kad Pengenalan</th>
                <th>Program</th>
                <th>Kemaskini</th>
                <th>Semak</th>
            </thead>
            <tbody>
                <?php
                // $getAllStudent = "SELECT * FROM student";
                $getAllStudent = "SELECT 
                                    s.*,
                                    p.programme_name as program
                                FROM 
                                    student as s
                                LEFT JOIN
                                    programme as p
                                ON
                                    s.programme = p.id_program
                                ORDER BY programme ASC
                               ";


                $run = mysqli_query($con, $getAllStudent);
                while ($stdnt = mysqli_fetch_array($run)) {
                    echo "
                    <tr>
                    <td>" . $stdnt['full_name'] . "</td>
                    <td>" . $stdnt['nokp'] . "</td>
                    <td>" . $stdnt['program'] . "</td>
                    <td>
                        <a href='student-edit.php?nokp=" . $stdnt['nokp'] . "' class='btn btn-info'>Kemaskini</a>
                    </td>
                    <td>
                        <a href='student-view.php?nokp=" . $stdnt['nokp'] . "' class='btn btn-info'>Semak</a>
                    </td>

                </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script>
    new DataTable('#myTable', {
        order: [
            [2, 'desc'],
            [0, 'asc']
        ]
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</html>