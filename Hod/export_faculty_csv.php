<?php
require_once "../db.php"; // Adjust path as needed

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=faculty_details.csv');

$output = fopen("php://output", "w");
fputcsv($output, array('Id', 'Name', 'Email', 'Contact Number', 'Branch'));

$sql = "SELECT id, name, email, contactNumber, branch FROM faculty";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
