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
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ? AND role = ?");
        $stmt->bind_param("ss", $username, $role);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                if ($role === 'warden') {
                    header("Location: warden.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $message = "Invalid username or password.";
            }
        } else {
            $message = "Invalid username or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Login - GECV Hostel Complaint Portal</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5;
    transition: background-color 1s ease;
    margin: 0;
    padding: 0;
  }

  .container {
    max-width: 420px;
    margin: 60px auto;
    background: #ffffff;
    padding: 30px 25px; /* Adjusted horizontal padding */
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
  }

  .container:hover {
    transform: scale(1.02);
  }

  h2 {
    text-align: center;
    color: #007bff;
    margin-bottom: 25px;
    font-weight: 600;
  }

  input[type="text"],
  input[type="password"],
  select {
    width: 100%;
    padding: 12px 15px;
    margin: 12px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 15px;
    box-sizing: border-box;
  }

  input:focus,
  select:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.4);
    outline: none;
  }

  button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
  }

  button:hover {
    background: linear-gradient(45deg, #0056b3, #007bff);
    transform: scale(1.03);
  }

  .message {
    color: red;
    text-align: center;
    margin-bottom: 10px;
    font-weight: bold;
  }

  .register-link {
    text-align: center;
    margin-top: 20px;
    font-size: 14px;
  }

  .register-link a {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
  }

  .register-link a:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>
<div class="container">
  <h2>Login - GECV Hostel Complaint Portal</h2>
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
    <button type="submit">Login</button>
  </form>
  <div class="register-link">
    Don't have an account? <a href="register.php">Register here</a>
  </div>
</div>

<script>
  const colors = ['#f0f2f5', '#e0f7fa', '#fce4ec', '#fff3e0', '#ede7f6', '#e8f5e9'];
  let current = 0;

  setInterval(() => {
    document.body.style.backgroundColor = colors[current];
    current = (current + 1) % colors.length;
  }, 2000);
</script>
</body>
</html>
