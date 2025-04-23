<?php
session_start();
if (!isset($_SESSION["loggedin"])) {
    header("Location: /dept/login.php");
    exit();
}

include("../db.php");

$role = $_SESSION["role"];
$userId = $_SESSION["username"];
$link = null;
$message = "";
$selectedSemester = "";

// Syllabus links mapped by semester
$syllabusLinks = [
    '6th' => 'https://rtu.ac.in/index/Adminpanel/Images/Media/Syllabus%203rd%20Year%20CSE(AI)%20V%20&%20VI%20Sem..pdf',
    '4th' => 'https://rtu.ac.in/home/wp-content/uploads/2018/11/Syllabus-CS.pdf'
];

// Only allow access if the role is 'Student'
if ($role !== "Student") {
    // Redirect non-student users to the login page or another page
    header("Location: /dept/login.php");
    exit();
}

// Fetch the student's semester(s)
$studentSemester = "";
$studentQuery = "SELECT semester FROM user WHERE id = '$userId' LIMIT 1";
$studentResult = mysqli_query($conn, $studentQuery);
if ($row = mysqli_fetch_assoc($studentResult)) {
    $studentSemester = $row['semester']; // Store the student's current semester
} else {
    $message = "Student semester not found.";
}

// Form submission handler
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the selected semester from the form
    $selectedSemester = $_POST['semester'];

    // Check if syllabus link exists for the selected semester
    if (isset($syllabusLinks[$selectedSemester])) {
        $link = $syllabusLinks[$selectedSemester];
    } else {
        $message = "No syllabus link available for your selected semester.";
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
    <div class="logo">CSAI Department</div>
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
    <h2>View Syllabus</h2>

    <?php if ($role === "Student") { ?>
        <!-- Form to select semester -->
        <form method="POST" action="view_syllabus_student.php">
            <label for="semester">Select Your Semester:</label>
            <select name="semester" id="semester" required>
                <option value="" disabled selected>Select Semester</option>
                <option value="6th" <?php if ($studentSemester === '6th') echo 'selected'; ?>>6th</option>
                <option value="4th" <?php if ($studentSemester === '4th') echo 'selected'; ?>>4th</option>
                <!-- Add other semesters as needed -->
            </select>
            <button type="submit">View</button>
        </form>

        <?php if ($selectedSemester) { ?>
            <p><strong>Your Selected Semester:</strong> <?php echo htmlspecialchars($selectedSemester); ?></p>

            <?php if ($link): ?>
                <div class="syllabus-link">
                    <h3>Syllabus Link:</h3>
                    <a href="<?php echo $link; ?>" target="_blank"><?php echo $link; ?></a>
                </div>
            <?php else: ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
        <?php } ?>
    <?php } ?>

</div>
<div style="text-align: center; margin: 20px;">
        <a href="/dept/user/user.php" class="back-button" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
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