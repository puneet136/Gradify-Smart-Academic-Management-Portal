<?php
$host = "localhost"; // Use the updated port;  // XAMPP default host
$user = "root";       // Default MySQL user
$password = "";       // Default password (empty in XAMPP)
$database = ""; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connected successfully!";
}
?>
