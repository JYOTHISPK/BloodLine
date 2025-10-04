<?php
session_start();
include("db.php");

// Ensure user is logged in
if (!isset($_SESSION['admission_no'])) {
    header("Location: login.php");
    exit();
}

$admission_no = $_SESSION['admission_no'];

// ================== HANDLE NEW REQUEST ==================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['make_request'])) {
    $blood_group = $_POST['blood_group'];
    $location = $_POST['location'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO requests (requester_admission_no, blood_group, location, reason, request_date)
            VALUES ('$admission_no', '$blood_group', '$location', '$reason', NOW())";
    if ($conn->query($sql)) {
        $message = "<div class='alert success'>✅ Request submitted successfully!</div>";
    } else {
        $message = "<div class='alert error'>❌ Error: " . $conn->error . "</div>";
    }
}

// ================== HANDLE CANCEL REQUEST ==================
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    $sql = "DELETE FROM requests WHERE request_id='$cancel_id' AND requester_admission_no='$admission_no'";
    if ($conn->query($sql)) {
        $message = "<div class='alert info'>ℹ️ Your request has been cancelled successfully.</div>";
    } else {
        $message = "<div class='alert error'>❌ Error: " . $conn->error . "</div>";
    }
}

// ================== HANDLE CONFIRM DONATION ==================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['donate'])) {
    $donor_admission_no = $_POST['donor_admission_no'];

    // Check if donor is in accepts table for this requester
    $check_sql = "SELECT * FROM accepts a 
                  JOIN requests r ON a.request_id = r.request_id
                  WHERE a.donor_admission_no = '$donor_admission_no'
                    AND r.requester_admission_no = '$admission_no'
                    AND a.status = 'accepted'";
    $check = $conn->query($check_sql);

    if ($check && $check->num_rows > 0) {
        // Insert into donated
        $insert_sql = "INSERT INTO donated (donor_admission_no, requester_admission_no, donation_date)
                       VALUES ('$donor_admission_no', '$admission_no', NOW())";
        $conn->query($insert_sql);

        // Insert into records
        $insert_record = "INSERT INTO records (donor_admission_no, requester_admission_no, donated_date)
                          VALUES ('$donor_admission_no', '$admission_no', NOW())";
        $conn->query($insert_record);

        $message = "<div class='alert success'>🩸 Donation confirmed and recorded successfully!</div>";
    } else {
        $message = "<div class='alert error'>❌ Invalid donor for this request.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blood Request</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: #fafafa;
        }

        .navbar {
            background-color: darkred;
            padding: 15px 40px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 { margin: 0; }
        .nav-links { display: flex; gap: 20px; }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        .nav-links a:hover { color: #ffcccc; }

        .container {
            padding: 30px;
            max-width: 1000px;
            margin: auto;
        }

        /* Alerts */
        .alert {
            padding: 12px 18px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
        }
        .alert.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert.info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        /* Tables */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background: #f2f2f2;
            color: darkred;
        }
        tr:hover { background: #f9f9f9; }

        /* Buttons */
        button {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover { opacity: 0.9; }
        .confirm-btn { background: #0275d8; color: white; }

        /* Cancel link styled like button */
        .cancel-link {
            display: inline-block;
            background: #ff4d4d;
            color: white !important;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .cancel-link:hover {
            background: #cc0000;
        }

        /* Submit button */
        .submit-btn {
            background: darkred;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            font-size: 15px;
            transition: background 0.3s ease, transform 0.2s ease;
        }
        .submit-btn:hover {
            background: #a30000;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
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
        <?php if (isset($message)) echo $message; ?>

        <h2>🩸 Make a Blood Request</h2>
        <form method="POST" action="">
            <label>Blood Group:</label>
            <select name="blood_group" required>
                <option value="">--Select--</option>
                <option>A+</option><option>A-</option>
                <option>B+</option><option>B-</option>
                <option>O+</option><option>O-</option>
                <option>AB+</option><option>AB-</option>
            </select><br><br>

            <label>Location:</label>
            <select name="location" required>
                <option value="">--Select District--</option>
                <option>Thiruvananthapuram</option><option>Kollam</option>
                <option>Pathanamthitta</option><option>Alappuzha</option>
                <option>Kottayam</option><option>Idukki</option>
                <option>Ernakulam</option><option>Thrissur</option>
                <option>Palakkad</option><option>Malappuram</option>
                <option>Kozhikode</option><option>Wayanad</option>
                <option>Kannur</option><option>Kasaragod</option>
            </select><br><br>

            <label>Reason:</label>
            <select name="reason" required>
                <option value="">--Select Reason--</option>
                <option value="Accident">Accident</option>
                <option value="Surgery">Surgery</option>
                <option value="Health Issue">Health Issue</option>
                <option value="Blood Donation Camp">Blood Donation Camp</option>
                <option value="Other">Other</option>
            </select><br><br>

            <button type="submit" name="make_request" class="submit-btn">🚀 Submit Request</button>
        </form>

        <hr>
        <h2>📋 My Requests</h2>
        <table>
            <tr>
                <th>Request ID</th>
                <th>Blood Group</th>
                <th>Location</th>
                <th>Reason</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT * FROM requests WHERE requester_admission_no='$admission_no'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['request_id']}</td>
                    <td>{$row['blood_group']}</td>
                    <td>{$row['location']}</td>
                    <td>{$row['reason']}</td>
                    <td>{$row['request_date']}</td>
                    <td><a class='cancel-link' href='request.php?cancel_id={$row['request_id']}'
                           onclick=\"return confirm('Are you sure you want to cancel this request?');\">Cancel</a></td>
                </tr>";
            }
            ?>
        </table>

        <hr>
        <h2>🤝 Accepted Donors</h2>
        <?php
        $sql = "SELECT r.request_id, r.blood_group, a.accept_date, a.status,
                       u.admission_no AS donor_admission_no,
                       u.name, u.age, u.semester, u.dept, u.blood_group AS donor_blood,
                       u.contact1, u.contact2, u.location
                FROM requests r
                JOIN accepts a ON r.request_id = a.request_id
                JOIN users u ON a.donor_admission_no = u.admission_no
                WHERE r.requester_admission_no = '$admission_no' and a.status = 'accepted'
                ORDER BY r.request_id, a.accept_date";

        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $current_request = null;
            while ($row = $result->fetch_assoc()) {
                if ($current_request !== $row['request_id']) {
                    if ($current_request !== null) echo "</table><br>";
                    $current_request = $row['request_id'];
                    echo "<h3>Request #{$row['request_id']} ({$row['blood_group']})</h3>";
                    echo "<table>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Semester</th>
                                <th>Dept</th>
                                <th>Blood Group</th>
                                <th>Contact 1</th>
                                <th>Contact 2</th>
                                <th>Location</th>
                                <th>Accepted On</th>
                                <th>Confirm Donation</th>
                            </tr>";
                }
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['age']}</td>
                        <td>{$row['semester']}</td>
                        <td>{$row['dept']}</td>
                        <td>{$row['donor_blood']}</td>
                        <td>{$row['contact1']}</td>
                        <td>{$row['contact2']}</td>
                        <td>{$row['location']}</td>
                        <td>{$row['accept_date']}</td>
                        <td>";
                
                if ($row['status'] == 'accepted') {
                    echo "<form method='post' style='display:inline;' 
                               onsubmit=\"return confirm('Are you sure you want to confirm this donation?');\">
                            <input type='hidden' name='donor_admission_no' value='{$row['donor_admission_no']}'>
                            <button type='submit' name='donate' class='confirm-btn'>Confirm</button>
                          </form>";
                } else {
                    echo "---";
                }

                echo "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No donors have accepted your requests yet.</p>";
        }
        ?>
    </div>
</body>
</html>
