<?php
// db.php - Database connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";    // Change if needed
$password = "";        // Change if needed
$dbname = "gecv_hostel_complaint";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>