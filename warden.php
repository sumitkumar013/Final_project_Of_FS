<?php
session_start();
require 'db.php';

// Check if the user is logged in and is a warden
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'warden') {
    header("Location: login.php");
    exit();
}

// Fetch complaints submitted by students
$sql = "SELECT c.id, u.username, c.problem_category, c.description, c.created_at 
        FROM complaints c 
        JOIN users u ON c.user_id = u.id 
        ORDER BY c.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Warden Dashboard - View Complaints</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 1000px;
      margin: 40px auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #007bff;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ccc;
      text-align: left;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .logout {
      text-align: center;
      margin-top: 20px;
    }
    .logout a {
      color: red;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Complaints Submitted by Students</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Student Username</th>
        <th>Problem Category</th>
        <th>Description</th>
        <th>Created At</th>
      </tr>
      <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['problem_category']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="text-align:center;">No complaints found.</td>
        </tr>
      <?php endif; ?>
    </table>

    <div class="logout">
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
