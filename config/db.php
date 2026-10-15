<?php
// Simple procedural MySQL connection using mysqli
$host = 'localhost';
$user = 's68160408';
$pass = 'y2r6r48G';
$dbname = 's68160408';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>
