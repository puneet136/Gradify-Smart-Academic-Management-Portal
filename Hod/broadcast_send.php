<?php
include("../db.php");
session_start();

$role = $_GET['role'] ?? 'student'; // student or faculty

// Student data
$section = $_GET['section'] ?? '';
$branch = $_GET['branch'] ?? '';
$session_year = $_GET['session'] ?? '';

// Faculty data
$faculty_branch = $_GET['faculty_branch'] ?? '';
$semester = $_GET['semester'] ?? '';

// Fetch recipients
if ($role === 'student') {
    $sql = "SELECT * FROM user WHERE section=? AND branch=? AND session=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $section, $branch, $session_year);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Correct query for faculty join with course table
    $sql = "SELECT DISTINCT f.email, f.name, c.course_name 
            FROM faculty f
            JOIN course c ON f.name = c.faculty_name 
            WHERE f.branch = ? AND c.semester = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $faculty_branch, $semester);  // Correct bind_param usage for string
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Send Broadcast</title>
    <link rel="stylesheet" href="/dept/CSS/broadcast_send.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
    <style>
        .back-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            background: #3f51b5;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .back-btn i {
            margin-right: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #3f51b5;
            color: white;
        }
        button {
            margin-top: 20px;
            background: #d63384;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>
<a href="hod.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

<div class="container">
    <h2>📨 Send Message to <?= $role === 'faculty' ? 'Faculty' : 'Students' ?></h2>

    <form action="broadcast_submit.php" method="POST" enctype="multipart/form-data">
        <!-- Common Fields -->
        <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
        <label>Message:</label>
        <textarea name="message" placeholder="Type your message here..." required></textarea><br>

        <label>Attach File:</label>
        <input type="file" name="attachment"><br><br>

        <!-- Role-Specific Hidden Fields -->
        <?php if ($role === 'student'): ?>
            <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
            <input type="hidden" name="branch" value="<?= htmlspecialchars($branch) ?>">
            <input type="hidden" name="session" value="<?= htmlspecialchars($session_year) ?>">
        <?php else: ?>
            <input type="hidden" name="faculty_branch" value="<?= htmlspecialchars($faculty_branch) ?>">
            <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>">
        <?php endif; ?>

        <!-- Recipient Table -->
        <h3>🎯 Select Recipients:</h3>
        <table>
            <tr>
                <th><input type="checkbox" id="selectAll"> Select All</th>
                <th>Name</th>
                <th>Email</th>
                <?php if ($role === 'student'): ?>
                    <th>Roll No</th>
                    <th>Session</th>
                    <th>Branch</th>
                    <th>Section</th>
                <?php else: ?>
                    <th>Course</th>
                <?php endif; ?>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <?php if ($role === 'student'): ?>
                        <td><input type="checkbox" name="selected_students[]" value="<?= htmlspecialchars($row['email']) ?>"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['rollno']) ?></td>
                        <td><?= htmlspecialchars($row['session']) ?></td>
                        <td><?= htmlspecialchars($row['branch']) ?></td>
                        <td><?= htmlspecialchars($row['section']) ?></td>
                    <?php else: ?>
                        <td><input type="checkbox" name="selected_recipients[]" value="<?= htmlspecialchars($row['email']) ?>"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                    <?php endif; ?>
                </tr>
            <?php endwhile; ?>
        </table>

        <button type="submit">📤 Send Broadcast</button>
    </form>
</div>

<script>
    document.getElementById("selectAll").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]:not(#selectAll)');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
</body>
</html>