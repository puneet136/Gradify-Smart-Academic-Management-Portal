<?php
include '../db.php';
session_start();

$faculty = $_SESSION["username"];
$date = $_GET['date'] ?? date('Y-m-d');

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=attendance_' . $date . '.csv');

$output = fopen("php://output", "w");
fputcsv($output, ['Name', 'Roll No', 'Status', 'Subject', 'Date']);

$result = $conn->query("SELECT * FROM attendance WHERE faculty_name = '$faculty' AND date = '$date'");
while($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['name'], $row['rollno'], $row['status'], $row['subject'], $row['date']]);
}
fclose($output);
exit();
?>
