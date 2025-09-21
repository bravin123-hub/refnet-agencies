<?php
// ================= SAFE SESSION START =================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ================= ACCESS CONTROL =================
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// ================= DATABASE CONNECTION =================
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "refnet_agencies";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ================= HANDLE APPROVAL/REJECTION =================
if (isset($_GET['approve']) || isset($_GET['reject'])) {
    $status = isset($_GET['approve']) ? 'approved' : 'rejected';
    $id = intval($_GET['approve'] ?? $_GET['reject']);

    $stmt = $conn->prepare("UPDATE blogs SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    $msg = $status === 'approved' ? "✅ Blog approved successfully" : "❌ Blog rejected successfully";
    header("Location: approve_blogs.php?msg=" . urlencode($msg));
    exit();
}

// ================= FUNCTION TO FETCH BLOGS =================
function fetch_blogs($conn, $status) {
    $stmt = $conn->prepare(
        "SELECT b.id, b.title, b.content, b.created_at, u.email 
         FROM blogs b 
         JOIN users u ON b.author_id = u.id 
         WHERE b.status = ? 
         ORDER BY b.created_at DESC"
    );
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $blogs = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $blogs;
}

// ================= FETCH BLOGS =================
$pending_blogs = fetch_blogs($conn, 'pending');
$approved_blogs = fetch_blogs($conn, 'approved');
$rejected_blogs = fetch_blogs($conn, 'rejected');

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Approve Blogs - RefNet Agencies</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7f7f7; margin:0; padding:0; }
        .container { width: 90%; margin: auto; padding: 20px; }
        h2 { text-align: center; margin: 20px 0; }
        .blog-card { background: white; padding: 15px; margin: 10px 0; border-radius: 8px; box-shadow: 0px 2px 6px rgba(0,0,0,0.1); }
        .actions a { margin-right: 10px; text-decoration: none; padding: 6px 12px; border-radius: 6px; }
        .approve { background: #28a745; color: white; }
        .reject { background: #dc3545; color: white; }
        .msg { padding: 10px; background: #d4edda; color: #155724; margin-bottom: 15px; border-radius: 5px; text-align: center; }
        small { display: block; margin-top: 5px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
<div class="container">
    <h2>Pending Blogs for Approval</h2>

    <?php if (!empty($_GET['msg'])): ?>
        <div class="msg"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <?php if (!empty($pending_blogs)): ?>
        <?php foreach ($pending_blogs as $row): ?>
            <div class="blog-card">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 300))); ?>...</p>
                <small>✍️ Author: <?php echo htmlspecialchars($row['email']); ?> | 📅 <?php echo $row['created_at']; ?></small>
                <div class="actions">
                    <a href="approve_blogs.php?approve=<?php echo $row['id']; ?>" class="approve">✅ Approve</a>
                    <a href="approve_blogs.php?reject=<?php echo $row['id']; ?>" class="reject">❌ Reject</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">✅ No pending blogs right now.</p>
    <?php endif; ?>

    <!-- Approved and Rejected Blogs -->
    <?php
    $sections = [
        'Approved Blogs' => $approved_blogs,
        'Rejected Blogs' => $rejected_blogs
    ];
    foreach ($sections as $title => $blogs): ?>
        <h2><?php echo $title === 'Approved Blogs' ? '✅ ' . $title : '❌ ' . $title; ?></h2>
        <?php if (!empty($blogs)): ?>
            <table>
                <tr><th>Title</th><th>Author</th><th>Date</th></tr>
                <?php foreach ($blogs as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align:center;">No <?php echo strtolower($title); ?> yet.</p>
        <?php endif; ?>
    <?php endforeach; ?>

</div>
</body>
</html>
