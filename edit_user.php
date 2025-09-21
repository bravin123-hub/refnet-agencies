<?php
session_start();
include("connection.php");

// ✅ Restrict to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No user ID provided.");
}

$user_id = intval($_GET['id']);

// ✅ Fetch user details
$stmt = $conn->prepare("SELECT id, first_name, last_name, username, email, phone, balance, role 
                        FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

// ✅ Update user on POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first = $_POST['first_name'];
    $last = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $balance = floatval($_POST['balance']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users 
                            SET first_name=?, last_name=?, username=?, email=?, phone=?, balance=?, role=? 
                            WHERE id=?");
    $stmt->bind_param("sssssdsi", $first, $last, $username, $email, $phone, $balance, $role, $user_id);
    if ($stmt->execute()) {
        header("Location: users.php?msg=User+updated+successfully");
        exit();
    } else {
        echo "Error updating user.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <style>
        body { font-family: Arial; background:#f4f6f9; padding:40px; }
        form { background:white; padding:25px; border-radius:12px; width:400px; margin:auto; 
               box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        input, select { width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px; }
        button { background:#2c3e50; color:white; padding:10px; border:none; border-radius:6px; cursor:pointer; }
        button:hover { background:#1a242f; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Edit User: <?php echo htmlspecialchars($user['username']); ?></h2>

<form method="POST">
    <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
    <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
    <input type="number" step="0.01" name="balance" value="<?php echo htmlspecialchars($user['balance']); ?>" required>
    
    <select name="role" required>
        <option value="user" <?php if($user['role']=="user") echo "selected"; ?>>User</option>
        <option value="admin" <?php if($user['role']=="admin") echo "selected"; ?>>Admin</option>
    </select>

    <button type="submit">Update User</button>
</form>

</body>
</html>
