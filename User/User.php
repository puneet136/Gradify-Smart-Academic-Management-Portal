<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Student") {
    header("Location:/dept/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- External CSS -->
    <link rel="stylesheet" href="/dept/CSS/User.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">CSE(AI) Department</div>
        <ul class="nav-links">
            <li><a href="/dept/home.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
        </ul>
    </nav>

    <!-- Dashboard Container -->
    <div class="container">
        <header>
            <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo" class="logo">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        </header>

        <section class="performance">
            <!-- Mid-Term Marks Card -->
            <div class="card">
                <h3>📑 Exam Marks</h3>
                <p>Check your latest exam scores.</p>
                <a href="ViewMarks.php"><button><i class="fas fa-clipboard-check"></i> View Marks</button></a>
            </div>

            <!-- Upcoming Tests Card -->
            <div class="card">
                <h3>📅 Upcoming Events</h3>
                <p>Stay updated with upcoming events!</p>
                <a href="StudentExamNotice.php"><button><i class="fas fa-calendar-alt"></i> View Schedule</button></a>
            </div>

            <!-- Notifications Card -->
            <div class="card">
                <h3>🔔 Notifications</h3>
                <p>Check latest updates from faculty.</p>
                <a href="Student_notification.php"><button><i class="fas fa-bell"></i> View All</button></a>
            </div>

            <!-- Broadcast Card -->
            <div class="card">
                <h3>📢 Broadcast</h3>
                <p>Check latest updates from HOD.</p>
                <a href="student_dashboard.php"><button><i class="fas fa-bullhorn"></i> View All</button></a>
            </div>

            <div class="card">
                <h3>📊 View Attendance</h3>
                <p>Check Your Daily Attendance</p>
                <a href="view_attendance.php"><button><i class="fas fa-clipboard-list"></i> View All</button></a>
            </div>

            <div class="card">
                <h3><i class="fas fa-book"></i> View Syllabus</h3>
                <p>Browse syllabus by semester</p>
                <a href="view_syllabus_student.php"><button>View All</button></a>
            </div>

            <div class="card">
                <h3><i class="fas fa-clock"></i> View Time Table</h3>
                <p>View Time Table by semester</p>
                <a href="/dept/faculty/timetable_view.php"><button>View All</button></a>
            </div>
        </section>
    </div>

    <!-- Footer -->
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
