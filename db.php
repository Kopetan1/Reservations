<?php
$host = "localhost";
$database = "kopetad1_hostel";
$password = "V55555z";




$db = new PDO(
    "mysql:host=$host;",
    $database,
    $password
);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("use $database");