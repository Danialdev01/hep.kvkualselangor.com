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
    $delete = "DELETE FROM student WHERE nokp = '$nokp'";
    $run = mysqli_query($con, $delete);

    if ($run) {
        echo "
        <script>
            window.alert('Data pelajar berjaya di padam!');
            window.location = 'student-app.php';
        </script>
        ";
    } else {
        echo "
        <script>
            window.alert('Data pelajar berjaya di padam!');
            window.location = 'student-view.php';
        </script>
        ";
    }
}


?>