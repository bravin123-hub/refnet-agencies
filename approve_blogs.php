<?php
// ================= SAFE SESSION START =================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ================= DATABASE CONNECTION =================
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "refnet";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ================= ACCESS CONTROL =================
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ================= HANDLE APPROVAL/REJECTION =================
if (isset($_GET['approve']) || isset($_GET['reject'])) {
    $status = isset($_GET['approve']) ? 'approved' : 'rejected';
    $blog_id = intval($_GET['approve'] ?? $_GET['reject']);

    // Update blog status
    $stmt = $conn->prepare("UPDATE blogs SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $status, $blog_id);
        $stmt->execute();
        $stmt->close();
    }

    // Reward user if approved
    if ($status === 'approved') {
        // Add 100 KES to the user's balance
        $stmt = $conn->prepare("
            UPDATE users u
            JOIN blogs b ON u.id = b.user_id
            SET u.balance = u.balance + 100
            WHERE b.id = ?
        ");
        if ($stmt) {
            $stmt->bind_param("i", $blog_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    $msg = $status === 'approved' ? "✅ Blog approved and user rewarded KES 100" : "❌ Blog rejected successfully";
    header("Location: approve_blogs.php?msg=" . urlencode($msg));
    exit();
}

// ================= FETCH PENDING BLOGS =================
$sql = "SELECT b.id, b.title, b.content, b.user_id, b.created_at, u.email 
        FROM blogs b
        JOIN users u ON b.user_id = u.id
        WHERE b.status = 'pending'
        ORDER BY b.created_at DESC";

$result = $conn->query($sql);
if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Approve Blogs - RefNet Agencies</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin:0; padding:0; }
        .container { width: 80%; margin: auto; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .blog-card { background: white; padding: 15px; margin: 10px 0; border-radius: 8px; box-shadow: 0px 2px 6px rgba(0,0,0,0.1); }
        .actions a { margin-right: 10px; text-decoration: none; padding: 6px 12px; border-radius: 6px; }
        .approve { background: #28a745; color: white; }
        .reject { background: #dc3545; color: white; }
        .msg { padding: 10px; background: #d4edda; color: #155724; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        .empty { text-align: center; padding: 20px; background: #fff3cd; color: #856404; border-radius: 6px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Pending Blogs for Approval</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="blog-card">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 300))); ?>...</p>
                <small>By <?php echo htmlspecialchars($row['email']); ?> | Posted on: <?php echo $row['created_at']; ?></small>
                <div class="actions">
                    <a href="approve_blogs.php?approve=<?php echo $row['id']; ?>" class="approve">✅ Approve</a>
                    <a href="approve_blogs.php?reject=<?php echo $row['id']; ?>" class="reject">❌ Reject</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty">✅ No pending blogs right now.</div>
    <?php endif; ?>
</div>
</body>
</html>
