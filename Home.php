<?php
// Start session if needed
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSE(AI) Department</title>
    <link rel="stylesheet" href="/dept/CSS/Home.css">
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

    <header class="header">
        <img src="img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="CSE(AI)-Logo" class="logo-img">
        <h1>Welcome to Computer Science And Engineering(Artificial Intelligence) Department</h1>
        <p>A comprehensive platform for students, faculty, and HOD to manage academic performance</p>
    </header>

    <section class="login-section">
        <div class="login-card">
            <span class="icon">🎓</span>
            <h2>Student Login</h2>
            <p>Access your course materials, assignments, and notices</p>
            <a href="/dept/login.php?role=Student"><button class="login-btn">Login</button></a>
        </div>
        <div class="login-card">
            <span class="icon">👤</span>
            <h2>Faculty Login</h2>
            <p>Manage courses, upload materials, and send notices</p>
            <a href="/dept/login.php?role=Faculty"><button class="login-btn">Login</button></a>
        </div>
        <div class="login-card">
            <span class="icon">⚙️</span>
            <h2>HOD Login</h2>
            <p>Department administration and faculty management</p>
            <a href="/dept/login.php?role=HOD"><button class="login-btn">Login</button></a>
        </div>
    </section>

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
