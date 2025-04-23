<?php
session_start();
include '../db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'HOD') {
    header('Location: /dept/login.php');
    exit();
}

// Fetch distinct filter options
$sessionResult = $conn->query("SELECT DISTINCT session FROM user");
$semesterResult = $conn->query("SELECT DISTINCT semester FROM user");
$sectionResult = $conn->query("SELECT DISTINCT section FROM user");
$subjectResult = $conn->query("SELECT DISTINCT subject FROM attendance");

// Filters
$session = $_POST['session'] ?? '';
$semester = $_POST['semester'] ?? '';
$section = $_POST['section'] ?? '';
$subject = $_POST['subject'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$below75 = isset($_POST['below_75']); // Checkbox logic

// Build filter
$attendanceFilter = "WHERE 1";
if ($session)  $attendanceFilter .= " AND rollno IN (SELECT rollno FROM user WHERE session = '$session')";
if ($semester) $attendanceFilter .= " AND rollno IN (SELECT rollno FROM user WHERE semester = '$semester')";
if ($section)  $attendanceFilter .= " AND rollno IN (SELECT rollno FROM user WHERE section = '$section')";
if ($subject)  $attendanceFilter .= " AND subject = '$subject'";
if ($start_date && $end_date) {
    $attendanceFilter .= " AND date BETWEEN '$start_date' AND '$end_date'";
} elseif ($start_date) {
    $attendanceFilter .= " AND date >= '$start_date'";
} elseif ($end_date) {
    $attendanceFilter .= " AND date <= '$end_date'";
}

$attendanceQuery = "SELECT rollno, subject, date, status FROM attendance $attendanceFilter ORDER BY rollno, subject, date";
$attendanceResult = $conn->query($attendanceQuery);

// Group data for percentage calculation
$records = [];
$percentages = [];

if ($attendanceResult && $attendanceResult->num_rows > 0) {
    while ($row = $attendanceResult->fetch_assoc()) {
        $rollno = $row['rollno'];
        $subj = $row['subject'];
        $key = $rollno . '_' . $subj;

        // Calculate percentage only once per student-subject combo
        if (!isset($percentages[$key])) {
            $totalQuery = "SELECT COUNT(*) AS total FROM attendance WHERE rollno = '$rollno' AND subject = '$subj'";
            $presentQuery = "SELECT COUNT(*) AS present FROM attendance WHERE rollno = '$rollno' AND subject = '$subj' AND status = 'Present'";
            $total = $conn->query($totalQuery)->fetch_assoc()['total'] ?? 0;
            $present = $conn->query($presentQuery)->fetch_assoc()['present'] ?? 0;
            $percentages[$key] = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        }

        // Apply below 75% filter
        if ($below75 && $percentages[$key] >= 75) {
            continue;
        }

        $records[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HOD View Attendance</title>
    <link rel="stylesheet" href="/dept/css/attendance.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="/dept/Home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<div class="filter-container">
    <form method="post" action="">
        <label for="session">Session:</label>
        <select name="session" id="session">
            <option value="">Select Session</option>
            <?php while ($row = $sessionResult->fetch_assoc()) { ?>
                <option value="<?= $row['session'] ?>" <?= ($session == $row['session']) ? 'selected' : '' ?>>
                    <?= $row['session'] ?>
                </option>
            <?php } ?>
        </select>

        <label for="semester">Semester:</label>
        <select name="semester" id="semester">
            <option value="">Select Semester</option>
            <?php while ($row = $semesterResult->fetch_assoc()) { ?>
                <option value="<?= $row['semester'] ?>" <?= ($semester == $row['semester']) ? 'selected' : '' ?>>
                    <?= $row['semester'] ?>
                </option>
            <?php } ?>
        </select>

        <label for="section">Section:</label>
        <select name="section" id="section">
            <option value="">Select Section</option>
            <?php while ($row = $sectionResult->fetch_assoc()) { ?>
                <option value="<?= $row['section'] ?>" <?= ($section == $row['section']) ? 'selected' : '' ?>>
                    <?= $row['section'] ?>
                </option>
            <?php } ?>
        </select>

        <label for="subject">Subject:</label>
        <select name="subject" id="subject">
            <option value="">Select Subject</option>
            <?php while ($row = $subjectResult->fetch_assoc()) { ?>
                <option value="<?= $row['subject'] ?>" <?= ($subject == $row['subject']) ? 'selected' : '' ?>>
                    <?= $row['subject'] ?>
                </option>
            <?php } ?>
        </select>

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">

        <label>
            <input type="checkbox" name="below_75" <?= $below75 ? 'checked' : '' ?>>
            Show only students with attendance below 75%
        </label>

        <button type="submit">Filter</button>
    </form>
</div>
<div style="text-align: center;">
    <form method="post" action="export_attendance_csv.php">
        <input type="hidden" name="session" value="<?= htmlspecialchars($session) ?>">
        <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>">
        <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
        <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
        <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
        <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
        <input type="hidden" name="below_75" value="<?= $below75 ? '1' : '0' ?>">
        <button type="submit" style="padding: 10px 200px; background-color:pink; color:white; border-radius:10px;">📄 Export CSV</button>
    </form>
</div>
<div class="attendance-container">
    <h3>Filtered Attendance Records</h3>
    <table>
        <thead>
        <tr>
            <th>Roll No</th>
            <th>Subject</th>
            <th>Date</th>
            <th>Status</th>
            <th>Attendance %</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($records)) {
            foreach ($records as $row) {
                $key = $row['rollno'] . '_' . $row['subject'];
                $percentage = $percentages[$key] ?? 0;
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['rollno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "<td>" . $percentage . "%</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<div style="text-align: center; margin: 20px;">
    <a href="/dept/hod/hod.php" class="back-button" style="padding: 10px 20px; background-color:rgb(228, 106, 106); color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
</div>



<footer class="footer">
    <div class="footer-left">
        <p>&copy; <?= date("Y") ?> Computer Science and Engineering(Artificial Intelligence) Department</p>
    </div>
    <div class="footer-right">
        <p>Developed & Designed by Tushar Dhaker || Puneet Agrawal</p>
    </div>
</footer>
</body>
</html>
