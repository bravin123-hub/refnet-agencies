<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Billing History - RefNet Agencies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ecf0f1;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 40px auto;
            max-width: 800px;
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: #fff;
        }

        .back {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .back:hover {
            background-color: #34495e;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Billing History</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Transaction ID</th>
                <th>Amount (KES)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <!-- Sample Data -->
            <tr>
                <td>1</td>
                <td>2025-08-01</td>
                <td>TXN123456789</td>
                <td>500</td>
                <td>Paid</td>
            </tr>
            <tr>
                <td>2</td>
                <td>2025-07-25</td>
                <td>TXN987654321</td>
                <td>300</td>
                <td>Pending</td>
            </tr>
            <tr>
                <td>3</td>
                <td>2025-07-10</td>
                <td>TXN456789123</td>
                <td>1000</td>
                <td>Paid</td>
            </tr>
        </tbody>
    </table>

    <a class="back" href="dashboard.php">← Back to Dashboard</a>
</div>

</body>
</html>
