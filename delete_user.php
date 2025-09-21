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

// ✅ Prevent admin deleting themselves
if ($user_id == $_SESSION['user_id']) {
    die("You cannot delete your own account.");
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: users.php?msg=User+deleted+successfully");
    exit();
} else {
    echo "Error deleting user.";
}

$stmt->close();
$conn->close();
?>
