<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Intro 2</title>
<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #004080, #0066cc);
        color: white;
        text-align: center;
    }
    h1 {
        font-size: 42px;
        margin-bottom: 20px;
        animation: fadeInDown 2s ease forwards;
    }
    p {
        font-size: 20px;
        max-width: 650px;
        margin: 10px auto 30px;
        line-height: 1.6;
        animation: fadeInUp 3s ease forwards;
    }
    a {
        display: inline-block;
        padding: 14px 35px;
        background: #ffcc00;
        color: #003366;
        text-decoration: none;
        font-size: 18px;
        font-weight: bold;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        transition: transform 0.3s ease, background 0.3s ease;
        animation: bounceIn 2.5s ease forwards, pulse 2s infinite 3s;
    }
    a:hover {
        background: #ffdb4d;
        transform: scale(1.1);
    }

    /* Animations */
    @keyframes fadeInDown {
        0% { opacity: 0; transform: translateY(-50px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(50px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes bounceIn {
        0% { transform: scale(0.5); opacity: 0; }
        60% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(1); }
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
</head>
<body>

    <h1>🚀 Ready to Take Control</h1>
    <p>
        You’re about to enter the heart of RefNet’s Admin Dashboard.  
        From here you’ll manage operations, empower users,  
        and guide RefNet to success with your leadership.  
    </p>
    <a href="admin_dashboard.php">Enter Dashboard ➡️</a>

</body>
</html>
