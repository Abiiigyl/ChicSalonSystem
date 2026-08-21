<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "chic_salon_system";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding
$conn->set_charset("utf8mb4");
?>
