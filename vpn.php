<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>VPN Services</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 12px; text-align: center; }
        th { background: #34495e; color: white; }
        a.buy-btn {
            display: inline-block;
            padding: 8px 12px;
            background: #1abc9c;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        a.buy-btn:hover { background: #16a085; }
    </style>
</head>
<body>
    <h2>VPN Services</h2>
    <p>Browse securely and privately with RefNet VPN packages. Select a plan and pay with M-PESA to get your VPN account instantly.</p>

    <table>
        <tr>
            <th>Plan</th>
            <th>Duration</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>Weekly Unlimited</td>
            <td>7 Days</td>
            <td>KES 40</td>
            <td><a href="purchase_vpn.php?plan=weekly" class="buy-btn">Buy Now</a></td>
        </tr>
        <tr>
            <td>Monthly Unlimited</td>
            <td>30 Days</td>
            <td>KES 150</td>
            <td><a href="purchase_vpn.php?plan=monthly" class="buy-btn">Buy Now</a></td>
        </tr>
        <tr>
            <td>Premium Unlimited</td>
            <td>90 Days</td>
            <td>KES 400</td>
            <td><a href="purchase_vpn.php?plan=premium" class="buy-btn">Buy Now</a></td>
        </tr>
    </table>
</body>
</html>
