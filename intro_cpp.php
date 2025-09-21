<?php
session_start(); include("connection.php");
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Intro to C++</title>
<style>body{font-family:Arial;background:#7f8c8d;color:white;text-align:center;padding:40px;}h1{font-size:36px;}p{max-width:800px;margin:20px auto;font-size:18px;}a{padding:12px 25px;background:#ffd700;color:black;border-radius:6px;text-decoration:none;font-weight:bold;}</style></head>
<body>
<h1>⚙️ Introduction to C++</h1>
<p>C++ is powerful and efficient. It’s used in operating systems, games, and performance-heavy applications ⚡.</p>
<a href="website.php">⬅ Back to Hub</a>
</body></html>
