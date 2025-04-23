<?php
session_start();
if (!isset($_SESSION["loggedin"]) || !in_array($_SESSION["role"], ["Faculty", "Student", "HOD"])) {
    header("Location: /dept/login.php");
    exit();
}
include "../db.php";

// Fetch distinct values for dropdowns from user table
$semesters = mysqli_query($conn, "SELECT DISTINCT semester FROM user ORDER BY semester");
$branches = mysqli_query($conn, "SELECT DISTINCT branch FROM user ORDER BY branch");
$sections = mysqli_query($conn, "SELECT DISTINCT section FROM user ORDER BY section");

$timetableImage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $semester = $_POST['semester'];
    $branch = $_POST['branch'];
    $section = $_POST['section'];

    // Hardcoded match for 4th semester CSAI B section
    if ($semester == "4th" && $branch == "CSAI" && $section == "B") {
        $timetableImage = "/dept/img/WhatsApp Image 2025-04-17 at 22.39.28_dbe37e60.jpg"; }
    else if ($semester == "4th" && $branch == "CSAI" && $section == "A") {
        $timetableImage = "/dept/img/4thSem_sectionA_CSAI.jpg";
    } else {
        $timetableImage = "not_found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Timetable</title>
    <link rel="stylesheet" href="/dept/CSS/Faculty.css">
</head>
<body>
<nav class="navbar">
        <div class = "logo "> CSE(AI) Department</div>
        <ul class="nav-links">
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>
    <div class="container">
        <h2>📅 Filter Timetable</h2>
        <form method="POST" class="filter-form">
            <label for="semester">Semester:</label>
            <select name="semester" id="semester" required>
                <option value="">Select Semester</option>
                <?php while($row = mysqli_fetch_assoc($semesters)) { ?>
                    <option value="<?= $row['semester'] ?>"><?= $row['semester'] ?></option>
                <?php } ?>
            </select>

            <label for="branch">Branch:</label>
            <select name="branch" id="branch" required>
                <option value="">Select Branch</option>
                <?php while($row = mysqli_fetch_assoc($branches)) { ?>
                    <option value="<?= $row['branch'] ?>"><?= $row['branch'] ?></option>
                <?php } ?>
            </select>

            <label for="section">Section:</label>
            <select name="section" id="section" required>
                <option value="">Select Section</option>
                <?php while($row = mysqli_fetch_assoc($sections)) { ?>
                    <option value="<?= $row['section'] ?>"><?= $row['section'] ?></option>
                <?php } ?>
            </select>

            <button type="submit">Search</button>
        </form>

        <?php if ($timetableImage && $timetableImage !== "not_found"): ?>
            <div class="result">
                <p>🗓 Timetable for Semester <?= htmlspecialchars($semester) ?> - <?= htmlspecialchars($branch) ?> - Section <?= htmlspecialchars($section) ?>:</p>
                <img src="<?= $timetableImage ?>" alt="Timetable" style="max-width:90%; margin-top:15px;">
            </div>
        <?php elseif ($timetableImage === "not_found"): ?>
            <p style="color:red;">❌ No timetable found for the selected combination.</p>
        <?php endif; ?>
    </div>
    <div style="text-align: center; margin: 20px;">
    <!-- <a href="/dept/user/user.php" class="back-button" style="padding: 10px 20px; background-color:rgb(12, 12, 12); color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a> -->
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