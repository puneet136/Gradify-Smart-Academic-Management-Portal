<?php
include("../db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $faculty_name = $_POST['faculty_name'];

    // File upload handling
    $file_name = $_FILES['syllabus']['name'];
    $file_tmp = $_FILES['syllabus']['tmp_name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($file_name);

    if (move_uploaded_file($file_tmp, $target_file)) {
        // Update the course table with the file link
        $sql = "UPDATE course SET syllabus_link = ? WHERE course_name = ? AND faculty_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $file_name, $course_name, $faculty_name);
        $stmt->execute();

        echo "✅ Syllabus uploaded and linked successfully.";
    } else {
        echo "❌ Failed to upload the file.";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <label>Course Name:</label>
    <input type="text" name="course_name" required><br><br>

    <label>Faculty Name:</label>
    <input type="text" name="faculty_name" required><br><br>

    <label>Upload Syllabus:</label>
    <input type="file" name="syllabus" accept=".pdf,.jpg,.png" required><br><br>

    <button type="submit">📤 Upload</button>
</form>
