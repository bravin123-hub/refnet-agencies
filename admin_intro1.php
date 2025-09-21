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
<title>Admin Intro 1</title>
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
        background: linear-gradient(135deg, #005f99, #007bff);
        color: white;
        text-align: center;
        overflow: hidden;
    }
    h1 {
        font-size: 48px;
        margin-bottom: 20px;
        animation: fadeInDown 2s ease forwards;
    }
    p {
        font-size: 20px;
        max-width: 600px;
        margin: 10px auto 30px;
        line-height: 1.6;
        animation: fadeInUp 3s ease forwards;
    }
    a {
        display: inline-block;
        padding: 14px 30px;
        background: #1abc9c;
        color: white;
        text-decoration: none;
        font-size: 18px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        transition: transform 0.3s ease, background 0.3s ease;
        animation: bounceIn 2.5s ease forwards;
    }
    a:hover {
        background: #16a085;
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
</style>
</head>
<body>

    <h1>👋 Welcome Admin</h1>
    <p>
        You have successfully logged into the RefNet Admin Panel.  
        Here you can manage users, approve blogs, review spins,  
        and monitor VPN services and reports.  
    </p>
    <a href="admin_intro2.php">Continue ➡️</a>

</body>
</html>
