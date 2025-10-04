<?php
include 'db.php';  // Database connection
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admission_no = trim($_POST['admission_no']);
    $password = trim($_POST['password']);

    // Fetch user by admission_no
    $sql = "SELECT * FROM users WHERE admission_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admission_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ✅ Plain 5-digit password check
        if ($password === $row['password']) {
            $_SESSION['admission_no'] = $row['admission_no'];
            $_SESSION['name'] = $row['name'];

            // Later → redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "<p style='color:red;'>❌ Invalid password!</p>";
        }
    } else {
        $message = "<p style='color:red;'>❌ No user found with this Admission Number!</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Login</title>
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
      padding: 40px 50px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      text-align: center;
      max-width: 400px;
      width: 90%;
    }
    h2 {
      color: darkred;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 18px;
      text-align: left;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 6px;
    }
    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }
    button {
      width: 100%;
      background: darkred;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    button:hover {
      background: crimson;
      transform: translateY(-2px);
    }
    .message {
      margin: 15px 0;
    }
    .register-link {
      margin-top: 15px;
    }
    .register-link a {
      color: darkred;
      text-decoration: none;
      font-weight: bold;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>🔑 User Login</h2>

    <!-- Show messages -->
    <div class="message">
      <?php if (!empty($message)) echo $message; ?>
    </div>

    <form method="POST" action="login.php">
      <div class="form-group">
        <label for="admission_no">Admission No:</label>
        <input type="text" id="admission_no" name="admission_no" required>
      </div>

      <div class="form-group">
        <label for="password">Password (5 digits):</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit">Login</button>
    </form>

    <div class="register-link">
      <p>📝 New user? <a href="register.php">Register here</a></p>
    </div>
  </div>
</body>
</html>
