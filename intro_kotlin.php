<?php
session_start(); include("connection.php");
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Intro to Kotlin</title>
<style>body{font-family:Arial;background:#8e44ad;color:white;text-align:center;padding:40px;}h1{font-size:36px;}p{max-width:800px;margin:20px auto;font-size:18px;}a{padding:12px 25px;background:#ffd700;color:black;border-radius:6px;text-decoration:none;font-weight:bold;}</style></head>
<body>
<h1>🤖 Introduction to Kotlin</h1>
<p>Kotlin is Google’s preferred Android language. It’s simpler, safer, and interoperates with Java seamlessly 📲.</p>
<a href="website.php">⬅ Back to Hub</a>
</body></html>
