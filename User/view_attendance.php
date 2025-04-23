<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../db.php'; // Database connection

// Check if the student is logged in and has the 'Student' role
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Student') {
    header('Location: /dept/login.php');
    exit();
}

// ✅ Get rollno from session
$rollno = isset($_SESSION['rollno']) ? $_SESSION['rollno'] : '';

if (empty($rollno)) {
    die("Roll number not found in session.");
}

// Fetch attendance for the logged-in student
$sql = "SELECT * FROM attendance WHERE rollno = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $rollno);
$stmt->execute();
$result = $stmt->get_result();

// Get attendance summary by subject (for percentage)
$summarySql = "
    SELECT subject,
        COUNT(*) AS total_classes,
        SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) AS present_count
    FROM attendance
    WHERE rollno = ?
    GROUP BY subject
";
$summaryStmt = $conn->prepare($summarySql);
$summaryStmt->bind_param("s", $rollno);
$summaryStmt->execute();
$summaryResult = $summaryStmt->get_result();

$subjectSummary = [];
while ($row = $summaryResult->fetch_assoc()) {
    $percentage = ($row['total_classes'] > 0)
        ? round(($row['present_count'] / $row['total_classes']) * 100, 2)
        : 0;
    $subjectSummary[$row['subject']] = $percentage;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Attendance</title>
    <link rel="stylesheet" href="/dept/css/student_attendance.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<div class="attendance-container">
    <h3>Your Attendance</h3>

    <h4>📊 Attendance Percentage by Subject</h4>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Attendance %</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($subjectSummary)) {
                foreach ($subjectSummary as $subject => $percentage) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($subject) . "</td>";
                    echo "<td>" . $percentage . "%</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No attendance summary available.</td></tr>";
            } ?>
        </tbody>
    </table>

    <br><br>

    <h4>📅 Attendance Records</h4>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No attendance records found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div style="text-align: center; margin: 20px;">
    <a href="/dept/user/user.php" class="back-button" style="padding: 10px 20px; background-color:rgb(228, 106, 106); color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
</div>

<footer class="footer">
    <div class="footer-left">
        <p>&copy; <?php echo date("Y"); ?> Computer Science and Engineering (Artificial Intelligence) Department</p>
    </div>
    <div class="footer-right">
        <p>Developed & Designed by Tushar Dhaker || Puneet Agrawal</p>
    </div>
</footer>
</body>
</html>