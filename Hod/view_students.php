<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "HOD") {
    header("Location: /dept/login.php");
    exit();
}
require_once "../db.php";

// Fetch unique values for dropdown filters
$sessions = mysqli_query($conn, "SELECT DISTINCT session FROM user");
$sections = mysqli_query($conn, "SELECT DISTINCT section FROM user");
$branches = mysqli_query($conn, "SELECT DISTINCT branch FROM user");

// Filter logic
$where = "WHERE 1=1";
if (isset($_GET['session']) && $_GET['session'] !== "") {
    $session = mysqli_real_escape_string($conn, $_GET['session']);
    $where .= " AND session = '$session'";
}
if (isset($_GET['section']) && $_GET['section'] !== "") {
    $section = mysqli_real_escape_string($conn, $_GET['section']);
    $where .= " AND section = '$section'";
}
if (isset($_GET['branch']) && $_GET['branch'] !== "") {
    $branch = mysqli_real_escape_string($conn, $_GET['branch']);
    $where .= " AND branch = '$branch'";
}

$students = mysqli_query($conn, "SELECT * FROM user $where ORDER BY name");

$total_students_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM user");
$total_students = mysqli_fetch_assoc($total_students_result)['total'];

$filtered_students_result = mysqli_query($conn, "SELECT COUNT(*) AS filtered FROM user $where");
$filtered_students = mysqli_fetch_assoc($filtered_students_result)['filtered'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Students</title>
    <link rel="stylesheet" href="/dept/CSS/view_students.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <h1>📘 All Students Details</h1>
    </div>

    <form method="GET" class="filters">
        <select name="session">
            <option value="">-- Select Session --</option>
            <?php while ($row = mysqli_fetch_assoc($sessions)) : ?>
                <option value="<?= $row['session']; ?>" <?= (isset($_GET['session']) && $_GET['session'] == $row['session']) ? 'selected' : ''; ?>>
                    <?= $row['session']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="section">
            <option value="">-- Select section --</option>
            <?php while ($row = mysqli_fetch_assoc($sections)) : ?>
                <option value="<?= $row['section']; ?>" <?= (isset($_GET['section']) && $_GET['section'] == $row['section']) ? 'selected' : ''; ?>>
                    <?= $row['section']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="branch">
            <option value="">-- Select Branch --</option>
            <?php while ($row = mysqli_fetch_assoc($branches)) : ?>
                <option value="<?= $row['branch']; ?>" <?= (isset($_GET['branch']) && $_GET['branch'] == $row['branch']) ? 'selected' : ''; ?>>
                    <?= $row['branch']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Filter</button>
    </form>

    <div class="student-summary">
        <div class="summary-box">
            <h3>Total Students</h3>
            <p><?= $total_students ?></p>
        </div>
        <div class="summary-box">
            <h3>Filtered Results</h3>
            <p><?= $filtered_students ?></p>
        </div>
    </div>

    <canvas id="studentsChart" width="600" height="250" style="margin: 30px auto; display: block;"></canvas>

    <form method="POST" action="export_students.php" class="export-form">
        <input type="hidden" name="session" value="<?= htmlspecialchars($_GET['session'] ?? '') ?>">
        <input type="hidden" name="section" value="<?= htmlspecialchars($_GET['section'] ?? '') ?>">
        <input type="hidden" name="branch" value="<?= htmlspecialchars($_GET['branch'] ?? '') ?>">
        <button type="submit" class="export-btn">📄 Export to CSV</button>
    </form>

    <table>
        <tr>
            <th>Name</th>
            <th>Roll No</th>
            <th>Session</th>
            <th>section</th>
            <th>Branch</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Father's Name</th>
            <th>Father's Contact</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($students)): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['rollno']) ?></td>
            <td><?= htmlspecialchars($row['session']) ?></td>
            <td><?= htmlspecialchars($row['section']) ?></td>
            <td><?= htmlspecialchars($row['branch']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['father_name']) ?></td>
            <td><?= htmlspecialchars($row['father_contact']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="/dept/HOD/hod.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<footer class="footer">
    <div class="footer-left">
        <p>&copy; <?php echo date("Y"); ?> Computer Science and Engineering (Artificial Intelligence) Department</p>
    </div>
    <div class="footer-right">
        <p>Developed & Designed by Tushar Dhaker || Puneet Agrawal</p>
    </div>
</footer>

<script>
const ctx = document.getElementById('studentsChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Total Students', 'Filtered Students'],
        datasets: [{
            label: 'Student Count',
            data: [<?= $total_students ?>, <?= $filtered_students ?>],
            backgroundColor: ['#3498db', '#e74c3c'],
            borderColor: ['#2980b9', '#c0392b'],
            borderWidth: 1,
            borderRadius: 8
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 10
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>

</body>
</html>
