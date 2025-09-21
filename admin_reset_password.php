<?php
session_start();
include("connection.php"); // 🔗 your DB connection file

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // check if email exists in admins table
    $check = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        if ($new_password === $confirm_password) {
            $hashed = password_hash($new_password, PASSWORD_BCRYPT);

            $update = $conn->prepare("UPDATE admins SET password=? WHERE email=?");
            $update->bind_param("ss", $hashed, $email);

            if ($update->execute()) {
                $message = "✅ Password reset successful! You can now login.";
            } else {
                $message = "❌ Failed to update password.";
            }
        } else {
            $message = "⚠️ Passwords do not match.";
        }
    } else {
        $message = "⚠️ No admin found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; display:flex; justify-content:center; align-items:center; height:100vh; }
        .reset-box { background:white; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.2); width:350px; }
        h2 { text-align:center; color:#2c3e50; }
        input[type=email], input[type=password] {
            width:100%; padding:10px; margin:8px 0; border:1px solid #ccc; border-radius:5px;
        }
        button {
            width:100%; padding:10px; background:#1abc9c; border:none; color:white; border-radius:5px;
            cursor:pointer; font-size:16px;
        }
        button:hover { background:#16a085; }
        p { text-align:center; color:red; }
    </style>
</head>
<body>
    <div class="reset-box">
        <h2>🔑 Reset Admin Password</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your admin email" required>
            <input type="password" name="new_password" placeholder="New password" required>
            <input type="password" name="confirm_password" placeholder="Confirm password" required>
            <button type="submit">Reset Password</button>
        </form>
        <p><?php echo $message; ?></p>
    </div>
</body>
</html>
