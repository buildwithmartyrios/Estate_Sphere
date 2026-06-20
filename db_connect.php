<?php
// Database configuration for WAMP Server
$host = "localhost";
$username = "root";     // Default WAMP username
$password = "";         // Default WAMP password is blank
$database = "estate_sphere"; // This matches the database name you created in phpMyAdmin

// Create the connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>