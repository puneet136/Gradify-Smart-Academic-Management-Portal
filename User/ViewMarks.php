<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Student") {
    header("Location:/dept/login.php");
    exit();
}

$studentName = $_SESSION["username"];

$conn = new mysqli("localhost", "root", "", "Gradiefy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Updated SQL query to include marks_type
$stmt = $conn->prepare("SELECT subject, marks_obtained, total_marks, faculty_name, marks_type FROM midterm_marks WHERE LOWER(student_name) = LOWER(?)");
$stmt->bind_param("s", $studentName);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Mid-Term Marks</title>
    <link rel="stylesheet" href="/dept/CSS/ViewMarks.css">
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

    <h2>📋 Mid-Term Marks for <?php echo htmlspecialchars($studentName); ?></h2>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Subject</th>
                <th>Marks Type</th> <!-- New column added -->
                <th>Marks Obtained</th>
                <th>Total Marks</th>
                <th>Faculty</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= htmlspecialchars($row['marks_type']) ?></td> <!-- New column added -->
                    <td><?= htmlspecialchars($row['marks_obtained']) ?></td>
                    <td><?= htmlspecialchars($row['total_marks']) ?></td>
                    <td><?= htmlspecialchars($row['faculty_name']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No mid-term marks available.</p>
    <?php endif; ?>
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