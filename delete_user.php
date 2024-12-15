<?php
// Database connection
$conn = new mysqli("localhost", "harynnn", "harynnn123", "Lab_5b");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if matric is provided
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    $stmt = $conn->prepare("DELETE FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    if ($stmt->execute()) {
        header("Location: display_users.php"); // Redirect to the user list
        exit;
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "No user selected for deletion.";
}

$conn->close();
?>
