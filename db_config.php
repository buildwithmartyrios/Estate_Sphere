<?php
// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "estate_sphere";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session for user tracking
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>