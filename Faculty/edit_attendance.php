<?php
session_start();
include '../db.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Faculty") {
    header("Location: /dept/login.php");
    exit();
}

// Get attendance data from POST
if (isset($_POST['status']) && is_array($_POST['status'])) {
    foreach ($_POST['status'] as $id => $new_status) {
        $id = intval($id);
        $new_status = $conn->real_escape_string($new_status);
        $update = $conn->prepare("UPDATE attendance SET status=? WHERE id=?");
        $update->bind_param("si", $new_status, $id);
        $update->execute();
    }

    // Optional: Redirect back to view page with confirmation
    $_SESSION['attendance_update_success'] = "Attendance updated successfully.";
    header("Location: view_attendance.php");
    exit();
} else {
    echo "No attendance data received.";
}
?>
