<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['admission_no'])) {
    header("Location: login.php");
    exit();
}

$admission_no = $_SESSION['admission_no'];

// Fetch user data
$sql = "SELECT * FROM users WHERE admission_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $admission_no);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $semester = $_POST['semester'];
    $dept = $_POST['dept'];
    $blood_group = $_POST['blood_group'];
    $contact1 = $_POST['contact1'];
    $contact2 = $_POST['contact2'];
    $location = $_POST['location'];

    $update_sql = "UPDATE users SET name=?, age=?, semester=?, dept=?, blood_group=?, contact1=?, contact2=?, location=? WHERE admission_no=?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sisssssss", $name, $age, $semester, $dept, $blood_group, $contact1, $contact2, $location, $admission_no);

    if ($update_stmt->execute()) {
        $success = "Profile updated successfully.";
        // Refresh user data
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $admission_no);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $error = "Error updating profile.";
    }
}

// Fetch donation history
$donation_sql = "SELECT * FROM records WHERE donor_admission_no = ? OR requester_admission_no = ? ORDER BY donated_date DESC";
$donation_stmt = $conn->prepare($donation_sql);
$donation_stmt->bind_param("ss", $admission_no, $admission_no);
$donation_stmt->execute();
$donation_history = $donation_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - BloodLine</title>
    <style>
        body { margin: 0; font-family: "Segoe UI", Tahoma, sans-serif; background: #fafafa; }
        .navbar { background-color: darkred; padding: 15px 40px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar h2 { margin: 0; }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a { color: white; text-decoration: none; font-weight: 500; padding: 0; }
        .nav-links a:hover { color: #ffcccc; }

        .container { padding: 30px; max-width: 1000px; margin: auto; }
        .alert { padding: 12px 18px; margin-bottom: 20px; border-radius: 8px; font-weight: bold; }
        .alert.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        .profile-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .profile-card h3 { margin-top: 0; color: darkred; }
        .profile-form { display: flex; flex-wrap: wrap; gap: 20px; }
        .profile-form .form-group { flex: 1 1 45%; min-width: 200px; }
        .profile-form label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        .profile-form input, .profile-form select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px; }

        .btn { padding: 10px 20px; margin-top: 15px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .btn-update { background: darkred; color: white; }
        .btn-update:hover { background: #a30000; }
        .btn-save { background: green; color: white; display: none; }
        .btn-save:hover { background: darkgreen; }

        table { border-collapse: collapse; width: 100%; margin-top: 10px; background: white; border-radius: 8px; overflow: hidden; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        th { background: #f2f2f2; color: darkred; }
        tr:hover { background: #f9f9f9; }
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
            <a href="login.php">🚪 Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success)) echo "<div class='alert success'>$success</div>"; ?>
        <?php if (isset($error)) echo "<div class='alert error'>$error</div>"; ?>

        <div class="profile-card">
            <h3>👤 My Profile</h3>
            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo $user['name']; ?>" class="editable" readonly>
                </div>

                <div class="form-group">
                    <label>Age:</label>
                    <input type="number" name="age" value="<?php echo $user['age']; ?>" class="editable" readonly>
                </div>

                <div class="form-group">
                    <label>Semester:</label>
                    <select name="semester" class="editable" disabled>
                        <?php
                        for ($i=1; $i<=8; $i++) {
                            $selected = ($user['semester'] == $i) ? "selected" : "";
                            echo "<option value='$i' $selected>$i</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Department:</label>
                    <select name="dept" class="editable" disabled>
                        <?php
                        $departments = ["CSE","MECH","EEE","CIVIL","ECE","IT"];
                        foreach ($departments as $d) {
                            $selected = ($user['dept'] == $d) ? "selected" : "";
                            echo "<option value='$d' $selected>$d</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Blood Group:</label>
                    <select name="blood_group" class="editable" disabled>
                        <?php
                        $groups = ["A+","A-","B+","B-","O+","O-","AB+","AB-"];
                        foreach ($groups as $g) {
                            $selected = ($user['blood_group'] == $g) ? "selected" : "";
                            echo "<option value='$g' $selected>$g</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Contact 1:</label>
                    <input type="text" name="contact1" value="<?php echo $user['contact1']; ?>" class="editable" readonly>
                </div>

                <div class="form-group">
                    <label>Contact 2:</label>
                    <input type="text" name="contact2" value="<?php echo $user['contact2']; ?>" class="editable" readonly>
                </div>

                <div class="form-group">
                    <label>Location:</label>
                    <select name="location" class="editable" disabled>
                        <?php
                        $locations = ["Thiruvananthapuram","Kollam","Pathanamthitta","Alappuzha","Kottayam","Idukki",
                                      "Ernakulam","Thrissur","Palakkad","Malappuram","Kozhikode",
                                      "Wayanad","Kannur","Kasaragod"];
                        foreach ($locations as $loc) {
                            $selected = ($user['location'] == $loc) ? "selected" : "";
                            echo "<option value='$loc' $selected>$loc</option>";
                        }
                        ?>
                    </select>
                </div>

                <div style="flex: 1 1 100%; text-align: center;">
                    <button type="button" id="editBtn" class="btn btn-update" onclick="enableEdit()">✏️ Update Profile</button>
                    <button type="submit" name="update_profile" id="saveBtn" class="btn btn-save">💾 Save Changes</button>
                </div>
            </form>
        </div>

        <div class="profile-card">
            <h3>📜 My Donation History</h3>
            <table>
                <tr>
                    <th>Donor</th>
                    <th>Requester</th>
                    <th>Date</th>
                </tr>
                <?php while ($row = $donation_history->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['donor_admission_no']; ?></td>
                        <td><?php echo $row['requester_admission_no']; ?></td>
                        <td><?php echo $row['donated_date']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>

    <script>
        function enableEdit() {
            document.querySelectorAll(".editable").forEach(el => {
                el.removeAttribute("readonly");
                el.removeAttribute("disabled");
            });
            document.getElementById("saveBtn").style.display = "inline-block";
            document.getElementById("editBtn").style.display = "none";
        }
    </script>
</body>
</html>
