<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>RefNet Admin Dashboard</title>
    <style>
        body { 
            margin:0; 
            font-family: Arial, sans-serif; 
            display:flex; 
            height:100vh; 
        }
        .sidebar {
            width: 240px;
            background: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align:center;
            margin-bottom: 20px;
            color: #1abc9c;
        }
        .sidebar a {
            padding: 14px 20px;
            text-decoration: none;
            color: white;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover { 
            background: #1abc9c; 
        }
        .main {
            flex: 1;
            padding: 20px;
            background: #f4f4f4;
            overflow-y: auto;
        }
        h2 { 
            color:#2c3e50; 
        }
        .card {
            background:white; 
            padding:20px; 
            margin:15px 0; 
            border-radius:8px; 
            box-shadow:0 2px 5px rgba(0,0,0,0.1);
            transition:0.3s;
        }
        .card:hover {
            background:#f1fdfb;
            transform:scale(1.02);
        }
        .card a {
            text-decoration:none; 
            color:#2c3e50; 
            font-size:18px;
            display:block;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>⚙️ Admin Panel</h2>
        <a href="admin_dashboard.php?page=home">🏠 Dashboard Home</a>
        <a href="admin_dashboard.php?page=users">👥 Users</a>
        <a href="admin_dashboard.php?page=blogs">📝 Approve Blogs</a>
        <a href="admin_dashboard.php?page=spins">🎡 User Spins</a>
        <a href="admin_dashboard.php?page=vpn_plans">🔒 VPN Plans</a>
        <a href="admin_dashboard.php?page=vpn_orders">🛒 VPN Purchases</a>
        <a href="admin_dashboard.php?page=reports">📊 Reports</a>
        <a href="admin_dashboard.php?page=client_connections">🔗 Client Connections</a>
        <!-- ✅ New AZU AI Assistant -->
        <a href="admin_dashboard.php?page=azu">🤖 AZU AI Assistant</a>
        <a href="azu_teach.php">📚 Teach AZU</a>
        <a href="calculator.php">🧮 Calculator</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main">
        <?php
        $page = $_GET['page'] ?? 'home';
        switch ($page) {
            case 'users':
                include("users.php");
                break;
            case 'blogs':
                include("approve_blogs.php");
                break;
            case 'spins':
                include("admin_spins.php");
                break;
            case 'vpn_plans':
                include("admin_vpn_plans.php");
                break;
            case 'vpn_orders':
                include("admin_vpn_orders.php");
                break;
            case 'reports':
                include("admin_reports.php");
                break;
            case 'client_connections':
                include("client_connections.php"); // ✅ Client requests page
                break;
            case 'azu':
                include("azu.php"); // ✅ AI assistant page
                break;
            default:
                echo "<h2>Welcome, Admin {$_SESSION['name']} 👋</h2>
                      <p>Use the sidebar to navigate the admin features.</p>";
        }
        ?>
    </div>

</body>
</html>
