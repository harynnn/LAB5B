<?php
include 'session_check.php';

// Database connection
$conn = new mysqli("localhost", "harynnn", "harynnn123", "Lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data for the update form
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    $stmt = $conn->prepare("SELECT name, role FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $stmt->bind_result($name, $role);
    $stmt->fetch();
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET name = ?, role = ? WHERE matric = ?");
    $stmt->bind_param("sss", $name, $role, $matric);
    if ($stmt->execute()) {
        header("Location: display_users.php"); // Redirect back to user list
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <h2>Update User</h2>
    <form action="update_user.php" method="POST">
        <label>Matric:</label>
        <input type="hidden" name="matric" value="<?php echo $matric; ?>">
        <input type="text" value="<?php echo $matric; ?>" required><br><br>

        <label>Name:</label>
        <input type="text" name="name" value="<?php echo $name; ?>" required><br><br>

        <label>Access Level:</label>
        <select name="role" required>
            <option value="student" <?php if ($role == "student") echo "selected"; ?>>Student</option>
            <option value="lecturer" <?php if ($role == "lecturer") echo "selected"; ?>>Lecturer</option>
        </select><br><br>
        <button type="submit">Update</button>
        <a href="display_users.php">Cancel</a>
    </form>
</body>
</html>
<?php $conn->close(); ?>

