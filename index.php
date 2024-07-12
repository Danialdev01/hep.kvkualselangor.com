<?php
// error_reporting(0);

include 'databases/config.php';
$error = "";
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['id']);
    $password = mysqli_real_escape_string($con, $_POST['pswd']);

    $searchUser = "SELECT username, password FROM user_acc WHERE username = '$username'";
    $result = mysqli_query($con, $searchUser);
    if ($result == TRUE) {
        $check = mysqli_fetch_array($result);
        $hashed_PSWD = $check['password'];
        if (password_verify($password, $hashed_PSWD)) {
            session_start();
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "<p class='text-bg-danger p-3'>ID atau Kata Laluan Pegawai Tidak Dijumpai!</p>";
        }
    } else {
        echo "Error";
    }
    

    
}


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
    <div class="container text-center mt-3 bg-light">
        <img src="./images/kpmkvks.png" class="w-75">
        <h3 class="h3 p-3">UNIT HAL EHWAL PELAJAR<br>KOLEJ VOKASIONAL KUALA SELANGOR</h3>

    </div>

    <form method="post">
        <div class="container mt-3 text-bg-primary p-3">
            <?php echo $error; ?>

            <div class="row">
                <div class="col-sm">
                    <p><strong>ID Pengguna</strong></p>
                    <input type="text" class="form-control" name="id" required placeholder="Masukkan ID Pengguna">
                </div>
                <div class="col-sm">
                    <p><strong>Kata Laluan</strong></p>
                    <input type="password" class="form-control" name="pswd" required placeholder="Masukkan Kata Laluan">
                </div>
            </div>
            <div class="row text-center mt-2">
                <div class="col-md">
                    <input class="btn btn-success" name="login" type="submit" value="Log Masuk">
                </div>
            </div>
        </div>
    </form>




    <footer class="bg-dark text-white">
        <div class="container py-3">
            <div class="row">
                <div class="col-md-6">
                    <h5>Sistem HEP - Kolej Vokasional Kuala Selangor</h5>
                    <p>
                        Unit Teknologi Maklumat dengan kerjasama Jabatan Teknologi Maklumat<br>Kolej Vokasional Kuala Selangor
                    </p>
                </div>
                <div class="col-md-6">
                    <h5>Pautan Lain</h5>
                    <ul class="list-unstyled ">
                        <li><a style="text-decoration:none; color:white;" href="https://semakdvm.kvkualaselangor.com">SemakDVM - Semakan Kelayakan DVM</a></li>
                        <li><a style="text-decoration:none; color:white;" href="https://spksys.kvkualaselangor.com">Sistem Pendaftaran Kursus (SPKSys)</a></li>
                        <li><a style="text-decoration:none; color:white;" href="https://kvkualaselangor.edu.moe.my">Portal Rasmi Kolej Vokasional Kuala Selangor</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="bg-secondary text-center py-2">
            <p>Hak Cipta &copy <?php echo date("Y"); ?> <br>Kolej Vokasional Kuala Selangor</p>
        </div>
    </footer>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>

</html>