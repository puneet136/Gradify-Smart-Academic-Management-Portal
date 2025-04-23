<?php
include '../db.php';

$session = $_POST['session'] ?? '';
$semester = $_POST['semester'] ?? '';
$section = $_POST['section'] ?? '';
$subject = $_POST['subject'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

// Build Filter
$attendanceFilter = "WHERE 1";
if ($session)  $attendanceFilter .= " AND rollno IN (SELECT rollno FROM user WHERE session = '$session')";
if ($semester) $attendanceFilter .= " AND rollno IN (SELECT rollno FROM user WHERE semester = '$semester')";
if ($section)  $attendanceFilter .= " AND rollno IN (SELECT rollno FROM user WHERE section = '$section')";
if ($subject)  $attendanceFilter .= " AND subject = '$subject'";
if ($start_date && $end_date) {
    $attendanceFilter .= " AND date BETWEEN '$start_date' AND '$end_date'";
} elseif ($start_date) {
    $attendanceFilter .= " AND date >= '$start_date'";
} elseif ($end_date) {
    $attendanceFilter .= " AND date <= '$end_date'";
}

// Main Query
$attendanceQuery = "SELECT rollno, subject, date, status FROM attendance $attendanceFilter ORDER BY rollno, subject, date";
$result = $conn->query($attendanceQuery);

// Prepare to group for percentage
$records = [];
$percentages = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;

        $rollno = $row['rollno'];
        $subj = $row['subject'];
        $key = $rollno . '_' . $subj;

        if (!isset($percentages[$key])) {
            $totalQuery = "SELECT COUNT(*) AS total FROM attendance WHERE rollno = '$rollno' AND subject = '$subj'";
            $presentQuery = "SELECT COUNT(*) AS present FROM attendance WHERE rollno = '$rollno' AND subject = '$subj' AND status = 'Present'";
            $total = $conn->query($totalQuery)->fetch_assoc()['total'] ?? 0;
            $present = $conn->query($presentQuery)->fetch_assoc()['present'] ?? 0;
            $percentages[$key] = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        }
    }
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="attendance_records.csv"');

$output = fopen("php://output", "w");
fputcsv($output, ['Roll No', 'Subject', 'Date', 'Status', 'Attendance %']);

foreach ($records as $row) {
    $key = $row['rollno'] . '_' . $row['subject'];
    $percentage = $percentages[$key] ?? 0;
    fputcsv($output, [$row['rollno'], $row['subject'], $row['date'], $row['status'], $percentage . "%"]);
}

fclose($output);
exit;
?>
