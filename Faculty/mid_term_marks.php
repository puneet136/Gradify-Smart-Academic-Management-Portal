<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}

$facultyName = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "Gradiefy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form input
$session = $_POST['session'] ?? '';
$section = $_POST['section'] ?? '';
$semester = $_POST['semester'] ?? '';
$marks_type = $_POST['marks_type'] ?? '';
$total_marks = $_POST['total_marks'] ?? '';

// Fetch students
$query = $conn->prepare("SELECT * FROM user WHERE session = ? AND section = ? AND semester = ?");
$query->bind_param("sss", $session, $section, $semester);
$query->execute();
$result = $query->get_result();

$students = [];
$branch = '';
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
    if (!$branch) {
        $branch = $row['branch'];
    }
}

// Fetch assigned subject for this faculty
$subjectStmt = $conn->prepare("SELECT course_name FROM course WHERE faculty_name = ? AND branch = ?");
$subjectStmt->bind_param("ss", $facultyName, $branch);
$subjectStmt->execute();
$subjectResult = $subjectStmt->get_result();

$subject = '';
if ($row = $subjectResult->fetch_assoc()) {
    $subject = $row['course_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mid Term Marks - Faculty Panel</title>
    <link rel="stylesheet" href="/dept/CSS/mid_term_marks.css">
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 4px;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" style="color: black; padding: 8px 14px; border-radius: 4px;">Logout</a></li>
    </ul>
</nav>

<div class="logo-container">
    <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo" class="logo">
</div>

<h2>Students - <?= htmlspecialchars($session) ?> / Section <?= htmlspecialchars($section) ?> / Sem <?= htmlspecialchars($semester) ?> / Branch <?= htmlspecialchars($branch) ?></h2>

<form id="marksForm" method="POST" action="submit_marks.php">
    <input type="hidden" name="faculty_name" value="<?= htmlspecialchars($facultyName) ?>">
    <input type="hidden" name="session" value="<?= htmlspecialchars($session) ?>">
    <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
    <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>">
    <input type="hidden" name="branch" value="<?= htmlspecialchars($branch) ?>">
    <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
    <input type="hidden" name="marks_type" value="<?= htmlspecialchars($marks_type) ?>">
    <input type="hidden" id="totalMarks" name="total_marks" value="<?= htmlspecialchars($total_marks) ?>">

    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Roll No</th>
                <th>Marks Obtained</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $studentIds = [];
            foreach ($students as $row):
                $id = $row['id'];
                $name = $row['name'] ?? 'N/A';
                $rollno = $row['rollno'];
                $studentIds[] = $id;
            ?>
            <tr>
                <td><?= htmlspecialchars($name) ?></td>
                <td><?= htmlspecialchars($rollno) ?></td>
                <td>
                    <input type="number" name="marks_obtained_<?= $id ?>" required min="0">
                    <input type="hidden" name="student_name_<?= $id ?>" value="<?= htmlspecialchars($name) ?>">
                    <input type="hidden" name="rollno_<?= $id ?>" value="<?= htmlspecialchars($rollno) ?>">
                    <input type="hidden" name="branch_<?= $id ?>" value="<?= htmlspecialchars($branch) ?>">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="student_ids" value="<?= implode(',', $studentIds) ?>">
    <button type="submit" class="submit-btn">Submit Marks</button>
</form>

<div style="text-align: center; margin: 20px;">
    <a href="/dept/faculty/faculty.php" class="back-button" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
</div>

<footer class="footer">
    <div class="footer-left">
        <p>&copy; <?php echo date("Y"); ?> Computer Science and Engineering(Artificial Intelligence) Department</p>
    </div>
    <div class="footer-right">
        <p>Developed & Designed by Tushar Dhaker || Puneet Agrawal</p>
    </div>
</footer>

<script>
document.getElementById("marksForm").addEventListener("submit", function (e) {
    const totalMarks = parseFloat(document.getElementById("totalMarks").value);
    const inputs = document.querySelectorAll('input[type="number"]');
    let valid = true;

    // Remove previous error messages
    document.querySelectorAll(".error-message").forEach(el => el.remove());

    inputs.forEach(input => {
        const val = parseFloat(input.value);
        const studentId = input.name.replace("marks_obtained_", "");

        if (val > totalMarks) {
            const error = document.createElement("div");
            error.classList.add("error-message");
            error.innerText = `Marks for ID ${studentId} cannot be more than total marks (${totalMarks})`;

            input.parentNode.appendChild(error);
            input.style.border = '2px solid red';
            input.focus();
            valid = false;
        } else {
            input.style.border = '';
        }
    });

    if (!valid) e.preventDefault();
});
</script>

</body>
</html>
