<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$message_success = $_SESSION['success'] ?? '';
$message_error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$stmt = $conn->prepare("SELECT id, problem_category, description, status, created_at FROM complaints WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$complaints = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - GECV Hostel Complaint Portal</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #007bff;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    header h1 {
      margin: 0;
      font-size: 24px;
    }

    header .logout {
      color: white;
      text-decoration: none;
      font-weight: bold;
      font-size: 14px;
    }

    .container {
      max-width: 960px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    h2 {
      color: #007bff;
      margin-top: 0;
    }

    form {
      display: grid;
      gap: 15px;
    }

    form label {
      font-weight: 600;
    }

    select, textarea {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    textarea {
      resize: vertical;
      height: 120px;
    }

    button {
      background-color: #007bff;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-weight: bold;
    }

    button:hover {
      background-color: #0056b3;
    }

    .status-pending {
      color: #e67e22;
      font-weight: bold;
    }

    .status-resolved {
      color: #27ae60;
      font-weight: bold;
    }

    .status-inprogress {
      color: #2980b9;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 30px;
      font-size: 14px;
    }

    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f1f1f1;
    }

    .message {
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    .success {
      background-color: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #c8e6c9;
    }

    .error {
      background-color: #ffebee;
      color: #c62828;
      border: 1px solid #ef9a9a;
    }

    @media (max-width: 600px) {
      header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
    }
  </style>
</head>
<body>

<header>
  <h1><i class="fas fa-tools"></i> GECV Hostel Complaint Portal</h1>
  <div>Welcome, <?= htmlspecialchars($username) ?> | <a href="logout.php" class="logout">Logout</a></div>
</header>

<div class="container">
  <h2><i class="fas fa-comment-dots"></i> Submit a Complaint</h2>

  <?php if ($message_success): ?>
    <div class="message success"><?= htmlspecialchars($message_success) ?></div>
  <?php endif; ?>
  <?php if ($message_error): ?>
    <div class="message error"><?= htmlspecialchars($message_error) ?></div>
  <?php endif; ?>

  <form method="POST" action="submit_complaint.php">
    <label for="problem_category"><i class="fas fa-list"></i> Select Problem Category</label>
    <select id="problem_category" name="problem_category" required>
      <option value="" disabled selected>-- Select a Problem --</option>
      <option value="Plumbing">🚰 Plumbing</option>
      <option value="Electrical">💡 Electrical</option>
      <option value="Cleanliness">🧹 Cleanliness</option>
      <option value="Food">🍽️ Food</option>
      <option value="Other">❓ Other</option>
    </select>

    <label for="description"><i class="fas fa-align-left"></i> Describe the Problem</label>
    <textarea id="description" name="description" placeholder="Enter detailed description here..." required></textarea>

    <button type="submit"><i class="fas fa-paper-plane"></i> Submit Complaint</button>
  </form>

  <h2><i class="fas fa-history"></i> Your Complaints</h2>

  <?php if (count($complaints) === 0): ?>
    <p>No complaints submitted yet.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Category</th>
          <th>Description</th>
          <th>Status</th>
          <th>Submitted On</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($complaints as $complaint): ?>
          <tr>
            <td><?= htmlspecialchars($complaint['id']) ?></td>
            <td><?= htmlspecialchars($complaint['problem_category']) ?></td>
            <td><?= nl2br(htmlspecialchars($complaint['description'])) ?></td>
            <td>
              <?php
                $status = strtolower($complaint['status']);
                if ($status === 'pending') {
                    echo '<span class="status-pending">Pending</span>';
                } elseif ($status === 'resolved') {
                    echo '<span class="status-resolved">Resolved</span>';
                } elseif ($status === 'in progress') {
                    echo '<span class="status-inprogress">In Progress</span>';
                } else {
                    echo htmlspecialchars($complaint['status']);
                }
              ?>
            </td>
            <td><?= htmlspecialchars($complaint['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

</body>
</html>
