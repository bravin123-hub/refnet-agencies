<?php
session_start();
include("connection.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

$user_id = $_SESSION['user_id'];

// Fetch current balance
$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$balance = $user['balance'];
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $amount = floatval($_POST['amount']);
    $method = $_POST['method'];

    if ($amount > 0 && !empty($method)) {
        // Insert deposit and update user's balance immediately
        $stmt = $conn->prepare("INSERT INTO deposits (user_id, amount, method, status) VALUES (?, ?, ?, 'completed')");
        $stmt->bind_param("ids", $user_id, $amount, $method);

        if ($stmt->execute()) {
            $stmt2 = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt2->bind_param("di", $amount, $user_id);
            $stmt2->execute();
            $stmt2->close();

            $balance += $amount; // update local balance
            $message = "<p class='msg-success'>✅ Deposit of KES " . number_format($amount,2) . " successful! New balance: KES " . number_format($balance,2) . "</p>";
        } else {
            $message = "<p class='msg-error'>❌ Error: Could not process your deposit.</p>";
        }
        $stmt->close();
    } else {
        $message = "<p class='msg-error'>Please enter a valid amount and method.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Deposit Funds - RefNet</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .deposit-container {
            background: #fff;
            width: 400px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            text-align: center;
        }

        .deposit-container h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .balance {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: bold;
            color: #16a085;
        }

        input, select {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            width: 95%;
            padding: 12px;
            margin-top: 15px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            background: #3498db;
            color: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .msg-success {
            color: #27ae60;
            background: #d4edda;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .msg-error {
            color: #c0392b;
            background: #f8d7da;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="deposit-container">
    <h2>💰 Deposit Funds</h2>
    <p class="balance">Current Balance: KES <?php echo number_format($balance, 2); ?></p>

    <?php echo $message; ?>

    <form method="POST">
        <label>Amount (KES)</label>
        <input type="number" step="0.01" name="amount" placeholder="Enter amount to deposit" min="1" required>

        <label>Payment Method</label>
        <select name="method" required>
            <option value="">-- Select Method --</option>
            <option value="M-PESA">M-PESA</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Airtel Money">Airtel Money</option>
        </select>

        <button type="submit">Submit Deposit</button>
    </form>
</div>

</body>
</html>
