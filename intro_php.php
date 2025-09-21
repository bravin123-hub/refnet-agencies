<?php
session_start(); include("connection.php");
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Intro to PHP</title>
<style>body{font-family:Arial;background:#6c3483;color:white;text-align:center;padding:40px;}h1{font-size:36px;}p{max-width:800px;margin:20px auto;font-size:18px;}a{padding:12px 25px;background:#ffd700;color:black;border-radius:6px;text-decoration:none;font-weight:bold;}</style></head>
<body>
<h1>🐘 Introduction to PHP</h1>
<p>PHP is the backend language of the web. Power login systems, blogs, e-commerce, and APIs with PHP 🔧.</p>
<a href="website.php">⬅ Back to Hub</a>
</body></html>
