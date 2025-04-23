<?php
session_start();
if (!isset($_SESSION['loggedin']) || !in_array($_SESSION["role"], ["Faculty", "HOD"])) 
    {
    header("Location: /dept/login.php");
    exit();
}

$facultyName = $_SESSION['username'];
$facultyRole = $_SESSION['role'];

$conn = new mysqli("localhost", "root", "", "gradiefy");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get faculty ID
$facultyID = null;
$stmt = $conn->prepare("SELECT id FROM faculty WHERE name = ?");
$stmt->bind_param("s", $facultyName);
$stmt->execute();
$stmt->bind_result($facultyID);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = trim($_POST['message']);
    $attachment = "";

    // File upload
    if (!empty($_FILES["attachment"]["name"])) {
        $upload_dir = "../uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique name
        $originalName = basename($_FILES["attachment"]["name"]);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9_\-]/", "_", pathinfo($originalName, PATHINFO_FILENAME));
        $finalName = $safeName . "." . $extension;

        // Move uploaded file
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $upload_dir . $finalName)) {
            $attachment = $finalName;
        } else {
            echo "<script>alert('File upload failed!');</script>";
        }
    }

    if (!empty($message) && $facultyID !== null) {
        $stmt = $conn->prepare("INSERT INTO notification (id, name, message, attachment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $facultyID, $facultyName, $message, $attachment);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch notifications
$sql = "SELECT name, message, attachment, created_at FROM notification ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification</title>
    <link rel="stylesheet" href="/dept/CSS/notification.css">
</head>
<body>
<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<div class="logo-container">
    <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo" class="logo">
</div>

<div class="container">
    <h1>Notifications</h1>

    <form method="post" enctype="multipart/form-data" class="form">
        <textarea name="message" placeholder="Write your notification..." required></textarea>
        <label>Attach File:</label>
        <input type="file" name="attachment"><br><br>
        <button type="submit">Send Notification</button>
    </form>

    <div class="notification-list">
        <h2>Recent Notifications</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="notification-item">
                    <strong>
                        <?php echo htmlspecialchars($row['name']); ?>
                    </strong><br>
                    <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                    <?php if (!empty($row['attachment'])): ?>
                        <a href="../uploads/<?php echo rawurlencode($row['attachment']); ?>" target="_blank" style="color: blue;">📎 Download Attachment</a><br>
                    <?php endif; ?>
                    <span class="timestamp"><?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?></span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No notifications yet.</p>
        <?php endif; ?>
    </div>
</div>

<div style="text-align: center; margin: 20px;">
    <a href="/dept/faculty/faculty.php" class="back-button" style="padding: 10px 20px; background-color:rgb(169, 57, 57); color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
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
