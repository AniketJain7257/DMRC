<?php
// Database connection parameters
$host = "localhost";  // Your database host
$user = "root";       // Your database username (default for XAMPP is 'root')
$password = "";       // Your database password (default for XAMPP is an empty string)
$database = "metro";   // Your database name

// Create a connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
