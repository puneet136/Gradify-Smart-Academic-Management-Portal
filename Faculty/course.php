<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}

$facultyName = $_SESSION['username']; // Must match `faculty.php`

// Connect to database
$conn = new mysqli("localhost", "root", "", "gradiefy");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch courses related to logged-in faculty
$sql = "SELECT * FROM course WHERE faculty_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $facultyName);
$stmt->execute();
$result = $stmt->get_result();
?>
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

<!DOCTYPE html>
<html>
<head>
    <title>Ongoing Courses</title>
    <link rel="stylesheet" type="text/css" href="/dept/CSS/course.css">
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($facultyName); ?>!</h1>
<h2>Your Assigned Subjects</h2>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='Subject'>"."<br><br>";
        echo "<strong>Subject :</strong> " . htmlspecialchars($row["course_name"]) . "<br>";
        // echo "<strong>Semester:</strong> " . htmlspecialchars($row["semester"]) . "<br>";
        // echo "<strong>Branch:</strong> " . htmlspecialchars($row["branch"]) . "<br>";
        echo "</div>";
    }
} else {
    echo "<p>No subject assigned to you yet.</p>";
}
$conn->close();
?>
 <div style="text-align: center; margin: 20px;">
        <a href="/dept/faculty/faculty.php" class="back-button" style="padding: 10px 20px; background-color:rgb(200, 86, 86); color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
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
