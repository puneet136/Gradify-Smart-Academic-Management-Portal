<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "HOD") {
    header("Location: /dept/login.php");
    exit();
}

require_once "../db.php"; // Update based on your path

// Fetch distinct values for dropdowns
$sessions = mysqli_query($conn, "SELECT DISTINCT session FROM midterm_marks");
$semesters = mysqli_query($conn, "SELECT DISTINCT semester FROM midterm_marks");
$branches = mysqli_query($conn, "SELECT DISTINCT branch FROM midterm_marks");
$sections = mysqli_query($conn, "SELECT DISTINCT section FROM midterm_marks");
$marks_types = mysqli_query($conn, "SELECT DISTINCT marks_type FROM midterm_marks");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Filter Marks</title>
    <link rel="stylesheet" href="/dept/CSS/marks_submitted_filter.css">
</head>
<body>
<style>
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
        <li><a href="/dept/Home.php">Home</a></li>
        <li><a href="/dept/Home.php">About</a></li>
        <li><a href="/dept/Home.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<div class="container">
    <div class="header">
        <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo">
        <h1>Filter Submitted Marks</h1>
    </div>

    <form action="marks_submitted_view.php" method="GET" class="filter-form">
        <select name="session" required>
            <option value="">Select Session</option>
            <?php while ($row = mysqli_fetch_assoc($sessions)) echo "<option>{$row['session']}</option>"; ?>
        </select>

        <select name="semester" required>
            <option value="">Select Semester</option>
            <?php while ($row = mysqli_fetch_assoc($semesters)) echo "<option>{$row['semester']}</option>"; ?>
        </select>

        <select name="branch" required>
            <option value="">Select Branch</option>
            <?php while ($row = mysqli_fetch_assoc($branches)) echo "<option>{$row['branch']}</option>"; ?>
        </select>

        <select name="section" required>
            <option value="">Select Section</option>
            <?php while ($row = mysqli_fetch_assoc($sections)) echo "<option>{$row['section']}</option>"; ?>
        </select>

        <select name="marks_type" required>
            <option value="">Select Marks Type</option>
            <?php while ($row = mysqli_fetch_assoc($marks_types)) echo "<option>{$row['marks_type']}</option>"; ?>
        </select>

        <button type="submit">View Submitted Marks</button>
    </form>
</div>
<div style="text-align: center; margin: 20px;">
        <a href="/dept/hod/hod.php" class="back-button" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
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
