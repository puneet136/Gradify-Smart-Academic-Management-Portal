<?php
session_start();
if (!isset($_SESSION["loggedin"]) || !in_array($_SESSION["role"], ["Faculty", "HOD"])) {
    header("Location: /dept/login.php");
    exit();
}
include '../db.php';

$faculty = $_SESSION["username"];
$message = "";

$sessions = $conn->query("SELECT DISTINCT session FROM attendance WHERE faculty_name = '$faculty'");
$semesters = $conn->query("SELECT DISTINCT semester FROM attendance WHERE faculty_name = '$faculty'");
$sections = $conn->query("SELECT DISTINCT section FROM attendance WHERE faculty_name = '$faculty'");
$subjects = $conn->query("SELECT DISTINCT subject FROM attendance WHERE faculty_name = '$faculty'");

$attendanceData = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $session = $_POST["session"];
    $semester = $_POST["semester"];
    $section = $_POST["section"];
    $subject = $_POST["subject"];
    $attendance_date = $_POST["attendance_date"];

    $stmt = $conn->prepare("SELECT * FROM attendance WHERE session=? AND semester=? AND section=? AND subject=? AND date=? AND faculty_name=?");
    $stmt->bind_param("ssssss", $session, $semester, $section, $subject, $attendance_date, $faculty);
    $stmt->execute();
    $attendanceData = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Attendance</title>
</head>
<body>
<style>
    body {
    font-family: Arial, sans-serif;
    background: url('/dept/img/Designer.png') no-repeat center center fixed;
    background-size: cover;
    backdrop-filter: blur(5px);
    color: #000;
    margin: 0;
    padding: 0;
}

.navbar {
    display: flex;
    justify-content: space-between;
    padding: 15px 30px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.logo {
    font-size: 20px;
    font-weight: bold;
    color: #000;
}

.nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
}

.nav-links a {
    text-decoration: none;
    color: #000;
    font-weight: 500;
}

.container {
    background: rgba(255, 255, 255, 0.8);
    margin: 10px auto;
    padding: 30px;
    width: 90%;
    max-width: 500px;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form input, select, button {
    padding: 10px;
    margin: 10px 0;
    width: 100%;
    max-width: 400px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    border: 1px solid #aaa;
    padding: 8px;
    background: #fff;
    text-align: center;
}

button {
    background: #000;
    color: #fff;
    border: none;
    cursor: pointer;
}

button:hover {
    background: #333;
}

.footer {
    margin-top: 40px;
    padding: 20px 40px;
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
    box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.1);
    color: #000;
    display: flex;
    justify-content: space-between;
    font-size: 15px;
    font-weight: bold;
}
</style>

<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="/dept/home.php">Home</a></li>
        <li><a href="/dept/home.php">Contact</a></li>
        <li><a href="/dept/home.php">About</a></li>
        <li><a href="/dept/logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h2>View & Edit Attendance</h2>
    <form method="POST">
        <label>Session:</label>
        <select name="session" required>
            <option value="">Select Session</option>
            <?php while($row = $sessions->fetch_assoc()) { ?>
                <option value="<?= $row['session'] ?>" <?= isset($_POST['session']) && $_POST['session'] == $row['session'] ? 'selected' : '' ?>><?= $row['session'] ?></option>
            <?php } ?>
        </select>

        <label>Semester:</label>
        <select name="semester" required>
            <option value="">Select Semester</option>
            <?php while($row = $semesters->fetch_assoc()) { ?>
                <option value="<?= $row['semester'] ?>" <?= isset($_POST['semester']) && $_POST['semester'] == $row['semester'] ? 'selected' : '' ?>><?= $row['semester'] ?></option>
            <?php } ?>
        </select>

        <label>Section:</label>
        <select name="section" required>
            <option value="">Select Section</option>
            <?php while($row = $sections->fetch_assoc()) { ?>
                <option value="<?= $row['section'] ?>" <?= isset($_POST['section']) && $_POST['section'] == $row['section'] ? 'selected' : '' ?>><?= $row['section'] ?></option>
            <?php } ?>
        </select>

        <label>Subject:</label>
        <select name="subject" required>
            <option value="">Select Subject</option>
            <?php while($row = $subjects->fetch_assoc()) { ?>
                <option value="<?= $row['subject'] ?>" <?= isset($_POST['subject']) && $_POST['subject'] == $row['subject'] ? 'selected' : '' ?>><?= $row['subject'] ?></option>
            <?php } ?>
        </select>

        <label>Date:</label>
        <input type="date" name="attendance_date" value="<?= $_POST['attendance_date'] ?? '' ?>" max="<?= date('Y-m-d') ?>" required>

        <button type="submit">View Attendance</button>
    </form>

    <?php if ($attendanceData && $attendanceData->num_rows > 0): ?>
        <form action="edit_attendance.php" method="POST">
            <input type="hidden" name="session" value="<?= htmlspecialchars($_POST['session']) ?>">
            <input type="hidden" name="semester" value="<?= htmlspecialchars($_POST['semester']) ?>">
            <input type="hidden" name="section" value="<?= htmlspecialchars($_POST['section']) ?>">
            <input type="hidden" name="subject" value="<?= htmlspecialchars($_POST['subject']) ?>">
            <input type="hidden" name="attendance_date" value="<?= htmlspecialchars($_POST['attendance_date']) ?>">

            <table>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Attendance %</th>
                </tr>
                <?php
                    // Get total dates the class was held
                    $totalDaysStmt = $conn->prepare("SELECT COUNT(DISTINCT date) as total_days FROM attendance WHERE session=? AND semester=? AND section=? AND subject=? AND faculty_name=?");
                    $totalDaysStmt->bind_param("sssss", $session, $semester, $section, $subject, $faculty);
                    $totalDaysStmt->execute();
                    $totalDaysResult = $totalDaysStmt->get_result()->fetch_assoc();
                    $totalDays = $totalDaysResult['total_days'] ?? 0;
                ?>
                <?php while ($row = $attendanceData->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['rollno']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <select name="status[<?= $row['id'] ?>]">
                                <option value="Present" <?= $row['status'] === "Present" ? "selected" : "" ?>>Present</option>
                                <option value="Absent" <?= $row['status'] === "Absent" ? "selected" : "" ?>>Absent</option>
                            </select>
                        </td>
                        <td>
                            <?php
                                $rollno = $row['rollno'];
                                $presentQuery = $conn->prepare("SELECT COUNT(*) as present_days FROM attendance WHERE session=? AND semester=? AND section=? AND subject=? AND faculty_name=? AND rollno=? AND status='Present'");
                                $presentQuery->bind_param("ssssss", $session, $semester, $section, $subject, $faculty, $rollno);
                                $presentQuery->execute();
                                $presentResult = $presentQuery->get_result()->fetch_assoc();
                                $presentDays = $presentResult['present_days'] ?? 0;
                                $percentage = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100, 2) : 0;
                            ?>
                            <?= $percentage ?>%
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <button type="submit">Update Attendance</button>
        </form>

        <form action="export_attendance.php" method="POST" style="margin-top: 20px;">
            <input type="hidden" name="session" value="<?= htmlspecialchars($_POST['session']) ?>">
            <input type="hidden" name="semester" value="<?= htmlspecialchars($_POST['semester']) ?>">
            <input type="hidden" name="section" value="<?= htmlspecialchars($_POST['section']) ?>">
            <input type="hidden" name="subject" value="<?= htmlspecialchars($_POST['subject']) ?>">
            <input type="hidden" name="attendance_date" value="<?= htmlspecialchars($_POST['attendance_date']) ?>">
            <button type="submit">Export to CSV</button>
        </form>
    <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <p>No records found for the selected filters.</p>
    <?php endif; ?>
</div>

<div style="text-align: center; margin: 20px;">
    <a href="/dept/faculty/faculty.php" class="back-button" style="padding: 10px 20px; background-color:rgb(228, 106, 106); color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
</div>

<footer class="footer">
    <div class="footer-left">
        <p>&copy; <?= date("Y") ?> Computer Science And Engineering(Artificial Intelligence) Department</p>
    </div>
    <div class="footer-right">
        <p>Developed by Tushar Dhaker & Puneet Agrawal</p>
    </div>
</footer>

<?php if (isset($_SESSION['attendance_update_success'])): ?>
    <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
        <?= $_SESSION['attendance_update_success'] ?>
    </div>
    <?php unset($_SESSION['attendance_update_success']); ?>
<?php endif; ?>
</body>
</html>