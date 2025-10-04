<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About - BloodLine</title>
    <style>
        body { margin: 0; font-family: "Segoe UI", Tahoma, sans-serif; background: #fafafa; }
        .navbar { background-color: darkred; padding: 15px 40px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar h2 { margin: 0; }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { color: white; text-decoration: none; font-weight: 500; padding: 0; }
        .nav-links a:hover { color: #ffcccc; }

        .container { padding: 30px; max-width: 800px; margin: auto; text-align: center; }
        h1 { color: darkred; margin-bottom: 20px; }

        .team-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px; text-align: left; display: flex; gap: 20px; align-items: center; transition: transform 0.3s; }
        .team-card:hover { transform: translateY(-3px) scale(1.02); }
        .team-card img { width: 150px; height: 150px; border-radius: 10px; object-fit: cover; }
        .team-info { flex: 1; }
        .team-info h3 { margin: 0 0 8px; color: darkred; }
        .team-info p { margin: 5px 0; font-size: 14px; color: #333; }

        .team-info p span { font-weight: bold; }

        @media(max-width: 600px) { 
            .team-card { flex-direction: column; align-items: center; text-align: center; }
            .team-card img { width: 120px; height: 120px; }
            .team-info h3, .team-info p { text-align: center; }
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
        <h1>Team J-Line</h1>
        <p> Making BloodLine legendary, one mission at a time. </p>

        <!-- Member 1 -->
        <div class="team-card">
            <img src="images/john paul1.jpg" alt="John Paul">
            <div class="team-info">
                <h3>John Paul</h3>
                <p><span>Roll no:</span> 37 </p>
                <p><span>Dept   :</span> S5 CSE 😎</p>
                <p><span>Contact:</span> johnpaul1@gmail.com</p>
            </div>
        </div>

        <!-- Member 2 -->
        <div class="team-card">
            <img src="images/john paul2.jpg" alt="John Paul">
            <div class="team-info">
                <h3>John Paul</h3>
                <p><span>Roll no:</span> 38 </p>
                <p><span>Dept   :</span> S5 CSE 🤫</p>
                <p><span>Contact:</span> johnpaul2@gmail.com</p>
            </div>
        </div>

        <!-- Member 3 -->
        <div class="team-card">
            <img src="images/joseph.jpg" alt="Joseph Martin">
            <div class="team-info">
                <h3>Joseph Martin</h3>
                <p><span>Roll no:</span> 39</p>
                <p><span>Dept   :</span> S5 CSE 🥲</p>
                <p><span>Contact:</span> josephmartin@gmail.com</p>
            </div>
        </div>

        <!-- Member 4 -->
        <div class="team-card">
            <img src="images/jyothis.jpg" alt="Jyothis P K">
            <div class="team-info">
                <h3>Jyothis P K</h3>
                <p><span>Roll no:</span> 40</p>
                <p><span>Dept   :</span> S5 CSE 😴</p>
                <p><span>Contact:</span> jyothisf15@gmail.com</p>
            </div>
        </div>

        <p style="margin-top: 40px; font-style: italic; color: #555;">“Together, we make BloodLine not just an app, but a family.” ❤️</p>
    </div>
</body>
</html>
