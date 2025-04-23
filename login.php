<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "your_db_name");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get role from URL if available
$role = isset($_GET["role"]) ? $_GET["role"] : "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["name"];
    $password = $_POST["pass"];
    $role = $_POST["role"];

    // Check from Authenticate table
    $stmt = $conn->prepare("SELECT pass FROM authenticate WHERE name = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if ($hashed_password) {
        if ($password === $hashed_password || password_verify($password, $hashed_password)) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;

            // Redirect based on role
            if ($role == "Faculty") {
                header("Location: /dept/Faculty/Faculty.php");
            } elseif ($role == "Student") {
                // ✅ Fetch rollno and email from user table
                $stmt = $conn->prepare("SELECT rollno, email FROM user WHERE name = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($rollno, $email);
                $stmt->fetch();
                $stmt->close();

                $_SESSION["email"] = $email;
                $_SESSION["rollno"] = $rollno; // ✅ Store rollno
                header("Location: /dept/User/User.php");
            } elseif ($role == "HOD") {
                header("Location: /dept/hod/hod.php");
            }

            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Invalid username or role!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/dept/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="your_logo_path" alt="JECRC Logo">
        </div>
        <h2>Login</h2>
        <form method="post" action="">
            <select name="role" required <?= $role ? 'disabled' : '' ?>>
                <option value="" disabled <?= empty($role) ? 'selected' : '' ?>>Select Role</option>
                <option value="Student" <?= $role == "Student" ? 'selected' : '' ?>>Student</option>
                <option value="Faculty" <?= $role == "Faculty" ? 'selected' : '' ?>>Faculty</option>
                <option value="HOD" <?= $role == "HOD" ? 'selected' : '' ?>>HOD</option>
            </select>

            <?php if ($role): ?>
                <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
            <?php endif; ?>

            <input type="text" name="name" placeholder="Username" required><br>
            <input type="password" name="pass" placeholder="Password" required><br>
            <button type="submit">Login</button>
            <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
        </form>
    </div>
</body>
</html>
