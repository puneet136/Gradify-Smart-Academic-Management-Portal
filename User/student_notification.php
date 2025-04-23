<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Student") {
    header("Location:/dept/login.php");
    exit();
}

require_once "../db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Notifications</title>
    <link rel="stylesheet" href="/dept/CSS/User.css">
    <style>
        body {
            background: url('/dept/img/Designer.png') no-repeat center center/cover;
            color: black;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .notification-container {
            max-width: 900px;
            margin: 120px auto 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .header img {
            height: 80px;
        }

        .notification {
            padding: 15px;
            margin-bottom: 15px;
            border-left: 5px solid #3498db;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .notification h4 {
            margin: 0 0 5px;
            font-size: 18px;
            color: #333;
        }

        .notification p {
            margin: 0 0 8px;
            font-size: 15px;
        }

        .notification a {
            color: #0066cc;
            font-size: 14px;
            text-decoration: underline;
        }

        .notification a:hover {
            text-decoration: none;
        }

        .footer {
            padding: 10px;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            color: #000;
        }

        .back-button:hover {
            background-color: #003d80;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<!-- Main Content -->
<div class="notification-container">
    <div class="header">
        <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo">
        <h2>📢 Notifications from Faculty & HOD</h2>
    </div>

    <?php
    $query = "SELECT name, message, attachment, created_at FROM notification ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='notification'>";
            echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
            echo "<p>" . nl2br(htmlspecialchars($row['message'])) . "</p>";

            if (!empty($row['attachment'])) {
                $fileName = basename($row['attachment']);
                $filePath = $_SERVER['DOCUMENT_ROOT'] . "/dept/uploads/" . $fileName;
                $downloadLink = "/dept/uploads/" . rawurlencode($fileName);

                if (file_exists($filePath)) {
                    echo "<a href='$downloadLink' target='_blank'>📎 Download Attachment</a><br>";
                } else {
                    echo "<span style='color:red;'>⚠ Attachment not found.</span><br>";
                }
            }

            echo "<small><i>Posted on " . date("d M Y, h:i A", strtotime($row['created_at'])) . "</i></small>";
            echo "</div>";
        }
    } else {
        echo "<p>No notifications available.</p>";
    }
    ?>
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
