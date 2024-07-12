<?php

$env = parse_ini_string(file_get_contents(__DIR__.'/.env'));
$SERVER = $env['HOSTNAME'];
$USER = $env['USERNAME'];
$PSWD = $env['PASSWORD'];
$DB = $env['DBNAME'];

$con = mysqli_connect($SERVER, $USER, $PSWD, $DB);