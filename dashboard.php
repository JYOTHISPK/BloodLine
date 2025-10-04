<?php
session_start();
if (!isset($_SESSION['admission_no'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Blood Donation</title>
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
    .navbar h2 {
      margin: 0;
    }
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
    .nav-links a:hover {
      color: #ffcccc;
    }

    /* Dashboard Container */
    .container {
      padding: 30px;
      max-width: 1000px;
      margin: auto;
    }

    .welcome-card {
      background: linear-gradient(135deg, #fff5f5, #ffe5e5);
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      text-align: center;
    }
    .welcome-card h3 {
      margin: 0;
      font-size: 24px;
      color: darkred;
    }
    .welcome-card p {
      margin: 5px 0 0;
      color: #555;
    }

    /* Grid Layout for Actions */
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .card {
      background: white;
      border-radius: 12px;
      padding: 25px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    }
    .card h4 {
      margin-top: 10px;
      color: darkred;
    }
    .card p {
      color: #666;
      font-size: 14px;
    }
    .icon {
      font-size: 32px;
    }

    /* Description Section */
    .desc {
      margin-top: 40px;
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .desc h3 {
      margin-top: 0;
      color: darkred;
    }
    .desc p {
      color: #444;
      line-height: 1.6;
    }

    /* Make anchor take full card */
    .grid a {
      text-decoration: none;
      color: inherit;
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

  <!-- Dashboard -->
  <div class="container">
    <!-- Welcome -->
    <div class="welcome-card">
      <h3>Welcome, <?php echo $_SESSION['name']; ?> 👋</h3>
    </div>

    <!-- Quick Actions -->
    <div class="grid">
      <a href="about.php">
        <div class="card">
          <div class="icon">ℹ️</div>
          <h4>About Us</h4>
          <p>Know more about our project and creators.</p>
        </div>
      </a>

      <a href="request.php">
        <div class="card">
          <div class="icon">🩸</div>
          <h4>Request Blood</h4>
          <p>Submit a request if you needs blood urgently.</p>
        </div>
      </a>

      <a href="acceptance.php">
        <div class="card">
          <div class="icon">🔔</div>
          <h4>Notifications</h4>
          <p>Stay updated with blood requests and responses.</p>
        </div>
      </a>

      <a href="profile.php">
        <div class="card">
          <div class="icon">👤</div>
          <h4>Profile</h4>
          <p>View and update your personal information.</p>
        </div>
      </a>
    </div>

    <!-- Description Section -->
    <div class="desc">
      <h3>Saving Lives, One Drop at a Time ⏳</h3>
      <p >
        Blood donation is one of the most powerful ways an individual can contribute to 
        society. A single donation has the potential to save multiple lives, offering hope
         and healing to patients facing accidents, surgeries, or critical health conditions.
          Every drop counts, and by donating blood, you are directly shaping a healthier, 
          more resilient community.     
      </p>
      <p>
        Unfortunately, in times of emergency, patients and families often struggle to find
        donors quickly. This is where our platform steps in. We aim to bridge the gap between
        those in urgent need and those willing to help, creating a network where time is not
        lost and lives are not put at risk. 
      </p>
      <p>
        Our system connects donors and recipients with ease, ensuring that requests are 
        seen promptly and matched effectively. With features that prioritize reliability,
         transparency, and accessibility, we strive to build a community where compassion 
         meets action.
      </p>
      <p>
        This project was developed with a clear mission: to make blood donation not just
         a rare act of kindness, but a consistent, organized effort that transforms 
         healthcare outcomes. By becoming a part of this initiative, you are not just 
         donating blood — you are donating hope, strength, and life itself.
      </p>
      <p style="text-align: center;margin-top: 40px; font-style: italic; color: #555;">“Together, we make BloodLine not just an app, but a family.” ❤️</p>

    </div>
</body>
</html>
