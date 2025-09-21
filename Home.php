<?php
session_start();
include("connection.php");

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch logged-in user details
$stmt = $conn->prepare("SELECT id, first_name, last_name, username, email, profile_pic, phone, balance, role 
                        FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Home</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background:#f4f6f9; 
            margin:0; 
            padding:40px; 
        }
        .profile { 
            background:white; 
            padding:25px; 
            border-radius:12px; 
            box-shadow:0 4px 12px rgba(0,0,0,0.1); 
            width:300px;        
            min-height:500px;   
            text-align:center;
            margin-left:40px; 
            margin-bottom:40px;
            display:inline-block;
            vertical-align:top;
        }
        .profile img { 
            width:180px; 
            height:240px;    
            border-radius:8px;  
            object-fit:cover; 
            margin-bottom:20px; 
            border:3px solid #ddd;
        }
        .profile h2 { 
            margin:10px 0; 
            font-size:20px; 
            color:#333; 
        }
        .profile p { 
            margin:8px 0; 
            font-size:15px; 
            color:#555; 
        }
    </style>
</head>
<body>

<!-- ✅ Logged-in user profile -->
<div class="profile">
    <img src="<?php echo htmlspecialchars($user['profile_pic'] ?: 'uploads/default.png'); ?>" alt="Profile Picture">
    <h2>Welcome <?php echo htmlspecialchars($user['first_name']); ?> <?php echo htmlspecialchars($user['last_name']); ?></h2>
    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
    <p><strong>Balance:</strong> KES <?php echo number_format($user['balance'],2); ?></p>
</div>

<?php if ($user['role'] === 'admin'): ?>
    <h1>All Users (Admin View)</h1>
    <?php
    $users = $conn->query("SELECT id, first_name, last_name, username, email, phone, balance, profile_pic 
                           FROM users ORDER BY id ASC");
    while ($u = $users->fetch_assoc()): ?>
        <div class="profile">
            <img src="<?php echo htmlspecialchars($u['profile_pic'] ?: 'uploads/default.png'); ?>" alt="Profile Picture">
            <h2><?php echo htmlspecialchars($u['first_name'].' '.$u['last_name']); ?></h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($u['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($u['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($u['phone']); ?></p>
            <p><strong>Balance:</strong> KES <?php echo number_format($u['balance'],2); ?></p>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

</body>
</html>
<?php
$conn->close();
?>
