<?php
include 'db.php';
session_start();

// --- Check login ---
if (!isset($_SESSION['admission_no'])) {
    header("Location: login.php");
    exit();
}

$donor_admission_no = $_SESSION['admission_no'];
$name = $_SESSION['name'];

// --- Get donor's blood group ---
$sql = "SELECT blood_group FROM users WHERE admission_no = '$donor_admission_no'";
$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    $donor = $res->fetch_assoc();
    $donor_blood = $donor['blood_group'];
} else {
    die("Could not fetch donor details.");
}

// --- Check rest period ---
$rest_check = "SELECT * FROM donated WHERE donor_admission_no = '$donor_admission_no'";
$rest_res = $conn->query($rest_check);
$on_rest = ($rest_res && $rest_res->num_rows > 0);

// --- Handle Accept ---
if (isset($_GET['accept_id'])) {
    $request_id = intval($_GET['accept_id']);
    $insert = "INSERT INTO accepts (request_id, donor_admission_no, status) 
               VALUES ($request_id, '$donor_admission_no', 'accepted')";
    if ($conn->query($insert)) {
        $msg = "<div class='success'>✅ You accepted request #$request_id successfully!</div>";
    } else {
        $msg = "<div class='error'>❌ Error: " . $conn->error . "</div>";
    }
}

// --- Handle Decline ---
if (isset($_GET['decline_id'])) {
    $request_id = intval($_GET['decline_id']);
    $insert = "INSERT INTO accepts (request_id, donor_admission_no, status) 
               VALUES ($request_id, '$donor_admission_no', 'declined')";
    if ($conn->query($insert)) {
        $msg = "<div class='warning'>⚠️ You declined request #$request_id.</div>";
    } else {
        $msg = "<div class='error'>❌ Error: " . $conn->error . "</div>";
    }
}

// --- Fetch matching requests ---
$sql = "SELECT r.request_id, r.requester_admission_no, r.blood_group, r.request_date
        FROM requests r
        WHERE r.blood_group = '$donor_blood'
          AND r.request_id NOT IN (
              SELECT request_id FROM accepts WHERE donor_admission_no = '$donor_admission_no'
          )";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications - BloodLine</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", Tahoma, sans-serif;
      background: #fafafa;
    }

    /* Navbar */
    .navbar {
      background-color: darkred;
      padding: 15px 40px;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .navbar h2 { margin: 0; }
    .nav-links {
      display: flex;
      gap: 20px;
    }
    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }
    .nav-links a:hover { color: #ffcccc; }

    /* Container */
    .container {
      padding: 30px;
      max-width: 900px;
      margin: auto;
    }

    .title {
      text-align: center;
      margin-bottom: 20px;
      color: darkred;
    }

    .message { text-align: center; margin-bottom: 15px; }
    .success { color: green; font-weight: bold; }
    .warning { color: orange; font-weight: bold; }
    .error { color: red; font-weight: bold; }

    /* Request Cards */
    .card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }
    .card:hover { transform: translateY(-4px); }
    .card h4 { margin: 0; color: darkred; }
    .card p { margin: 5px 0; color: #555; }

    .actions {
      margin-top: 10px;
    }
    .btn {
      padding: 8px 14px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      margin-right: 10px;
      text-decoration: none;
      display: inline-block;
    }
    .accept { background: darkred; color: white; }
    .accept:hover { background: #a60000; }
    .decline { background: #ccc; color: black; }
    .decline:hover { background: #999; }

    .rest {
      text-align: center;
      padding: 20px;
      background: #ffe5e5;
      border: 1px solid darkred;
      border-radius: 12px;
      color: darkred;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <div class="navbar">
    <h2>BloodLine 🫀</h2>
    <div class="nav-links">
      <a href="dashboard.php">🏠 Home</a>
      <a href="about.php">ℹ️ About</a>
      <a href="request.php">🩸 Make Request</a>
      <a href="acceptance.php">🔔 Notifications</a>
      <a href="profile.php">👤 Profile</a>
    </div>
  </div>

  <div class="container">
    <h2 class="title">🔔 Notifications for <?php echo $name; ?></h2>

    <?php if (isset($msg)) echo "<div class='message'>$msg</div>"; ?>

    <?php if ($on_rest): ?>
      <div class="rest">
        ⏳ You are currently in rest period after donation.  
        Please wait before accepting new requests.
      </div>
    <?php else: ?>
      <?php
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<div class='card'>
                      <h4>Request #{$row['request_id']}</h4>
                      <p><b>Requester:</b> {$row['requester_admission_no']}</p>
                      <p><b>Blood Group:</b> {$row['blood_group']}</p>
                      <p><b>Date:</b> {$row['request_date']}</p>
                      <div class='actions'>
                        <a class='btn accept' href='acceptance.php?accept_id={$row['request_id']}'>✅ Accept</a>
                        <a class='btn decline' href='acceptance.php?decline_id={$row['request_id']}'>❌ Decline</a>
                      </div>
                    </div>";
          }
      } else {
          echo "<p style='text-align:center; color:#555;'>No matching requests right now.</p>";
      }
      ?>
    <?php endif; ?>
  </div>

  <!-- Auto-refresh every 10s -->
  <script>
  setInterval(() => {
    location.reload();
  }, 10000);
  </script>
</body>
</html>
