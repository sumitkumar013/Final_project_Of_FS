<?php
session_start();
require 'db.php';



if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}



$user_id = $_SESSION['user_id'];



// Validate POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $problem_category = trim($_POST['problem_category'] ?? '');
    $description = trim($_POST['description'] ?? '');



    if (empty($problem_category) || empty($description)) {
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: dashboard.php");
        exit();
    }



    // Insert complaint into DB
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, problem_category, description) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $problem_category, $description);



    if ($stmt->execute()) {
        $_SESSION['success'] = "Complaint submitted successfully.";
    } else {
        $_SESSION['error'] = "Failed to submit complaint, please try again.";
    }
    $stmt->close();



    header("Location: dashboard.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>