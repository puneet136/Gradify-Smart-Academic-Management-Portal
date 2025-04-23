<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}

require_once "../db.php";

$facultyName = $_SESSION["username"];

// Get subject assigned to faculty from `course` table
$subjectQuery = "SELECT course_name FROM course WHERE faculty_name = ?";
$stmt = $conn->prepare($subjectQuery);
$stmt->bind_param("s", $facultyName);
$stmt->execute();
$stmt->bind_result($subject);
$stmt->fetch();
$stmt->close();

// Fetch marks from `midterm_marks`, join with `user`, and calculate average of MTT-1 & MTT-2
$marksQuery = "
    SELECT 
        u.rollno,
        u.name AS student_name,
        MAX(CASE WHEN m.marks_type = 'MTT-1' THEN m.marks_obtained END) AS mtt1,
        MAX(CASE WHEN m.marks_type = 'MTT-2' THEN m.marks_obtained END) AS mtt2,
        CASE
            WHEN 
                MAX(CASE WHEN m.marks_type = 'MTT-1' THEN m.marks_obtained END) IS NOT NULL AND
                MAX(CASE WHEN m.marks_type = 'MTT-2' THEN m.marks_obtained END) IS NOT NULL
            THEN 
                ROUND((
                    MAX(CASE WHEN m.marks_type = 'MTT-1' THEN m.marks_obtained END) +
                    MAX(CASE WHEN m.marks_type = 'MTT-2' THEN m.marks_obtained END)
                ) / 2, 2)
            ELSE 
                COALESCE(
                    MAX(CASE WHEN m.marks_type = 'MTT-1' THEN m.marks_obtained END),
                    MAX(CASE WHEN m.marks_type = 'MTT-2' THEN m.marks_obtained END)
                )
        END AS average
    FROM midterm_marks m
    JOIN user u ON u.rollno = m.rollno
    WHERE m.subject = ?
    GROUP BY u.rollno, u.name
";


$stmt = $conn->prepare($marksQuery);
$stmt->bind_param("s", $subject);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<nav class="navbar">
    <div class="logo">CSAI Department</div>
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<div class="logo-container">
    <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo" class="logo">
</div>
    <meta charset="UTF-8">
    <title>Total Marks</title>
    <link rel="stylesheet" href="/dept/CSS/total_marks.css">
</head>
<body>

<div class="container">
    <h2>Total Marks for Subject: <?php echo htmlspecialchars($subject); ?></h2>

    <table>
        <thead>
            <tr>
                <th>Roll Number</th>
                <th>Student Name</th>
                <th>MTT-1</th>
                <th>MTT-2</th>
                <th>Average</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['rollno']); ?></td>
                    <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                    <td><?php echo $row['mtt1'] ?? 'N/A'; ?></td>
                    <td><?php echo $row['mtt2'] ?? 'N/A'; ?></td>
                    <td><?php echo $row['average']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<div style="text-align: center; margin: 20px;">
    <a href="/dept/faculty/faculty.php" class="back-button" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
</div>
<footer class="footer">
        <p>&copy; <?php echo date("Y"); ?> Computer Science and Artificial Intelligence Department</p>
    </footer>

</body>
</html>
