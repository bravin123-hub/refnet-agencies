<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']); 
    $password = $_POST['password'];

    // ✅ Use email column for login
    $stmt = $conn->prepare("SELECT id, email, password, first_name, role FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // ✅ Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email']   = $row['email'];
            $_SESSION['role']    = $row['role'];
            $_SESSION['name']    = $row['first_name'];

            // ✅ Redirect based on role
            if ($row['role'] === 'admin') {
                header("Location: admin_intro1.php");
            } else {
                header("Location: index.php"); // your user dashboard
            }
            exit();
        } else {
            $error = "❌ Wrong password";
        }
    } else {
        $error = "❌ User not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 350px;
        }
        .login-box h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            width: 100%; 
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }
        button {
            width: 100%; 
            padding: 12px;
            margin-top: 15px;
            background: #2d80f7; 
            color: #fff;
            border: none; 
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover { background: #1c5fd3; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Sign In</h2>
        <?php if($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label>Email</label>
            <input type="text" name="email" placeholder="Enter your Email" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your Password" required>

            <div class="actions">
                <label><input type="checkbox" name="remember"> Remember me</label>
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <button type="submit">Sign In</button>
        </form>

        <p style="text-align:center; margin-top:15px;">
            Don’t have an account? <a href="register.php">Sign Up</a>
        </p>
    </div>
</body>
</html>
