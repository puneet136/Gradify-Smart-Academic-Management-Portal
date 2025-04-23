<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}

include '../db.php';

// Get posted filter values
$session = $_POST['session'];
$branch = $_POST['branch'];
$semester = $_POST['semester'];
$section = $_POST['section'];
$subject = $_POST['subject'];
$attendance_date = $_POST['attendance_date']; // Important!


$faculty_name = $_SESSION["username"];
$check = $conn->prepare("SELECT * FROM attendance WHERE date = ? AND subject = ? AND session = ? AND semester = ? AND section = ? AND faculty_name = ?");
$check->bind_param("ssssss", $attendance_date, $subject, $session, $semester, $section, $faculty_name);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "<h3 style='color:red;text-align:center;'>Attendance already marked for $attendance_date.</h3>";
    exit();
}

// Fetch students to mark attendance
$query = $conn->prepare("SELECT * FROM user WHERE session = ? AND branch = ? AND semester = ? AND section = ?");
$query->bind_param("ssss", $session, $branch, $semester, $section);
$query->execute();
$students = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance</title>
    <!-- <link rel="stylesheet" href="faculty_attendance.css"> -->
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
margin: 50px auto;
padding: 20px;
width: 80%;
border-radius: 15px;
box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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
.header {
    text-align: center;
    padding: 10px 0;
    background-color: black; /* or any color you prefer */
    border-bottom: 2px solid #ccc;
}

.center-logo {
    height: 100px;
    object-fit: contain;
}

</style>
<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="/dept/home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>
    <div class="container">
        <h2>Mark Attendance for <?php echo $attendance_date; ?></h2>
        <form action="submit_attendance.php" method="POST">
            <input type="hidden" name="session" value="<?php echo $session; ?>">
            <input type="hidden" name="branch" value="<?php echo $branch; ?>">
            <input type="hidden" name="semester" value="<?php echo $semester; ?>">
            <input type="hidden" name="section" value="<?php echo $section; ?>">
            <input type="hidden" name="subject" value="<?php echo $subject; ?>">
            <input type="hidden" name="attendance_date" value="<?php echo $attendance_date; ?>">

            <table>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['rollno']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td>
    <input type="checkbox" name="status[<?php echo $row['rollno']; ?>]" value="Present" checked>
    Present
</td>

                    </tr>
                <?php endwhile; ?>
            </table>

            <button type="submit">Submit Attendance</button>
        </form>
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
