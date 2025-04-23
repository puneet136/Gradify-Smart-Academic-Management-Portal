<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}

$facultyName = $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> <!-- Font Awesome -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            background: url('/dept/img/Designer.png') no-repeat center center fixed;
            background-size: cover;
            backdrop-filter: blur(5px);
            color: #000; /* Set text color to black */
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #000; /* Change logo text color to black */
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #000; /* Change navigation links to black */
            font-weight: 500;
        }

        .header {
            margin-top: 30px;
        }

        .logo-img {
            width: 80px;
        }

        h1 {
            font-size: 26px;
            margin-top: 10px;
            color: #000; /* Ensure heading is black */
        }

        .login-section {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 250px;
            color: #000; /* Change login card text to black */
            backdrop-filter: blur(10px);
        }

        .icon {
            font-size: 30px;
        }

        .login-btn {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            border: none;
            padding: 10px 15px;
            margin-top: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-btn:hover {
            background: rgba(0, 0, 0, 1);
        }

        .footer {
            margin-top: 105px;
            padding: 15px 40px;
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

        .main-content {
            padding: 20px;
            color: #000;
        }

        .dashboard {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 220px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .card-btn {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }

        .card-btn:hover {
            background: rgba(0, 0, 0, 1);
        }

        .dashboard-btn {
            background-color:rgb(32, 32, 32);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .dashboard-btn:hover {
            background-color:rgb(0, 0, 0);
        }

    </style>
</head>
<body>

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

<div class="main-content">
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($facultyName); ?>!</h1>
    </header>

    <section class="dashboard">
        <div class="card">
            <h2><i class="fas fa-book-open"></i>My Courses</h2>
            <p>Currently handling courses</p>
            <a href="course.php"><button class="card-btn"><i class="fas fa-arrow-right"></i> View Courses</button></a>
        </div>

        <div class="card">
            <h2><i class="fas fa-bell"></i> Notification</h2>
            <p>New Announcement from Department</p>
            <a href="notification.php"><button class="card-btn"><i class="fas fa-arrow-right"></i> View Notification</button></a>
        </div>

        <div class="card">
            <h2><i class="fas fa-pen-nib"></i> Exam Marks</h2>
            <p>Add and delete marks</p>
            <a href="mid_term_filter.php"><button class="card-btn"><i class="fas fa-arrow-right"></i> Edit here</button></a>
        </div>

        <div class="card">
            <h2><i class="fas fa-calendar-alt"></i> Upcoming Events</h2>
            <p>Send message to students</p>
            <a href="ExamNotice.php"><button class="card-btn"><i class="fas fa-arrow-right"></i> Click here</button></a>
        </div>

        <!-- View Syllabus Card -->
        <div class="card">
            <h3>📘 View Syllabus</h3>
            <p>Browse syllabus by session & semester</p>
            <a href="view_syllabus.php"><button class="card-btn"><i class="fas fa-arrow-right"></i> Click here</button></a>
        </div>

        <!-- Mark Attendance Card -->
        <div class="card">
            <h3><i class="fas fa-calendar-alt"></i> Mark Attendance</h3>
            <p>Mark attendance of students .</p>
            <a href="faculty_attendance_filter.php" class="dashboard-btn">📋 Mark Attendance</a>
        </div>

        <div class="card">
            <h3><i class="fas fa-calendar-alt"></i> View Attendance</h3>
            <p>View attendance of students </p>
            <a href="view_attendance.php" class="dashboard-btn">📋 View Attendance</a>
        </div>

        <div class="card">
            <h3><i class="fas fa-time-alt"></i>📊 Time Table</h3>
            <p>View Time Table of students </p>
            <a href="timetable_view.php" class="dashboard-btn">📋 View Attendance</a>
        </div>

        <div class="card">
            <h3><i class="fas fa-time-alt"></i>📢 Broadcast</h3>
            <p>View Time Table of students </p>
            <a href="faculty_dashboard.php" class="dashboard-btn">📋 View Attendance</a>
        </div>
    </section>
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
