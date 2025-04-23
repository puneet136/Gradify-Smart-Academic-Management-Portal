<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "HOD") {
    header("Location: /dept/login.php");
    exit();
}

require_once "../db.php";

$session = $_GET['session'];
$semester = $_GET['semester'];
$branch = $_GET['branch'];
$section = $_GET['section'];
$marks_type = $_GET['marks_type'];

$query = "SELECT subject, marks_obtained,student_name,rollno , total_marks
        FROM midterm_marks 
        WHERE session = ? AND semester = ? AND branch = ? AND section = ? AND marks_type = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssss", $session, $semester, $branch, $section, $marks_type);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submitted Marks</title>
    <link rel="stylesheet" href="/dept/CSS/marks_submitted_view.css">
</head>
<body>

<nav class="navbar">
        <div class="logo">CSE(AI) Department</div>
        <ul class="nav-links">
            <li><a href="/dept/Home.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>

<div class="container">
    <div class="header">
        <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo">
        <h1>Submitted Marks for <?= htmlspecialchars($marks_type) ?></h1>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <!-- <th>Student ID</th> -->
                    <th>Name</th>
                    <th>Roll No.</th>
                    <th>Subject</th>
                    <th>Marks</th>
                    <th>Total Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- <td><?= htmlspecialchars($row['']) ?></td> -->
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['rollno']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['marks_obtained']) ?></td>
                        <td><?= htmlspecialchars($row['total_marks']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No marks submitted for the selected filters.</p>
    <?php endif; ?>
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
