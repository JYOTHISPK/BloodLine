<?php
// Check for registration success
$success_msg = "";
if (isset($_GET['status']) && $_GET['status'] === 'success') {
    $success_msg = "✅ Registration successful!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Blood Donation System</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #fff5f5, #ffe5e5);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .container {
      background: white;
      padding: 40px 60px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      text-align: center;
      max-width: 500px;
      width: 90%;
      position: relative;
    }
    h1 {
      color: darkred;
      font-size: 32px;
      margin-bottom: 10px;
    }
    p {
      font-size: 18px;
      color: #444;
      margin-bottom: 30px;
    }
    .success-msg {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 12px 20px;
      border-radius: 8px;
      margin-top: 25px;
      font-weight: bold;
      font-size: 16px;
    }
    .btn {
      display: inline-block;
      margin: 10px;
      padding: 12px 25px;
      font-size: 17px;
      font-weight: bold;
      text-decoration: none;
      color: white;
      background-color: darkred;
      border-radius: 8px;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    .btn:hover {
      background-color: crimson;
      transform: translateY(-3px);
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>BloodLine 🫀</h1>

    <p>"Do you bleed? Then please choose an option:"</p>
    <a href="login.php" class="btn">🔑 Login</a>
    <a href="register.php" class="btn">📝 New User? Register</a>

    <?php if ($success_msg != ""): ?>
        <div class="success-msg"><?php echo $success_msg; ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
