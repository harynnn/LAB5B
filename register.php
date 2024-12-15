<?php
class Database {
    private $host = "localhost";
    private $db_name = "Lab_5b";
    private $username = "harynnn";
    private $password = "harynnn123";
    public $conn;

    public function getConnection() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }
}

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new user
    public function createUser($matric, $name, $password, $role) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $matric, $name, $passwordHash, $role);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $database = new Database();
    $conn = $database->getConnection();

    // Create a user object
    $user = new User($conn);

    // Get form data
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Insert user into database
    $result = $user->createUser($matric, $name, $password, $role);

    if ($result === true) {
        echo "New record created successfully";
    } else {
        echo $result; // Show error message if the insertion failed
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <h2>Register Form</h2>

    <form action="" method="POST">
        <label>Matric:</label>
        <input type="text" name="matric" required><br><br>

        <label>Name:</label>
        <input type="text" name="name" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Role:</label>
        <select name="role" id="role" required>
            <option value="">Please select</option>
            <option value="student">Student</option>
            <option value="lecturer">Lecturer</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
