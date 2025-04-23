<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}

$facultyName = $_POST['faculty_name'];
$session = $_POST['session'];
$section = $_POST['section'];
$semester = $_POST['semester'];
$branch = $_POST['branch'];

// Subject, Marks Type, Total Marks (fetched globally from form)
$subject = $_POST["subject"]??'';
$marksType = $_POST["marks_type"] ?? '';
$totalMarks = $_POST["total_marks"] ?? '';

// Connect to database
$conn = new mysqli("localhost", "root", "", "Gradiefy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get faculty's assigned course and branch
$facultyQuery = $conn->prepare("SELECT branch FROM faculty WHERE name = ?");
$facultyQuery->bind_param("s", $facultyName);
$facultyQuery->execute();
$facultyQuery->bind_result($facultyBranch);
$facultyQuery->fetch();
$facultyQuery->close();

if ($facultyBranch !== $branch) {
    die("You are not authorized to add marks for students in this branch.");
}

$studentIds = explode(',', $_POST['student_ids']);
$insertedMarks = [];

foreach ($studentIds as $id) {
    $studentName = $_POST["student_name_$id"] ?? '';
    $rollNo = $_POST["rollno_$id"] ?? '';
    $marksObtained = $_POST["marks_obtained_$id"] ?? 0;
    $studentBranch = $_POST["branch_$id"] ?? '';

    // Check if the student belongs to the same branch as the faculty
    if ($studentBranch !== $facultyBranch) {
        // Skip this student if the branch doesn't match
        continue;
    }

    $stmt = $conn->prepare("INSERT INTO midterm_marks (student_name, rollno, subject, marks_type, marks_obtained, total_marks, session, section, semester, branch, faculty_name)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiiissss", $studentName, $rollNo, $subject, $marksType, $marksObtained, $totalMarks, $session, $section, $semester, $studentBranch, $facultyName);

    if ($stmt->execute()) {
        $insertedMarks[] = [
            'name' => $studentName,
            'rollno' => $rollNo,
            'subject' => $subject,
            'type' => $marksType,
            'marks' => $marksObtained,
            'total' => $totalMarks
        ];
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Marks Submission Confirmation</title>
    <link rel="stylesheet" href="/dept/CSS/submitmarks.css">
</head>
<body>

<div class="container">
    <h1>✅ Marks Submitted Successfully!</h1>
    <h3>Faculty: <?= htmlspecialchars($facultyName) ?></h3>
    <h4>Session: <?= htmlspecialchars($session) ?> | Section: <?= htmlspecialchars($section) ?> | Semester: <?= htmlspecialchars($semester) ?> | Branch: <?= htmlspecialchars($branch) ?></h4>

    <p><strong>Subject:</strong> <?= htmlspecialchars($subject) ?></p>
    <p><strong>Marks Type:</strong> <?= htmlspecialchars($marksType) ?></p>
    <p><strong>Total Marks:</strong> <?= htmlspecialchars($totalMarks) ?></p>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Roll No</th>
                <th>Subject</th>
                <th>Marks Type</th>
                <th>Marks Obtained</th>
                <th>Total Marks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($insertedMarks as $entry): ?>
            <tr>
                <td><?= htmlspecialchars($entry['name']) ?></td>
                <td><?= htmlspecialchars($entry['rollno']) ?></td>
                <td><?= htmlspecialchars($entry['subject']) ?></td>
                <td><?= htmlspecialchars($entry['type']) ?></td>
                <td><?= htmlspecialchars($entry['marks']) ?></td>
                <td><?= htmlspecialchars($entry['total']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="/dept/Faculty/Faculty.php" class="back-button">Back to Pannel</a>
</div>

</body>
</html>
