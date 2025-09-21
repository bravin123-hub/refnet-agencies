<?php
session_start();

// ✅ Restrict to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("connection.php");

$message = "";

// ✅ Handle approve/reject actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if (in_array($action, ['approved','rejected'])) {
        $stmt = $conn->prepare("UPDATE loan_requests SET status=? WHERE id=?");
        $stmt->bind_param("si", $action, $id);
        if ($stmt->execute()) {
            $message = "✅ Loan request #$id marked as $action.";
        } else {
            $message = "❌ Database error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// ✅ Fetch all requests
$result = $conn->query("SELECT lr.*, u.first_name, u.email 
                        FROM loan_requests lr 
                        JOIN users u ON lr.user_id = u.id 
                        ORDER BY lr.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Loan Requests</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        h2 { text-align: center; }
        .msg { text-align: center; margin-bottom: 15px; font-weight: bold; color: green; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #2c3e50; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        a.btn { padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .approve { background: #27ae60; color: white; }
        .reject { background: #c0392b; color: white; }
        .pdf { background: #2980b9; color: white; }
    </style>
</head>
<body>
    <h2>📋 Loan Requests Management</h2>

    <?php if ($message): ?>
        <p class="msg"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Email</th>
            <th>Full Name</th>
            <th>ID Number</th>
            <th>ID Document</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['first_name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars($row['id_number']) ?></td>
                <td><a class="btn pdf" href="<?= $row['id_file'] ?>" target="_blank">📄 View PDF</a></td>
                <td><?= ucfirst($row['status']) ?></td>
                <td>
                    <?php if ($row['status'] === 'pending'): ?>
                        <a class="btn approve" href="?action=approved&id=<?= $row['id'] ?>">Approve</a>
                        <a class="btn reject" href="?action=rejected&id=<?= $row['id'] ?>">Reject</a>
                    <?php else: ?>
                        ✅ Already <?= ucfirst($row['status']) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
