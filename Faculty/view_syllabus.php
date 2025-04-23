<?php
session_start();
if (!isset($_SESSION["loggedin"]) || !in_array($_SESSION["role"], ["Faculty", "HOD"])) {
    header("Location: /dept/login.php");
    exit();
}

include("../db.php");

// Fetch distinct semester from user table for dropdown
$semesters = [];
$semesterQuery = "SELECT DISTINCT semester FROM user ORDER BY semester";
$semesterResult = mysqli_query($conn, $semesterQuery);

while ($row = mysqli_fetch_assoc($semesterResult)) {
    $semesters[] = $row['semester'];
}

// Hardcoded syllabus links based on semester
$syllabusLinks = [
    '6th' => 'https://rtu.ac.in/index/Adminpanel/Images/Media/Syllabus%203rd%20Year%20CSE(AI)%20V%20&%20VI%20Sem..pdf',
    '4th' => 'https://rtu.ac.in/home/wp-content/uploads/2018/11/Syllabus-CS.pdf'
];

$link = null;
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedSemester = $_POST['semester'];

    if (isset($syllabusLinks[$selectedSemester])) {
        $link = $syllabusLinks[$selectedSemester];
    } else {
        $message = "No syllabus link available for Semester: $selectedSemester.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Syllabus</title>
    <link rel="stylesheet" href="/dept/CSS/Faculty.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="/dept/home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<div class="logo-container">
    <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo" class="logo">
</div>

<div class="main-content">
    <h2>Select Semester</h2>
    <form method="POST" action="view_syllabus.php">
        <label for="semester">Semester:</label>
        <select name="semester" id="semester" required>
            <option value="" disabled selected>Select Semester</option>
            <?php foreach ($semesters as $sem) { ?>
                <option value="<?php echo $sem; ?>" <?php if (isset($selectedSemester) && $selectedSemester === $sem) echo 'selected'; ?>>
                    <?php echo $sem; ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit">Filter</button>
    </form>

    <?php if ($link): ?>
        <div class="syllabus-link">
            <h3>Syllabus Link:</h3>
            <a href="<?php echo $link; ?>" target="_blank"><?php echo $link; ?></a>
        </div>
    <?php elseif (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</div>
<footer class="footer">
    <div class="footer-left">
        <p>&copy; <?php echo date("Y"); ?> Computer Science and Artificial Intelligence Department</p>
    </div>
    <div class="footer-right">
        <p>Developed & Designed by Tushar Dhaker || Puneet Agrawal</p>
    </div>
</footer>
</body>
</html>
