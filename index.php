<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>GECV Hostel Complaint Portal</title>
<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #007bff, #00d4ff);
    color: white;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .container {
    text-align: center;
    background: rgba(0,0,0,0.4);
    padding: 40px 60px;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    max-width: 450px;
  }
  h1 {
    margin-bottom: 20px;
    font-size: 2.8rem;
  }
  p {
    font-size: 1.2rem;
    margin-bottom: 40px;
  }
  a.button {
    display: inline-block;
    margin: 0 15px;
    padding: 15px 40px;
    background: white;
    color: #007bff;
    font-weight: bold;
    font-size: 1.1rem;
    border-radius: 50px;
    text-decoration: none;
    box-shadow: 0 5px 15px rgba(255,255,255,0.3);
    transition: all 0.3s ease;
  }
  a.button:hover {
    background: #0056b3;
    color: white;
    box-shadow: 0 8px 20px rgba(0, 86, 179, 0.7);
  }
</style>
</head>
<body>
  <div class="container">
    <h1>Welcome to GECV Hostel Complaint Portal</h1>
    <p>Your platform to quickly report and resolve hostel issues for a better living experience.</p>
    <a href="login.php" class="button">Login</a>
    <a href="register.php" class="button">Register</a>
  </div>
</body>
</html>