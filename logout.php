<?php
// ✅ Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Only log out if a user is logged in
if (isset($_SESSION['user_id'])) {
    // Optionally: you could log this logout action into a DB using $_SESSION['user_id']
    
    // ✅ Destroy session
    $_SESSION = []; // clear all session variables
    session_unset();
    session_destroy();
}

// ✅ Redirect back to login
header("Location: login.php");
exit();
