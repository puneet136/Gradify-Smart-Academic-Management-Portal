<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Student") {
    header("Location:/dept/login.php");
    exit();
}

include("../db.php");
$email = $_SESSION["email"];

$sql = "SELECT message, attachment, sender_name, created_at FROM broadcast_messages WHERE recipient_email = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Broadcast Messages</title>
    <link rel="stylesheet" href="/dept/CSS/student_dashboard.css">
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
<div class="container">
<div class="logo">
    <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo">
</div>
    <h2>📢 Your Broadcast Messages</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Attachment</th>
                    <th>Sender</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Message"><?= htmlspecialchars($row['message']); ?></td>
                        <td data-label="Attachment">
                            <?php if (!empty($row['attachment'])): ?>
                                <a href="/dept/uploads/<?= htmlspecialchars($row['attachment']); ?>" target="_blank">Download</a>
                            <?php else: ?>
                                No Attachment
                            <?php endif; ?>
                        </td>
                        <td data-label="Sender"><?= htmlspecialchars($row['sender_name']); ?></td>
                        <td data-label="Time"><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No broadcast messages found.</p>
    <?php endif; ?>
</div>
<div style="text-align: center; margin: 20px;">
        <a href="/dept/user/user.php" class="back-button" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
    </div>
<footer class="footer">
        <p>&copy; <?php echo date("Y"); ?>Computer Science and Engineering (Artificial Intelligence) Department</p>
    </footer>

</body>
</html>