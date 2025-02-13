<?php
include_once("db.php"); // Ensure db.php contains a valid mysqli connection setup

// Capture form data
$a1 = $_POST['t1']; // First Name
$a2 = $_POST['t2']; // Last Name
$a3 = $_POST['t3']; // Metro Card Number
$a4 = $_POST['t4']; // Mobile Number
$a5 = $_POST['t5']; // Email Address
$a6 = $_POST['t6']; // Password

// Prepare database connection
$conn = new mysqli($host, $user, $password, $database); // Update with your database credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if Metro Card Number already exists
$stmt = $conn->prepare("SELECT * FROM login WHERE mcn = ?");
$stmt->bind_param("s", $a3);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Metro Card Number already exists
    header("Location: login.php?x=1");
} else {
    // Insert data into tables using a transaction
    $conn->begin_transaction();

    try {
        // Insert into login table
        $stmt = $conn->prepare("INSERT INTO login (mcn, pwd) VALUES (?, ?)");
        $stmt->bind_param("ss", $a3, $a6);
        $stmt->execute();

        // Insert into signup table
        $stmt = $conn->prepare("INSERT INTO signup (fname, lname, mcn, mb_no, email, pwd) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $a1, $a2, $a3, $a4, $a5, $a6);
        $stmt->execute();

        // Insert into balance table with balance set to 0 and the current timestamp
        $stmt = $conn->prepare("INSERT INTO balance (mcn, user_name, balance_amount, last_updated) VALUES (?, ?, ?, ?)");
        $full_name = $a1 . ' ' . $a2; // Concatenate first name and last name
        $balance_amount = 0; // Default balance amount
        $timestamp = date("Y-m-d H:i:s"); // Current timestamp
        $stmt->bind_param("ssds", $a3, $full_name, $balance_amount, $timestamp);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Redirect to login with success
        header("Location: login.php?x=2");
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

// Close connection
$conn->close();
?>
