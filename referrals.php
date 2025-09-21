<?php
session_start();
require_once 'includes/connection.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's referral code
$stmt = $conn->prepare("SELECT referral_code FROM users WHERE id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$stmt->bind_result($my_referral_code);
$stmt->fetch();
$stmt->close();

// Build referral link
$base_url = "http://localhost/refnet_agencies/register.php?ref=".urlencode($my_referral_code);

// Fetch all users referred by this user
$stmt = $conn->prepare("SELECT first_name,last_name,phone,profile_pic,created_at FROM users WHERE referred_by=? ORDER BY created_at DESC");
$stmt->bind_param("s",$my_referral_code);
$stmt->execute();
$result = $stmt->get_result();
$referrals = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>My Referrals - RefNet</title>
<style>
body{font-family:Arial;background:#f4f4f4;}
header{background:#007BFF;color:white;padding:15px;text-align:center;}
table{width:90%;margin:20px auto;border-collapse:collapse;background:#fff;}
th,td{padding:10px;border:1px solid #ddd;text-align:left;vertical-align: middle;}
th{background:#007BFF;color:white;}
tr:nth-child(even){background:#f9f9f9;}
p.center{text-align:center;}
.ref-link{background:#e9ecef;padding:10px;display:inline-block;border-radius:5px;}
img.profile{width:50px;height:50px;border-radius:50%;object-fit:cover;}
</style>
</head>
<body>
<header>
<h1>My Referrals</h1>
<p>Your referral code: <strong><?php echo htmlspecialchars($my_referral_code); ?></strong></p>
<p>Your referral link:  
<span class="ref-link"><a href="<?php echo htmlspecialchars($base_url); ?>" target="_blank"><?php echo htmlspecialchars($base_url); ?></a></span>
</p>
</header>

<?php if(count($referrals)===0): ?>
<p class="center">You haven't referred anyone yet.</p>
<?php else: ?>
<table>
<tr><th>Profile</th><th>Name</th><th>Phone</th><th>Date Joined</th></tr>
<?php foreach($referrals as $r): ?>
<tr>
<td><img src="uploads/<?php echo htmlspecialchars($r['profile_pic'] ?: 'default.png'); ?>" class="profile"></td>
<td><?php echo htmlspecialchars($r['first_name'].' '.$r['last_name']); ?></td>
<td><?php echo htmlspecialchars($r['phone']); ?></td>
<td><?php echo htmlspecialchars(date("d M Y",strtotime($r['created_at']))); ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endif; ?>

<p class="center"><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
