<?php
require_once 'db.php'; // include DB connection

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admission_no = trim($_POST['admission_no']);
    $name = trim($_POST['name']);
    $age = (int) $_POST['age'];
    $semester = (int) $_POST['semester'];
    $dept = trim($_POST['dept']);
    $blood_group = strtoupper(trim($_POST['blood_group']));
    $contact1 = trim($_POST['contact1']);
    $contact2 = trim($_POST['contact2']);
    $location = trim($_POST['location']);
    $password = trim($_POST['password']); 

    // Validation
    if (empty($admission_no) || !preg_match("/^[A-Za-z0-9]+$/", $admission_no)) {
        $errors[] = "Admission No should be alphanumeric and not empty.";
    }
    if (empty($name) || !preg_match("/^[A-Za-z ]+$/", $name)) {
        $errors[] = "Name must contain only letters and spaces.";
    }
    if ($age < 18 || $age > 65) {
        $errors[] = "Age must be between 18 and 65.";
    }
    if ($semester < 1 || $semester > 8) {
        $errors[] = "Semester must be between 1 and 8.";
    }
    $allowed_groups = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
    if (!in_array($blood_group, $allowed_groups)) {
        $errors[] = "Invalid blood group.";
    }
    if (!preg_match("/^[0-9]{10}$/", $contact1)) {
        $errors[] = "Contact1 must be a 10-digit number.";
    }
    if (!empty($contact2) && !preg_match("/^[0-9]{10}$/", $contact2)) {
        $errors[] = "Contact2 must be a 10-digit number (if provided).";
    }
    if (empty($location)) {
        $errors[] = "Location cannot be empty.";
    }
    if (empty($password) || strlen($password) != 5) {
        $errors[] = "Password must be exactly 5 characters long.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users 
            (admission_no, name, age, semester, dept, blood_group, contact1, contact2, location, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssiissssss",
            $admission_no, $name, $age, $semester, $dept, $blood_group, $contact1, $contact2, $location, $password
        );

        if ($stmt->execute()) {
            header("Location: index.php?status=success");
            exit();
        } else {
            $errors[] = "Database Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - BloodLine</title>
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
    padding: 25px 35px;   /* 🔹 reduced padding */
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    text-align: center;
    max-width: 400px;     /* 🔹 smaller width */
    width: 90%;
}
h1 {
    color: darkred;
    font-size: 26px;      /* 🔹 smaller font */
    margin-bottom: 15px;
}
input[type="text"], input[type="number"], select {
    width: 100%;
    padding: 8px 10px;    /* 🔹 compact inputs */
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}
button {
    padding: 10px 18px;   /* 🔹 smaller button */
    font-size: 15px;
    font-weight: bold;
    color: white;
    background-color: darkred;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 12px;
    transition: background 0.3s ease, transform 0.2s ease;
}
button:hover {
    background-color: crimson;
    transform: translateY(-2px);
}
.error-msg {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 8px 12px;    /* 🔹 smaller error box */
    border-radius: 6px;
    margin-bottom: 10px;
    font-size: 13px;
    text-align: left;
}
</style>
</head>
<body>
<div class="container">
    <h1>Register 📝</h1>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $e) {
            echo "<div class='error-msg'>❌ $e</div>";
        }
    }
    ?>

    <form method="POST" action="">
        <input type="text" name="admission_no" placeholder="Admission No" required>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="number" name="age" placeholder="Age" min="18" max="65" required>
        <input type="number" name="semester" placeholder="Semester (1-8)" min="1" max="8" required>

        <select name="dept" required>
            <option value="">--Select Department--</option>
            <option>CSE</option><option>ECE</option><option>MECH</option>
            <option>CIVIL</option><option>EEE</option><option>IT</option>
        </select>

        <select name="blood_group" required>
            <option value="">--Select Blood Group--</option>
            <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
            <option>O+</option><option>O-</option><option>AB+</option><option>AB-</option>
        </select>

        <input type="text" name="contact1" placeholder="Contact 1 (10 digits)" required>
        <input type="text" name="contact2" placeholder="Contact 2 (Optional)">

        <select name="location" required>
            <option value="">--Select District--</option>
            <option>Thiruvananthapuram</option><option>Kollam</option><option>Pathanamthitta</option>
            <option>Alappuzha</option><option>Kottayam</option><option>Idukki</option>
            <option>Ernakulam</option><option>Thrissur</option><option>Palakkad</option>
            <option>Malappuram</option><option>Kozhikode</option><option>Wayanad</option>
            <option>Kannur</option><option>Kasaragod</option>
        </select>

        <input type="text" name="password" placeholder="Password (5 chars)" required>
        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>
