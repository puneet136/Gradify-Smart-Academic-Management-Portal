<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION["role"] !== "HOD") {
    header("Location: /dept/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "gradiefy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selected_branch = isset($_POST['branch']) ? $_POST['branch'] : '';
$selected_semester = isset($_POST['semester']) ? $_POST['semester'] : '';
$subjects = [];

// Fetch subjects based on selected branch and semester
if (!empty($selected_branch) && !empty($selected_semester)) {
    $stmt = $conn->prepare("SELECT course_name, faculty_name FROM course WHERE branch = ? AND semester = ?");
    $stmt->bind_param("ss", $selected_branch, $selected_semester);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
    $stmt->close();
}

// Fetch distinct branches and semesters
$branch_result = $conn->query("SELECT DISTINCT branch FROM course");
$semester_result = $conn->query("SELECT DISTINCT semester FROM course");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Subjects</title>
    <link rel="stylesheet" href="/dept/CSS/view_subject.css">
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
        <h2>📘 View Subjects by Branch & Semester</h2>
    </div>

    <form method="POST">
        <label for="branch">Select Branch:</label>
        <select name="branch" required>
            <option value="">-- Select Branch --</option>
            <?php while ($row = $branch_result->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['branch']) ?>" <?= $row['branch'] === $selected_branch ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['branch']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="semester" style="margin-left: 20px;">Select Semester:</label>
        <select name="semester" required>
            <option value="">-- Select Semester --</option>
            <?php while ($sem = $semester_result->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($sem['semester']) ?>" <?= $sem['semester'] === $selected_semester ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sem['semester']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit" style="margin-left: 20px;">View Subjects</button>
    </form>

    <?php if (!empty($subjects)): ?>
        <table>
            <thead>
                <tr>
                    <th>Subject Name</th>
                    <th>Faculty Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $sub): ?>
                    <tr>
                        <td class="bold-dark"><?= htmlspecialchars($sub['course_name']) ?></td>
                        <td class="bold-dark"><?= htmlspecialchars($sub['faculty_name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($selected_branch && $selected_semester): ?>
        <p>No subjects found for selected branch and semester.</p>
    <?php endif; ?>
</div>

<div style="text-align: center; margin: 20px;">
    <a href="/dept/hod/hod.php" class="back-button" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
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