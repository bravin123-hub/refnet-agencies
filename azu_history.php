<?php
include("connection.php");
session_start();

$result = $conn->query("SELECT * FROM azu_conversations WHERE user_id={$_SESSION['user_id']} ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>AZU Conversation History</title>
<style>
body { font-family: Arial; background:#f4f4f4; padding:20px; }
.box { background:white; padding:10px; margin:10px 0; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
.admin { color:#2c3e50; font-weight:bold; }
.azu { color:#1abc9c; font-weight:bold; }
</style>
</head>
<body>
<h2>🗂️ AZU Conversation History</h2>
<?php while($row=$result->fetch_assoc()): ?>
<div class="box">
    <span class="<?= $row['role']; ?>"><?= ucfirst($row['role']); ?>:</span>
    <?= htmlspecialchars($row['message']); ?><br>
    <small>🕒 <?= $row['created_at']; ?></small>
</div>
<?php endwhile; ?>
</body>
</html>
