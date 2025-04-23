<?php
require_once '../db.php';

$session = $_POST['session'] ?? '';
$semester = $_POST['semester'] ?? '';
$branch = $_POST['branch'] ?? '';

$where = [];
if ($session !== '') $where[] = "session = '" . mysqli_real_escape_string($conn, $session) . "'";
if ($semester !== '') $where[] = "semester = '" . mysqli_real_escape_string($conn, $semester) . "'";
if ($branch !== '') $where[] = "branch = '" . mysqli_real_escape_string($conn, $branch) . "'";
$whereSql = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

$query = "SELECT name, rollno, email, session, semester, branch, contact FROM user $whereSql ORDER BY name";
$result = mysqli_query($conn, $query);

// CSV headers
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=students.csv');

// Open output stream
$output = fopen("php://output", "w");

// Add column headers
fputcsv($output, ['Name', 'Roll No', 'Email', 'Session', 'Semester', 'Branch', 'Contact']);

// Add rows
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}
fclose($output);
exit;
?>
