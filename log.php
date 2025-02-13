<?php
// Start session
session_start();

// Include database connection
include_once("db.php");

// Check if database connection is initialized
if (!isset($conn) || !$conn) {
    die("Database connection not initialized. Check your db.php file.");
}

// Check if form variables are set
if (isset($_POST['t9']) && isset($_POST['t10'])) {
    $mcn = $_POST['t9'];
    $password = $_POST['t10'];

    // Create a prepared statement
    $stmt = $conn->prepare("SELECT * FROM login WHERE mcn = ? AND pwd = ?");
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("ss", $mcn, $password);

        // Execute the statement
        $stmt->execute();

        // Store the result
        $stmt->store_result();

        // Check if there is a matching record
        if ($stmt->num_rows === 1) {
            // Successful login
            $_SESSION['mcn'] = $mcn;
            header("Location: user.php");
            exit;
        } else {
            // Incorrect login credentials
            header("Location: login.php?x=3");
            exit;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        die("Database query failed: " . $conn->error);
    }
} else {
    // Redirect if accessed without POST data
    header("Location: login.php");
    exit;
}
?>
