<?php
include '../db.php';
session_start();

$faculty = $_SESSION["username"];
$date = $_POST['attendance_date'];

$session = $_POST['session'];
$branch = $_POST['branch'];
$semester = $_POST['semester'];
$section = $_POST['section'];
$subject = $_POST['subject'];

$status = $_POST['status']; // contains only students marked Present (checked)

$students = $conn->prepare("SELECT rollno, name FROM user WHERE session = ? AND branch = ? AND semester = ? AND section = ?");
$students->bind_param("ssss", $session, $branch, $semester, $section);
$students->execute();
$result = $students->get_result();

while ($row = $result->fetch_assoc()) {
    $roll = $row['rollno'];
    $name = $row['name'];

    $att_status = isset($status[$roll]) ? 'Present' : 'Absent';

    $stmt = $conn->prepare("INSERT INTO attendance (faculty_name, session, branch, semester, section, subject, date, rollno, name, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $faculty, $session, $branch, $semester, $section, $subject, $date, $roll, $name, $att_status);
    $stmt->execute();
}

echo "<script>alert('Attendance submitted successfully!'); window.location.href='faculty_attendance_filter.php';</script>";
?>
