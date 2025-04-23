<?php
session_start();
if (!isset($_SESSION['loggedin']) || !in_array($_SESSION["role"], ["Faculty", "HOD"])) {
    header("Location: /dept/login.php");
    exit();
}


$facultyId = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "gradiefy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Add new exam notice
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_notice"])) {
    $exam_title = $_POST["exam_title"];
    $exam_date = $_POST["exam_date"];
    $description = $_POST["description"];

    $stmt = $conn->prepare("INSERT INTO exam_schedule (title, date, faculty_name, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $exam_title, $exam_date, $facultyId, $description);

    if ($stmt->execute()) {
        $message = "✅ Exam notice posted successfully by <strong>$facultyId</strong>!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
    $stmt->close();
}

// Delete notice
if (isset($_GET['title']) && isset($_GET['date'])) {
    $delete_title = urldecode($_GET['title']);
    $delete_date = $_GET['date'];

    $stmt = $conn->prepare("DELETE FROM exam_schedule WHERE title=? AND date=? AND faculty_name=?");
    $stmt->bind_param("sss", $delete_title, $delete_date, $facultyId);
    $stmt->execute();
    $stmt->close();

    header("Location: ExamNotice.php");
    exit();
}

// Update notice
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_notice"])) {
    $original_title = $_POST["original_title"];
    $original_date = $_POST["original_date"];
    $edit_title = $_POST["edit_title"];
    $edit_date = $_POST["edit_date"];
    $edit_description = $_POST["edit_description"];

    $stmt = $conn->prepare("UPDATE exam_schedule SET title=?, date=?, description=? WHERE title=? AND date=? AND faculty_name=?");
    $stmt->bind_param("ssssss", $edit_title, $edit_date, $edit_description, $original_title, $original_date, $facultyId);
    $stmt->execute();
    $stmt->close();

    header("Location: ExamNotice.php");
    exit();
}

// Fetch notices
$stmt = $conn->prepare("SELECT * FROM exam_schedule WHERE faculty_name = ?");
$stmt->bind_param("s", $facultyId);
$stmt->execute();
$notices = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post Exam Notice</title>
    <link rel="stylesheet" href="/dept/CSS/ExamNotice.css">
</head>
<body>
    <style>
        .header{
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
<header class="header">
    <div class="header-left">CSE(AI) Department</div>
    <div class="header-center">
    <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo" class="logo" />
</div>
    <div class="header-right">
    <a href="/dept/Home.php">Home</a>
    <a href="#">About</a>
    <a href="#">Contact</a>
    <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
</div>
</header>

<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($facultyId); ?> </h1>
    <h2>Post New Exam Notice</h2>

    <?php if (!empty($message)) echo "<p class='message'>$message</p>"; ?>

    <form method="POST" class="exam-form">
        <label>Exam Title</label>
        <input type="text" name="exam_title" required>

        <label>Exam Date</label>
        <input type="date" name="exam_date" required>

        <label for="description">Test Description</label>
        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Enter test description..."></textarea>

        <button type="submit" name="add_notice">Post Exam Notice</button>
    </form>

    <h2>📋 Your Posted Exam Notices</h2>
    <?php if ($notices->num_rows > 0): ?>
        <table>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $notices->fetch_assoc()): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="original_title" value="<?= htmlspecialchars($row['title']) ?>">
                        <input type="hidden" name="original_date" value="<?= $row['date'] ?>">
                        <td><input type="text" name="edit_title" value="<?= htmlspecialchars($row['title']) ?>" required></td>
                        <td><input type="date" name="edit_date" value="<?= $row['date'] ?>" required></td>
                        <td><textarea name="edit_description" rows="2"><?= htmlspecialchars($row['description']) ?></textarea></td>
                        <td>
                            <button type="submit" name="update_notice">✏ Update</button>
                            <a href="?title=<?= urlencode($row['title']) ?>&date=<?= $row['date'] ?>" onclick="return confirm('Delete this exam notice?')">🗑 Delete</a>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No exam notices posted yet.</p>
    <?php endif; ?>
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