<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION["role"] !== "Student") {
    header("Location: /dept/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "Gradiefy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Updated query with case-insensitive JOIN
$query = "
    SELECT es.title, es.date, es.description, f.Name AS faculty_name
    FROM exam_schedule es
    JOIN faculty f ON LOWER(es.faculty_name) = LOWER(f.Name)
    ORDER BY es.date ASC
";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Events</title>
    <link rel="stylesheet" href="/dept/CSS/StudentExamNotice.css">
    <style>
        body {
            background: url('/dept/img/Designer.png') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            margin: 50px auto;
            border-radius: 12px;
            max-width: 900px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #3a3a3a;
            color: white;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
<div class ="page-wrapper">
<header class="header">
    <div class="header-left">CSE(AI) Department</div>
    <div class="header-center">
        <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo" class="logo" />
    </div>
    <div class="header-right">
        <a href="/dept/Home.php">Home</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </div>
</header>

<div class="container">
    <h2>📚 Upcoming Test Schedule</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Description</th>
                <th>Faculty</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['faculty_name']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No upcoming exams found.</p>
    <?php endif; ?>
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
</div>
</body>
</html>
