<?php
session_start(); include("connection.php");
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Intro to MATLAB</title>
<style>body{font-family:Arial;background:#e74c3c;color:white;text-align:center;padding:40px;}h1{font-size:36px;}p{max-width:800px;margin:20px auto;font-size:18px;}a{padding:12px 25px;background:#ffd700;color:black;border-radius:6px;text-decoration:none;font-weight:bold;}</style></head>
<body>
<h1>📐 Introduction to MATLAB</h1>
<p>MATLAB is the go-to for engineers and scientists. It’s powerful for math, simulations, and modeling 🔬.</p>
<a href="website.php">⬅ Back to Hub</a>
</body></html>
