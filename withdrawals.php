<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'connection.php'; // make sure this path is correct

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// helper: prepare and die with useful error (dev-time)
function prepare_or_die($conn, $sql) {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Database prepare failed: " . htmlspecialchars($conn->error));
    }
    return $stmt;
}

// ✅ Fetch user balance
$stmt = prepare_or_die($conn, "SELECT balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($balance);
if (!$stmt->fetch()) {
    die("User not found. Please log in again.");
}
$stmt->close();

$msg = "";

// ✅ Handle withdrawal request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount'] ?? 0);
    $method = $_POST['method'] ?? '';

    if ($amount < 500) {
        $msg = "<p style='color:red;'>Minimum withdrawal amount is KES 500.</p>";
    } elseif ($amount > $balance) {
        $msg = "<p style='color:red;'>Insufficient balance. Your current balance is KES " . number_format($balance,2) . ".</p>";
    } elseif (empty($method)) {
        $msg = "<p style='color:red;'>Please select a withdrawal method.</p>";
    } else {
        // Insert withdrawal request
        $stmt = prepare_or_die($conn, "INSERT INTO withdrawals (user_id, amount, method, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("ids", $user_id, $amount, $method);
        if ($stmt->execute()) {
            // Update balance
            $stmt2 = prepare_or_die($conn, "UPDATE users SET balance = balance - ? WHERE id = ?");
            $stmt2->bind_param("di", $amount, $user_id);
            $stmt2->execute();
            $stmt2->close();

            $balance -= $amount;
            $msg = "<p style='color:green;'>✅ Withdrawal request of KES $amount submitted successfully!</p>";
        } else {
            $msg = "<p style='color:red;'>❌ Failed to process withdrawal.</p>";
        }
        $stmt->close();
    }
}

// ✅ Fetch previous withdrawals
$stmt = prepare_or_die($conn, "SELECT amount, method, status, created_at FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$withdrawals = [];
while ($row = $result->fetch_assoc()) {
    $withdrawals[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Withdraw Funds - RefNet</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; text-align: center; padding: 40px; }
        .withdraw-box { background: white; padding: 30px; border-radius: 12px; max-width: 450px; margin: auto; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        h2 { color: #2c3e50; margin-bottom: 15px; }
        .balance { font-size: 18px; margin-bottom: 15px; }
        input, select { width: 90%; padding: 12px; margin: 10px 0; border-radius: 6px; border: 1px solid #ccc; font-size: 16px; }
        button { background: #2ecc71; color: white; padding: 12px 25px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; transition: background 0.2s, transform 0.2s; }
        button:hover { background: #27ae60; transform: scale(1.03); }
        .msg { margin-bottom: 15px; font-size: 16px; }
        .withdrawals-list { margin-top: 30px; text-align: left; max-width: 600px; margin-left: auto; margin-right: auto; }
        .withdrawals-list table { width: 100%; border-collapse: collapse; }
        .withdrawals-list th, .withdrawals-list td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        .withdrawals-list th { background-color: #2c3e50; color: white; }
    </style>
</head>
<body>

<div class="withdraw-box">
    <h2>💸 Withdraw Funds</h2>
    <p class="balance"><strong>Available Balance:</strong> KES <?php echo number_format($balance, 2); ?></p>
    <?php echo $msg; ?>

    <form method="POST">
        <input type="number" name="amount" step="0.01" placeholder="Enter amount to withdraw" min="500" required>
        <select name="method" required>
            <option value="">Select Withdrawal Method</option>
            <option value="M-PESA">M-PESA</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="PayPal">PayPal</option>
        </select>
        <button type="submit">Request Withdrawal</button>
    </form>
</div>

<div class="withdrawals-list">
    <h3>Previous Withdrawals</h3>
    <?php if (empty($withdrawals)): ?>
        <p>No withdrawals yet.</p>
    <?php else: ?>
        <table>
            <tr><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr>
            <?php foreach ($withdrawals as $w): ?>
                <tr>
                    <td>KES <?php echo number_format($w['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($w['method']); ?></td>
                    <td><?php echo ucfirst($w['status']); ?></td>
                    <td><?php echo htmlspecialchars($w['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<p><a href="Home.php">Home</a></p>
</body>
</html>
