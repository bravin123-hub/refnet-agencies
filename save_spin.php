<?php
session_start();
include("connection.php");

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo "Not logged in";
    exit();
}

$email = $_SESSION['email'];
$points = $_POST['points'] ?? '';
$spin_type = $_POST['spin_type'] ?? '';

// Extract numeric amount from prize (e.g. "KES 200" → 200)
$amount = 0;
if (preg_match('/(\d+)/', $points, $match)) {
    $amount = (int)$match[1];
}

// 1. Record spin history
$stmt = $conn->prepare("INSERT INTO spin_history (email, spin_type, prize, spin_date) VALUES (?, ?, ?, NOW())");
$stmt->bind_param("sss", $email, $spin_type, $points);
$stmt->execute();
$stmt->close();

// 2. Update user balance
if ($amount > 0) {
    $stmt2 = $conn->prepare("UPDATE users SET balance = balance + ? WHERE email = ?");
    $stmt2->bind_param("ds", $amount, $email);
    $stmt2->execute();
    $stmt2->close();
}

$conn->close();

echo "success";
?>
