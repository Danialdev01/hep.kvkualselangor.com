<?php



include '../databases/config.php';
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
} else {
    $username = $_SESSION['username'];
}




$msg = "";

if (isset($_POST['update'])) {

    $title = mysqli_real_escape_string($con, $_POST['title']);
    $date_issued = $_POST['date_issued'];
    // -- asrama --
    //report date
    $reportD_a = $_POST['reportD-a'];

    //place
    $reportP_a = $_POST['reportP-a'];

    // time from a to a2
    $reportT_a = $_POST['reportT-a'];
    $reportT_a2 = $_POST['reportT-a2'];



    // -- Outsider --
    //report date
    $reportD_o = $_POST['reportD-o'];

    //report place outsider
    $reportP_o = $_POST['reportP-o'];

    //report time for outsider
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



    if ($execSet == TRUE) {

        $msg = "<p class='text-bg-success p-3'>Surat Tawaran Sistem SemakDVM berjaya dikemaskini!</p>";

    } else {

        $msg = "<p class='text-bg-danger p-3'>Surat Tawaran Sistem SemakDVM gagal dikemaskini!</p>";

    }

}

$currentDocSearch = "SELECT * FROM document";
$currentDocSet = mysqli_query($con, $currentDocSearch);
$currentDoc = mysqli_fetch_array($currentDocSet);





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



<!-- <style>

    body {

        display: flex;

        min-height: 100vh;

        flex-direction: column;

    }



    footer {

        margin-top: auto;

    } -->

</style>



<body>
    <?php $page="config"; require_once('navbar.php'); ?>
        <div class="container text-center mt-3 bg-light">
        <img src="../images/kpmkvks.png" class="w-75">
        <h3 class="h3 p-3">UNIT HAL EHWAL PELAJAR<br>KOLEJ VOKASIONAL KUALA SELANGOR</h3>
    </div>


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

    </form><br>



</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>



</html>