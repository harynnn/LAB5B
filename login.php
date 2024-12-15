<?php
session_start();
$message = ""; // To display error messages

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "harynnn", "harynnn123", "Lab_5b");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Query the database to find the user
    $stmt = $conn->prepare("SELECT password FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    // Validate the password
    if ($hashedPassword && password_verify($password, $hashedPassword)) {
        $_SESSION['matric'] = $matric; 
        header("Location: display_users.php"); 
        exit;
    } else {
        $message = "Invalid username or password. Try <a href='login.php'>login</a> again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <h2>Login Users</h2>

    <!-- Display error message if any -->
    <?php if ($message): ?>
        <p style="color: red;"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="login.php" method="POST">
        <label>Matric:</label>
        <input type="text" name="matric" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <p><a href="register.php">Register</a> here if you have not.</span></p>
</body>
</html>
