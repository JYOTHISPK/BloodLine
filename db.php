<?php
$servername = "127.0.0.1:3307";  // 👈 host + port together
$username = "root";
$password = ""; // default for XAMPP
$dbname = "blood_donation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

//echo "DB ok"; // just to test
?>
