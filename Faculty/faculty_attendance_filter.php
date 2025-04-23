<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}
include '../db.php';
$faculty = $_SESSION["username"];

// Fetch distinct dropdown values
$sessions = $conn->query("SELECT DISTINCT session FROM user");
$branches = $conn->query("SELECT DISTINCT branch FROM user");
$semesters = $conn->query("SELECT DISTINCT semester FROM user");
$sections = $conn->query("SELECT DISTINCT section FROM user");

// Assigned subjects to faculty
$subjects = $conn->prepare("SELECT DISTINCT course_name FROM course WHERE faculty_name = ?");
$subjects->bind_param("s", $faculty);
$subjects->execute();
$subject_result = $subjects->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Attendance Filter</title>
    <link rel="stylesheet" href="/dept/css/faculty_attendance.css">
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
align-items: center;
padding: 15px 30px;
background: rgba(255, 255, 255, 0.2);
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(10px);
}

.navbar .logo {
font-size: 20px;
font-weight: bold;
color: #000; /* Change logo text color to black */
}

.nav-links {
list-style: none;
padding: 0;
margin: 0;
display: flex;
}

.nav-links li {
margin-left: 20px;
}

.nav-links a {
text-decoration: none;
color: #000; /* Change navigation links to black */
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
.container label {
    font-weight: bold;
    margin-bottom: 5px;
}

.container select,
.container input[type="date"] {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}


h2 {
margin-bottom: 20px;
color: #000;
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
text-align: center;
background: rgba(255,255,255,0.95);
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
align-items: center;
flex-wrap: wrap;
font-size: 15px;
font-weight: bold;
position: relative;
text-align: left;
border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.footer-left,
.footer-right {
flex: 1;
}

.footer-right {
text-align: right;
}

/* Optional: improve for mobile */
@media (max-width: 600px) {
.footer {
flex-direction: column;
align-items: flex-start;
text-align: left;
}

.footer-right {
text-align: left;
margin-top: 8px;
}
}
</style>
<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="/dept/home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <h2>Student Attendance Filter</h2>
    <form action="mark_attendance.php" method="POST">
        <label>Session:</label>
        <select name="session" required>
            <option value="">Select Session</option>
            <?php while($row = $sessions->fetch_assoc()) { ?>
                <option value="<?= $row['session'] ?>"><?= $row['session'] ?></option>
            <?php } ?>
        </select>

        <label>Branch:</label>
        <select name="branch" required>
            <option value="">Select Branch</option>
            <?php while($row = $branches->fetch_assoc()) { ?>
                <option value="<?= $row['branch'] ?>"><?= $row['branch'] ?></option>
            <?php } ?>
        </select>

        <label>Semester:</label>
        <select name="semester" required>
            <option value="">Select Semester</option>
            <?php while($row = $semesters->fetch_assoc()) { ?>
                <option value="<?= $row['semester'] ?>"><?= $row['semester'] ?></option>
            <?php } ?>
        </select>

        <label>Section:</label>
        <select name="section" required>
            <option value="">Select Section</option>
            <?php while($row = $sections->fetch_assoc()) { ?>
                <option value="<?= $row['section'] ?>"><?= $row['section'] ?></option>
            <?php } ?>
        </select>

        <label>Subject:</label>
        <select name="subject" required>
            <option value="">Select Subject</option>
            <?php while($row = $subject_result->fetch_assoc()) { ?>
                <option value="<?= $row['course_name'] ?>"><?= $row['course_name'] ?></option>
            <?php } ?>
        </select>

        <label>Date:</label>
        <input type="date" name="attendance_date" max="<?= date('Y-m-d') ?>" required>

        <button type="submit">Proceed</button>
    </form>
</div>
<div style="text-align: center; margin: 20px;">
        <a href="/dept/faculty/faculty.php" class="back-button" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
    </div>
<footer class="footer">
    <div class="footer-left">
        <p>&copy; <?= date("Y") ?> CSE(AI) Department</p>
    </div>
    <div class="footer-right">
        <p>Developed by Tushar Dhaker & Puneet Agrawal</p>
    </div>
</footer>
</body>
</html>
