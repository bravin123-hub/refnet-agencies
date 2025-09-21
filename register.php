<?php 
session_start();
include("connection.php");

if (isset($_POST['register'])) {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $password   = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $balance    = 0.00;

    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password) || empty($_FILES["profile_pic"]["name"])) {
        $error = "⚠️ Please fill in all required fields and upload a profile picture.";
    } elseif ($password !== $confirm_password) {
        $error = "❌ Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $profile_pic = "uploads/default.png";
        if (!empty($_FILES["profile_pic"]["name"])) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
            $targetFilePath = $targetDir . $fileName;

            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg','jpeg','png','gif'];

            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
                    $profile_pic = $targetFilePath;
                }
            } else {
                $error = "❌ Only JPG, JPEG, PNG, and GIF files are allowed.";
            }
        }

        if (!isset($error)) {
            $sql = "INSERT INTO users (first_name, last_name, username, email, phone, password, balance, profile_pic) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssds", $first_name, $last_name, $username, $email, $phone, $hashedPassword, $balance, $profile_pic);

            if ($stmt->execute()) {
                $success = true;
            } else {
                $error = "❌ Registration failed: " . $stmt->error;
            }

            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background:#e6f0ff; text-align:center; padding:40px; }
        .box { display:inline-block; background:white; padding:25px; border-radius:12px; box-shadow:0 6px 15px rgba(0,0,50,0.15); width:380px; border-top:5px solid #007bff; }
        input, button { margin:10px 0; padding:12px; width:100%; border:1px solid #99c2ff; border-radius:6px; font-size:14px; box-sizing:border-box; }
        input:focus { outline:none; border-color:#0056b3; box-shadow:0 0 4px rgba(0,86,179,0.5); }
        button { background:#007bff; color:white; border:none; cursor:pointer; font-size:16px; font-weight:bold; transition:0.3s; }
        button:hover:not(:disabled) { background:#0056b3; }
        button:disabled { background:#bbb; cursor:not-allowed; }
        .success { background:#cce5ff; color:#004085; padding:15px; border-radius:8px; margin-bottom:10px; border:1px solid #99c2ff; }
        .error { background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:10px; border:1px solid #f5c6cb; }
        a.login-btn { display:inline-block; margin-top:15px; padding:10px 15px; background:#0056b3; color:white; text-decoration:none; border-radius:5px; }
        a.login-btn:hover { background:#003d80; }
        .relative { position:relative; margin:10px 0; }
        .relative input { padding-right:40px; }
        .eye { position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:16px; color:#0056b3; }
        .rules { text-align:left; margin:10px 0; font-size:13px; background:#f0f8ff; padding:10px; border-radius:6px; border:1px solid #99c2ff; }
        .rules li { margin:4px 0; }
        .valid { color:#007bff; }
        .valid::before { content:"✔ "; }
        .invalid { color:#ff0000; }
        .invalid::before { content:"✖ "; }
        .input-valid { border:1px solid #007bff !important; }
        .input-invalid { border:1px solid red !important; }
    </style>
</head>
<body>
<div class="box">
    <h2 style="color:#0056b3;">Create Account</h2>

    <?php if (isset($success) && $success): ?>
        <div class="success">
            ✅ You have registered successfully!  
            Please <a href="login.php" class="login-btn">Login Here</a>
        </div>
    <?php else: ?>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

        <form action="" method="post" enctype="multipart/form-data" id="registerForm">
            <input type="text" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required><br>
            <input type="text" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required><br>
            <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required><br>
            <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required><br>
            <input type="text" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required><br>

            <div class="relative">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="eye" onclick="togglePassword('password')">👁</span>
            </div>

            <div class="rules">
                <ul>
                    <li id="length" class="invalid">At least 8 characters long</li>
                    <li id="uppercase" class="invalid">At least one uppercase letter</li>
                    <li id="lowercase" class="invalid">At least one lowercase letter</li>
                    <li id="special" class="invalid">At least one special character (!@#$%^&*)</li>
                </ul>
            </div>

            <div class="relative">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <span class="eye" onclick="togglePassword('confirm_password')">👁</span>
            </div>
            <p id="matchMsg" style="font-size:13px; color:red; display:none;">❌ Passwords do not match</p>

            <input type="file" name="profile_pic" accept="image/*" required><br>
            <button type="submit" name="register" id="registerBtn" disabled>Register</button>
        </form>
    <?php endif; ?>
</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}

const passwordInput = document.getElementById("password");
const confirmInput = document.getElementById("confirm_password");
const registerBtn = document.getElementById("registerBtn");
const matchMsg = document.getElementById("matchMsg");

function validatePassword() {
    const value = passwordInput.value;
    let valid = true;

    if (value.length >= 8) { document.getElementById("length").classList.replace("invalid","valid"); } else { document.getElementById("length").classList.replace("valid","invalid"); valid = false; }
    if (/[A-Z]/.test(value)) { document.getElementById("uppercase").classList.replace("invalid","valid"); } else { document.getElementById("uppercase").classList.replace("valid","invalid"); valid = false; }
    if (/[a-z]/.test(value)) { document.getElementById("lowercase").classList.replace("invalid","valid"); } else { document.getElementById("lowercase").classList.replace("valid","invalid"); valid = false; }
    if (/[^a-zA-Z0-9]/.test(value)) { document.getElementById("special").classList.replace("invalid","valid"); } else { document.getElementById("special").classList.replace("valid","invalid"); valid = false; }

    if (value === confirmInput.value && value.length > 0) {
        confirmInput.classList.add("input-valid");
        confirmInput.classList.remove("input-invalid");
        matchMsg.style.display = "none";
    } else {
        confirmInput.classList.add("input-invalid");
        confirmInput.classList.remove("input-valid");
        matchMsg.style.display = "block";
        valid = false;
    }

    if (valid) {
        passwordInput.classList.add("input-valid");
        passwordInput.classList.remove("input-invalid");
    } else {
        passwordInput.classList.add("input-invalid");
        passwordInput.classList.remove("input-valid");
    }

    registerBtn.disabled = !valid;
}

passwordInput.addEventListener("input", validatePassword);
confirmInput.addEventListener("input", validatePassword);
</script>
</body>
</html>
