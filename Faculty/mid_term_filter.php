<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "Gradiefy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$facultyName = $_SESSION['username'];

// Fetch sessions
$sessions = [];
$sessionRes = $conn->query("SELECT DISTINCT session FROM user ORDER BY session ASC");
while ($row = $sessionRes->fetch_assoc()) {
    $sessions[] = $row['session'];  
}

// Fetch sections
$sections = [];
$sectionRes = $conn->query("SELECT DISTINCT section FROM user ORDER BY section ASC");
while ($row = $sectionRes->fetch_assoc()) {
    $sections[] = $row['section'];
}

// Fetch semesters
$semesters = [];
$semesterRes = $conn->query("SELECT DISTINCT semester FROM user ORDER BY semester ASC");
while ($row = $semesterRes->fetch_assoc()) {
    $semesters[] = $row['semester'];
}

// Branches for this faculty
$branches = [];
$branchStmt = $conn->prepare("SELECT DISTINCT branch FROM course WHERE faculty_name = ?");
$branchStmt->bind_param("s", $facultyName);
$branchStmt->execute();
$branchRes = $branchStmt->get_result();
while ($row = $branchRes->fetch_assoc()) {
    $branches[] = $row['branch'];
}
$branchStmt->close();

// Subjects for this faculty
$subjects = [];
$subjectStmt = $conn->prepare("SELECT DISTINCT course_name FROM course WHERE faculty_name = ?");
$subjectStmt->bind_param("s", $facultyName);
$subjectStmt->execute();
$subjectRes = $subjectStmt->get_result();
while ($row = $subjectRes->fetch_assoc()) {
    $subjects[] = $row['course_name'];
}
$subjectStmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Filters</title>
    <link rel="stylesheet" href="/dept/CSS/mid_term_filter.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="/dept/Home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" style="color: black; padding: 8px 14px; border-radius: 4px;">Logout</a></li>
    </ul>
</nav>
<div class="logo-container">
    <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo" class="logo">
</div>

<div class="form-wrapper">
    <form method="POST" action="mid_term_marks.php">
        <label>Session:</label>
        <select name="session" required>
            <option value="">Select</option>
            <?php foreach ($sessions as $s): ?>
                <option value="<?= $s ?>"><?= $s ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Section:</label>
        <select name="section" required>
            <option value="">Select</option>
            <?php foreach ($sections as $sec): ?>
                <option value="<?= $sec ?>"><?= $sec ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Semester:</label>
        <select name="semester" required>
            <option value="">Select</option>
            <?php foreach ($semesters as $sem): ?>
                <option value="<?= $sem ?>"><?= $sem ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Branch:</label>
        <select name="branch" required>
            <option value="">Select</option>
            <?php foreach ($branches as $br): ?>
                <option value="<?= $br ?>"><?= $br ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Subject:</label>
        <select name="subject" required>
            <option value="">Select</option>
            <?php foreach ($subjects as $sub): ?>
                <option value="<?= $sub ?>"><?= $sub ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Marks Type:</label>
        <select name="marks_type" required>
            <option value="">Select</option>
            <option value="MTT-1">MTT-1</option>
            <option value="MTT-2">MTT-2</option>
            <option value="Internal Practical">Internal Practical</option>
        </select><br>

        <label>Total Marks:</label>
        <input type="number" name="total_marks" required><br>

        <input type="hidden" name="faculty_name" value="<?= htmlspecialchars($facultyName) ?>">

        <button type="submit">Proceed</button>
    </form>
    
</div>
<div style="text-align: center; margin: 20px;">
<a href="/dept/faculty/total_marks.php" class="View Total Marks" style="padding: 10px 20px; background-color:rgb(210, 13, 13); color: white; text-decoration: none; border-radius: 10px;">⬅ View Total Marks</a>
        <a href="/dept/faculty/faculty.php" class="back-button" style="padding: 10px 20px; background-color:rgb(199, 90, 90); color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
        
    </div>
    <footer class="footer">
    <div class="footer-left">
        <p>&copy; <?php echo date("Y"); ?> Computer Science and Engineering(Artificial Intelligence) Department</p>
    </div>
    <div class="footer-right">
        <p>Developed & Designed by Tushar Dhaker || Puneet Agrawal</p>
    </div>
</footer>
</body>
</html>
