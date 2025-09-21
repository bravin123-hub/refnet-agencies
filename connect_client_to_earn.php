<?php
session_start();
include("connection.php");

// ✅ Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']); 
$msg = "";

// ✅ Handle new client form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_client'])) {
    $client_name  = trim($_POST['client_name']);
    $client_email = trim($_POST['client_email']);

    if ($client_name !== "" && $client_email !== "") {
        // Check for duplicates
        $check = $conn->prepare("SELECT id FROM clients WHERE user_id = ? AND client_email = ?");
        $check->bind_param("is", $user_id, $client_email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = "⚠ This client is already connected.";
        } else {
            // Insert new client with default `approved = 0`
            $stmt = $conn->prepare("INSERT INTO clients (user_id, client_name, client_email, approved, created_at) VALUES (?, ?, ?, 0, NOW())");
            if (!$stmt) {
                $msg = "❌ SQL Error: " . htmlspecialchars($conn->error);
            } else {
                $stmt->bind_param("iss", $user_id, $client_name, $client_email);
                if ($stmt->execute()) {
                    $msg = "🎉 Client submitted successfully! Waiting for admin approval ✅";
                } else {
                    $msg = "❌ Error: " . htmlspecialchars($stmt->error);
                }
            }
        }
        $check->close();
    } else {
        $msg = "⚠ Please enter all fields.";
    }
}

// ✅ Fetch user clients safely
$result = $conn->prepare("SELECT client_name, client_email, created_at, approved FROM clients WHERE user_id = ? ORDER BY created_at DESC");
$result->bind_param("i", $user_id);
$result->execute();
$result_data = $result->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>👥 Connect Client to Earn</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #2c3e50, #3498db);
        color: white;
        padding: 20px;
        text-align: center;
    }
    h1 { margin-bottom: 10px; }
    .msg { margin: 15px auto; padding: 10px; background: rgba(0,0,0,0.3); border-radius: 6px; }
    form {
        margin: 20px auto;
        max-width: 400px;
        background: rgba(255,255,255,0.1);
        padding: 20px;
        border-radius: 10px;
    }
    input {
        width: 90%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 6px;
        border: none;
    }
    button {
        padding: 12px 20px;
        background: #ffd700;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }
    button:hover { background: #ffcc33; }
    table {
        margin: 20px auto;
        border-collapse: collapse;
        width: 80%;
        background: rgba(255,255,255,0.1);
    }
    th, td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        color: white;
    }
    th { background: rgba(0,0,0,0.4); }
    .approved { color: lightgreen; font-weight: bold; }
    .pending { color: orange; font-weight: bold; }
</style>
</head>
<body>
    <h1>👥 Connect Clients to Earn Rewards</h1>
    <p>Add new clients below. Each client earns you <b>Ksh 5000</b> once approved by admin 🎁</p>

    <?php if ($msg) echo "<div class='msg'>".htmlspecialchars($msg)."</div>"; ?>

    <form method="post">
        <input type="text" name="client_name" placeholder="Client Name" required>
        <input type="email" name="client_email" placeholder="Client Email" required>
        <button type="submit" name="add_client">➕ Connect Client</button>
    </form>

    <h2>📋 Your Connected Clients</h2>
    <table>
        <tr><th>Client Name</th><th>Email</th><th>Date Added</th><th>Status</th></tr>
        <?php if ($result_data->num_rows > 0): ?>
            <?php while ($row = $result_data->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['client_name']) ?></td>
                    <td><?= htmlspecialchars($row['client_email']) ?></td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                    <td>
                        <?php if ($row['approved'] == 1): ?>
                            <span class="approved">✅ Approved</span>
                        <?php else: ?>
                            <span class="pending">⏳ Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No clients connected yet.</td></tr>
        <?php endif; ?>
    </table>

    <a href="website.php" style="color:#ffd700; font-weight:bold;">⬅ Back to Training Hub</a>
</body>
</html>
