<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Common fields
    $message = $_POST['message'];
    $role = $_POST['role'] ?? 'student'; // Default role = student

    // Sender info
    $sender_name = $_SESSION['username'] ?? 'Unknown';
    $sender_role = $_SESSION['role'] ?? 'HOD';

    // Handle file upload
    $filename = "";
    if (!empty($_FILES["attachment"]["name"])) {
        $upload_dir = "../uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = basename($_FILES["attachment"]["name"]);
        $target = $upload_dir . $filename;
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $target);
    }

    // Handling student broadcast
    if ($role === 'student' && !empty($_POST['selected_students'])) {
        $selected_students = $_POST['selected_students'];
        $section = $_POST['section'];
        $branch = $_POST['branch'];
        $session_year = $_POST['session'];

        $insert = $conn->prepare("INSERT INTO broadcast_messages 
            (sender_name, sender_role, recipient_email, message, attachment, session, section, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

        foreach ($selected_students as $email) {
            $insert->bind_param("sssssss", $sender_name, $sender_role, $email, $message, $filename, $session_year, $section);
            $insert->execute();
        }

        echo "<script>alert('Broadcast sent to students successfully!'); window.location.href='/dept/hod/hod.php';</script>";

    } 
    // Handling faculty broadcast
    elseif ($role === 'faculty' && !empty($_POST['selected_recipients'])) {
        $selected_faculty = $_POST['selected_recipients'];
        $faculty_branch = $_POST['faculty_branch'];
        $semester = $_POST['semester'];

        $insert = $conn->prepare("INSERT INTO broadcast_messages 
            (sender_name, sender_role, recipient_email, message, attachment,  created_at) 
            VALUES (?, ?, ?, ?,  ?, NOW())");

        foreach ($selected_faculty as $email) {
            $insert->bind_param("sssss", $sender_name, $sender_role, $email, $message, $filename);
            $insert->execute();
        }

        echo "<script>alert('Broadcast sent to faculty successfully!'); window.location.href='/dept/hod/hod.php';</script>";

    } 
    else {
        echo "<script>alert('No recipients selected.'); window.location.href='broadcast_filter.php';</script>";
    }

} else {
    echo "<script>alert('Invalid request.'); window.location.href='broadcast_filter.php';</script>";
}
?>