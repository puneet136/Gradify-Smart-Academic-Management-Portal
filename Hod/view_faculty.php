<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "HOD") {
    header("Location: /dept/login.php");
    exit();
}
require_once "../db.php"; // Adjust path based on your structure
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Faculty Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/dept/CSS/view_faculty.css">
    <style>
    .export-container {
        display: flex;
        justify-content: flex-end;
        margin: 20px 50px 10px 0px;
    
}

.export-btn {
    padding: 10px 20px;
    background-color: #009688;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.export-btn:hover {
    background-color: #00796b;
}

    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">CSE(AI) Department</div>
    <ul class="nav-links">
        <li><a href="/dept/Home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="/dept/logout.php" class="logout-btn">Logout</a></li>
    </ul>
</nav>

<!-- HEADER WITH LOGO -->
<div class="header">
    <img src="/dept/img/jaipur_engineering_college_and_research_centre_jecrc__logo__1_-removebg-preview.png" alt="JECRC Logo">
    <h1>Faculty Details</h1>
</div>

<!-- EXPORT BUTTON -->
<form method="post" action="export_faculty_csv.php">
    <button type="submit" class="export-btn"><i class="fas fa-file-csv"></i> Export as CSV</button>
</form>

<!-- FACULTY TABLE -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>Branch</th>
                <th>Designation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, name, email, contact, branch , designation FROM Faculty";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['contact']}</td>
                        <td>{$row['branch']}</td>
                        <td>{$row['designation']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No faculty data found</td></tr>";
            }

            mysqli_close($conn);
            ?>
        </tbody>
    </table>

    <div class="btn-center">
        <a href="hod.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="footer-left">
        <p>&copy; <?php echo date("Y"); ?> Computer Science and Engineering (Artificial Intelligence) Department</p>
    </div>
    <div class="footer-right">
        <p>Developed & Designed by Tushar Dhaker || Puneet Agrawal</p>
    </div>
</footer>

</body>
</html>
