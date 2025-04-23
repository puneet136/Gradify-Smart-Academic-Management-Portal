<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "HOD" ) {
    header("Location: /dept/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Dashboard</title>
    <link rel="stylesheet" href="/dept/CSS/hod.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
    </div>

    <div class="grid">
        <div class="card">
            <h3>👩‍🏫 Faculty</h3>
            <p>View all details about your faculty</p>
            <a href="view_faculty.php"><button>View All</button></a>
        </div>

        <div class="card">
            <h3>🎓 Students</h3>
            <p>Filter and browse all students by session, semester, and branch</p>
            <a href="view_students.php"><button>View Students</button></a>
        </div>

        <div class="card">
            <h3>📚 Subjects</h3>
            <p>Access all subject details in one streamlined view</p>
            <a href="view_subject.php"><button>View All</button></a>
        </div>

        <div class="card">
            <h3>📢 Broadcast</h3>
            <p>Send important announcements to students and faculty instantly</p>
            <a href="broadcast.php"><button>Click Here</button></a>
        </div>

        <div class="card">
            <h3>📝 Marks Submitted</h3>
            <p>View details about submitted student marks</p>
            <a href="marks_submitted_filter.php"><button>View Details</button></a>
        </div>

        <div class="card">
            <h3>📅 Upcoming Events</h3>
            <p>View all upcoming events in the department</p>
            <a href="/dept/Faculty/ExamNotice.php"><button>Click Here</button></a>
        </div>

        <div class="card">
            <h3>🔔 Notification</h3>
            <p>Check department notifications</p>
            <a href="/dept/Faculty/notification.php"><button>Only View Notifications</button></a>
        </div>

        <div class="card">
            <h3>📊 Attendance</h3>
            <p>Check Students Attendance</p>
            <a href="hod_view_attendance.php"><button>Check Attendance</button></a>
        </div>

        <div class="card">
            <h3>📚 View Syllabus</h3>
            <p>Check Students Syllabus</p>
            <a href="/dept/faculty/view_syllabus.php"><button>Only View Notifications</button></a>
        </div>

        <div class="card">
            <h3>🕰️ View Time Table</h3>
            <p>Check Students Time Table by semester</p>
            <a href="/dept/faculty/timetable_view.php"><button> View Time Table</button></a>
        </div>
    </div>
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
