<?php
include("connection.php");

$email = "admin@refnet.com";
$plain_password = "admin123"; // hii ndio password mpya
$hash = password_hash($plain_password, PASSWORD_BCRYPT);

// kagua kama admin yupo tayari
$sql = "SELECT * FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // update password
    $sql = "UPDATE users SET password=?, role='admin' WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hash, $email);
    $stmt->execute();
    echo "✅ Admin password updated.<br>";
} else {
    // insert admin
    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hash);
    $stmt->execute();
    echo "✅ Admin account created.<br>";
}

echo "Use Email: admin@refnet.com<br>Password: admin123";
