<?php
session_start();

// Unset the 'mcn' session variable
unset($_SESSION['mcn']);

// Optionally destroy the entire session (this is optional if you want to clear everything)
session_destroy();

// Redirect the user to index.php
header("Location: index.php");
exit(); // Ensure that no further code is executed after the redirect
?>
