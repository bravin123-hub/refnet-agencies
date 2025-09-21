<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connection.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // ✅ Check only admins table
    $stmt = $conn->prepare("SELECT id, email, password, first_name FROM admins WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // ✅ Admin session
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['email']    = $row['email'];
            $_SESSION['role']     = 'admin';
            $_SESSION['name']     = $row['first_name'];

            header("Location: admin_intro1.php"); 
            exit();
        } else {
            echo "❌ Invalid password!";
        }
    } else {
        echo "❌ No admin found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - RefNet</title>
</head>
<body>
    <h2>Admin Login</h2>
    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    <p><a href="login.php">Login as User</a></p>
</body>
</html>
