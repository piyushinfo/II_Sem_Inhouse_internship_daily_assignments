<?php
/**
 * db_connect.php
 * Opens one shared connection to MySQL. Every page that needs the
 * database includes this file first: include('db_connect.php');
 */

$host     = "localhost";
$user     = "root";
$password = "";
$database = "student_management";

$conn = mysqli_connect($host, $user, $password, $database);

// If the connection fails, stop everything and show why.
if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Uncomment the two lines below if you want a visible confirmation
// banner on the page while you're testing the connection.
// echo '<div class="alert alert-success text-center mb-0">';
// echo 'Connected! Server info: ' . mysqli_get_host_info($conn) . '</div>';
?>
