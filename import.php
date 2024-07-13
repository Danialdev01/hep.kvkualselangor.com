<?php
include 'databases/config.php';
if (isset($_POST["Import"])) {

    if (isset($_FILES["file"])) {
        $file = mysqli_real_escape_string($con,$_FILES['file']['tmp_name']);
        $handle = fopen($file, "r");
        $c = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== false) {
            // echo $data[0];
            // echo "<br>";
            // echo $data[2];
            // echo "<br>";
            // echo $data[3];
            // echo "<br>";
            // echo $data[4];
            // echo "<br>";
            // echo $data[5];
            // echo "<br>";
            // echo "<br>";
            $query = mysqli_query($con, "INSERT INTO student (`id`, `full_name`, `nokp`, `programme`, `year_s`, `edu`, `residence`) VALUES (NULL, '$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]')");
        }

        if ($query) {
            echo "
		<script>
			window.alert('Maklumat pelajar berjaya dikemaskini!');
			window.location = 'student-app.php';
		</script>
		";
        } else {
            echo "
		<script>
			window.alert('Ralat ketika proses kemaskini! Sila pastikan format fail anda adalah tepat sebelum mencuba lagi!');
			window.location = 'import.php';
		</script>
		";
        }
        fclose($handle);
    }
}
