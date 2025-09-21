<?php
session_start();
include("connection.php");
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Intro to HTML</title>
<style>body{font-family:Arial;background:#ff6f61;color:white;text-align:center;padding:40px;}h1{font-size:36px;}p{max-width:800px;margin:20px auto;font-size:18px;}a{display:inline-block;padding:12px 25px;background:#ffd700;color:black;border-radius:6px;text-decoration:none;font-weight:bold;}</style>
</head>
<body>
<h1>🌐 Introduction to HTML</h1>
<p>HTML (HyperText Markup Language) structures every webpage. Learn to create headings, paragraphs, links, and images. It’s your first step to web development 🚀.</p>
<a href="website.php">⬅ Back to Hub</a>
</body>
</html>
