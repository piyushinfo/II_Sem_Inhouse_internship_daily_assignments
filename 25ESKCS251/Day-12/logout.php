<?php
// logout.php — Module 4's clean session destroy pattern
session_start();

// Step 1: Clear all session data
$_SESSION = array();

// Step 2: Destroy the session on the server
session_destroy();

// Step 3: Send the user back to login
header("Location: login.php");
exit();
?>
