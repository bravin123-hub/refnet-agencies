<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("connection.php");

// Approve client if admin clicks approve
if (isset($_GET['approve'])) {
    $client_id = intval($_GET['approve']);
    $conn->query("UPDATE clients SET approved = 1 WHERE id = $client_id");

    // Give 5000 Ksh to the user who added this client
    $client = $conn->query("SELECT user_id FROM clients WHERE id = $client_id")->fetch_assoc();
    if ($client) {
        $conn->query("UPDATE users SET balance = balance + 5000 WHERE id = " . $client['user_id']);
    }

    header("Location: admin_dashboard.php?page=client_connections");
    exit();
}

// Reject client
if (isset($_GET['reject'])) {
    $client_id = intval($_GET['reject']);
    $conn->query("UPDATE clients SET approved = 0 WHERE id = $client_id");
    header("Location: admin_dashboard.php?page=client_connections");
    exit();
}

// Fetch all clients
$result = $conn->query("SELECT c.*, u.username 
                        FROM clients c 
                        LEFT JOIN users u ON c.user_id = u.id 
                        ORDER BY c.created_at DESC");
?>

<h2>🔗 Client Connections</h2>
<table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <tr style="background:#2c3e50; color:white;">
        <th>Client Name</th>
        <th>Email</th>
        <th>Connected By</th>
        <th>Date Added</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['client_name']) ?></td>
            <td><?= htmlspecialchars($row['client_email']) ?></td>
            <td><?= htmlspecialchars($row['username'] ?? '---') ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <?php if ($row['approved'] == 1): ?>
                    ✅ Approved
                <?php elseif ($row['approved'] === "0" || $row['approved'] == 0): ?>
                    ❌ Rejected
                <?php else: ?>
                    ⏳ Pending
                <?php endif; ?>
            </td>
            <td>
                <?php if ($row['approved'] != 1): ?>
                    <a href="admin_dashboard.php?page=client_connections&approve=<?= $row['id'] ?>" 
                       style="color:green; font-weight:bold;">✔ Approve</a> | 
                    <a href="admin_dashboard.php?page=client_connections&reject=<?= $row['id'] ?>" 
                       style="color:red; font-weight:bold;">✖ Reject</a>
                <?php else: ?>
                    ---
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
