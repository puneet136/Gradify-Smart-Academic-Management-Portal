<?php
include("../db.php");

// Fetch distinct values
$sessions = mysqli_query($conn, "SELECT DISTINCT session FROM user");
$student_branches = mysqli_query($conn, "SELECT DISTINCT branch FROM user");
$sections = mysqli_query($conn, "SELECT DISTINCT section FROM user");

$faculty_branches = mysqli_query($conn, "SELECT DISTINCT branch FROM course");
$semesters = mysqli_query($conn, "SELECT DISTINCT semester FROM course");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Broadcast Filter</title>
    <link rel="stylesheet" href="/dept/CSS/broadcast.css">
    <script>
        function toggleTargetOptions() {
            var role = document.getElementById("targetRole").value;
            document.getElementById("studentOptions").style.display = (role === "student") ? "block" : "none";
            document.getElementById("facultyOptions").style.display = (role === "faculty") ? "block" : "none";
        }
    </script>
</head>
<body>
    
    <div class="container">
        <h2>📣 Select Broadcast Audience</h2>

        <form action="broadcast_send.php" method="get">
            <label>Send To:</label>
            <select name="role" id="targetRole" onchange="toggleTargetOptions()" required>
                <option value="">Select Audience</option>
                <option value="student">Students</option>
                <option value="faculty">Faculty</option>
            </select>

            <!-- Student Filter Options -->
            <div id="studentOptions" style="display: none;">
                <label>Session:</label>
                <select name="session">
                    <option value="">Select Session</option>
                    <?php while ($row = mysqli_fetch_assoc($sessions)) {
                        echo "<option value='{$row['session']}'>{$row['session']}</option>";
                    } ?>
                </select>

                <label>Branch:</label>
                <select name="branch">
                    <option value="">Select Branch</option>
                    <?php while ($row = mysqli_fetch_assoc($student_branches)) {
                        echo "<option value='{$row['branch']}'>{$row['branch']}</option>";
                    } ?>
                </select>

                <label>Section:</label>
                <select name="section">
                    <option value="">Select Section</option>
                    <?php while ($row = mysqli_fetch_assoc($sections)) {
                        echo "<option value='{$row['section']}'>{$row['section']}</option>";
                    } ?>
                </select>
            </div>

            <!-- Faculty Filter Options -->
            <div id="facultyOptions" style="display: none;">
                <label>Branch:</label>
                <select name="faculty_branch">
                    <option value="">Select Branch</option>
                    <?php while ($row = mysqli_fetch_assoc($faculty_branches)) {
                        echo "<option value='{$row['branch']}'>{$row['branch']}</option>";
                    } ?>
                </select>

                <label>Semester:</label>
                <select name="semester">
                    <option value="">Select Semester</option>
                    <?php while ($row = mysqli_fetch_assoc($semesters)) {
                        echo "<option value='{$row['semester']}'>{$row['semester']}</option>";
                    } ?>
                </select>
            </div>

            <button type="submit">Filter Audience</button>
        </form>
    </div>

    <div style="text-align: center; margin: 20px;">
        <a href="/dept/hod/hod.php" class="back-button" style="padding: 10px 20px; background-color: #0056b3; color: white; text-decoration: none; border-radius: 5px;">⬅ Back to Dashboard</a>
    </div>
    
</body>
</html>