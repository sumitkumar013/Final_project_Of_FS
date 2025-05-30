<?php
session_start();
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        $message = "Please fill in all fields.";
    } else {
        // Warden limit check
        if ($role === 'warden') {
            $checkWarden = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'warden'");
            $result = $checkWarden->fetch_assoc();
            if ($result['total'] >= 2) {
                $message = "Maximum 2 warden registrations allowed.";
            }
        }

        if (!$message) {
            // Check if username exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $message = "Username already taken.";
            } else {
                // Register new user
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $hash, $role);
                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['role'] = $role;
                    if ($role === 'warden') {
                        header("Location: warden.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit();
                } else {
                    $message = "Registration failed. Please try again.";
                }
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Register - GECV Hostel Complaint Portal</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    animation: changeBG 8s infinite alternate;
  }

  @keyframes changeBG {
    0% { background-color: #f0f2f5; }
    50% { background-color: #dfe9f3; }
    100% { background-color: #f0f2f5; }
  }

  .container {
    max-width: 400px;
    margin: 50px auto;
    background: white;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.25);
  }

  h2 {
    text-align: center;
    color: #007bff;
    margin-bottom: 20px;
  }

  input[type="text"],
  input[type="password"],
  select {
    width: 100%;
    padding: 12px;
    margin: 10px 0 20px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    transition: 0.3s;
  }

  input[type="text"]:focus,
  input[type="password"]:focus,
  select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.5);
    outline: none;
  }

  button {
    width: 100%;
    padding: 12px;
    background-color: #007bff;
    border: none;
    color: white;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
  }

  button:hover {
    background-color: #0056b3;
  }

  .message {
    color: red;
    text-align: center;
    margin-bottom: 10px;
  }

  .login-link {
    text-align: center;
    margin-top: 15px;
  }

  .login-link a {
    color: #007bff;
    text-decoration: none;
  }

  .login-link a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>
<div class="container">
  <h2>Register - GECV Hostel Complaint Portal</h2>
  <?php if ($message): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
  <?php endif; ?>
  <form method="POST" action="">
    <input type="text" name="username" placeholder="Username" required autocomplete="off" />
    <input type="password" name="password" placeholder="Password" required />
    <select name="role" required>
      <option value="">Select Role</option>
      <option value="student">Student</option>
      <option value="warden">Warden</option>
    </select>
    <button type="submit">Register</button>
  </form>
  <div class="login-link">
    Already have an account? <a href="login.php">Login here</a>
  </div>
</div>
</body>
</html>
