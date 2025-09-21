<?php
session_start();
include("connection.php");

// ✅ Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch logged-in user details safely
$sql = "SELECT first_name, last_name, username, email, 
               COALESCE(phone,'Not set') AS phone,
               COALESCE(balance,0.00) AS balance,
               profile_pic,
               referral_code
        FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    session_destroy();
    header("Location: login.php?error=UserNotFound");
    exit();
}

// ✅ Build referral link
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
          . "://{$_SERVER['HTTP_HOST']}/refnet_agencies/register.php?ref=" 
          . urlencode($user['referral_code']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>RefNet Dashboard</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
/* Reset & base */
* { margin:0; padding:0; box-sizing:border-box; }
html,body { height:100%; }
body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; display:flex; flex-direction:column; min-height:100vh; background-color:#f4f6f9; }

/* Topbar */
.topbar {
    background: linear-gradient(90deg,#2c3e50,#1abc9c);
    color:white;
    padding:12px 16px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    font-size:18px;
    font-weight:700;
    letter-spacing:0.6px;
    box-shadow:0 2px 8px rgba(0,0,0,0.12);
    z-index:50;
}

/* Hamburger (3 lines) */
.hamburger {
    width:36px;
    height:28px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    cursor:pointer;
    margin-right:10px;
}
.hamburger span {
    display:block;
    height:3px;
    background:white;
    border-radius:3px;
    transition:transform .2s ease, opacity .2s ease;
}

/* user info on topbar */
.top-left { display:flex; align-items:center; gap:8px; }
.top-title { display:flex; align-items:center; gap:10px; font-size:18px; font-weight:700; color:#fff; }
.user-info { display:flex; align-items:center; gap:10px; font-size:13px; color:#fff; }
.user-info img { width:38px; height:38px; border-radius:50%; border:2px solid rgba(255,255,255,0.25); object-fit:cover; }

/* Layout */
.container { display:flex; flex:1; height: calc(100vh - 56px); overflow:hidden; }

/* Sidebar */
.sidebar {
    width:240px;
    min-width:240px;
    background-color:#34495e;
    color:white;
    display:flex;
    flex-direction:column;
    padding-top:18px;
    box-shadow:2px 0 6px rgba(0,0,0,0.12);
    overflow-y:auto;
    transition: all .28s ease;
    z-index:40;
}
.sidebar a { padding:12px 18px; text-decoration:none; color:white; display:block; cursor:pointer; transition: all .2s ease; }
.sidebar a:hover { background-color:#1abc9c; padding-left:24px; }
.sidebar .dropdown { position:relative; }
.sidebar .dropdown-content { display:none; flex-direction:column; background-color:#3c5a72; }
.sidebar .dropdown:hover .dropdown-content { display:flex; }

/* collapsed (hidden) sidebar */
.sidebar.collapsed {
    width:0;
    min-width:0;
    padding:0;
    overflow:hidden;
}

/* main area with iframe */
.main-content { flex:1; background:transparent; overflow:hidden; }
iframe { width:100%; height:100%; border:none; background:white; }

/* overlay for mobile */
.overlay {
    display:none;
    position:fixed;
    inset:0;
    background: rgba(0,0,0,0.45);
    z-index:35;
}

/* decorative bubbles */
.bubble { position:absolute; top:50px; border-radius:50%; opacity:0.7; pointer-events:none; animation:fall linear forwards; z-index:10; }
@keyframes fall { to { transform: translateY(100vh); opacity:0; } }

/* responsive behavior */
@media (max-width: 900px) {
    .sidebar { position:fixed; left:-240px; top:0; height:100vh; }
    .sidebar.open { left:0; }
    .overlay.show { display:block; }
    .hamburger { display:flex; }
}
@media (min-width: 901px) {
    .hamburger { display:flex; }
}
</style>
</head>
<body>

<div class="topbar">
    <div class="top-left">
        <!-- Hamburger: 3 lines -->
        <div class="hamburger" id="hamburgerBtn" onclick="toggleSidebar()">
            <span></span><span></span><span></span>
        </div>

        <div class="top-title">🚀 RefNet Dashboard</div>
    </div>

    <div class="user-info" title="<?php echo htmlspecialchars($user['email']); ?>">
        <img src="<?php echo htmlspecialchars($user['profile_pic'] ?? 'default.png'); ?>" alt="Profile">
        <div style="line-height:1.05;">
            <div style="font-weight:700;"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></div>
            <div style="font-size:11px; opacity:0.95;"><?php echo htmlspecialchars($user['email']); ?></div>
            <div style="font-size:11px; opacity:0.95;">Referral: <a href="<?php echo htmlspecialchars($base_url); ?>" target="_blank" style="color:#fff;"><?php echo htmlspecialchars($user['referral_code']); ?></a></div>
        </div>
    </div>
</div>

<div class="container">
    <nav class="sidebar" id="sidebar">
        <a onclick="loadPage('home')">🏠 Home</a>

        <div class="dropdown">
            <a>💰 Billings ▾</a>
            <div class="dropdown-content">
                <a onclick="loadPage('withdrawals')">Withdrawals</a>
                <a onclick="loadPage('deposit')">Deposit</a>
            </div>
        </div>

        <a onclick="loadPage('referrals')">🔗 Referrals</a>
        <a onclick="loadPage('write_blog')">✍️ Write Blog</a>
        <a onclick="loadPage('blogs')">📚 My Blogs</a>
        <a onclick="loadPage('academics')">🎓 Academics</a>

        <div class="dropdown">
            <a>💸 Loans ▾</a>
            <div class="dropdown-content">
                <a onclick="loadPage('request_loan')">Request Loan</a>
                <a onclick="loadPage('repay_loan')">Repay Loan</a>
                <a onclick="loadPage('my_loans')">My Loans</a>
            </div>
        </div>

        <div class="dropdown">
            <a>🎡 Spinning Wheel ▾</a>
            <div class="dropdown-content">
                <a onclick="loadPage('register_spin')">🆓 Register Spin</a>
                <a onclick="loadPage('money_spin')">💰 Money Spin</a>
                <a onclick="loadPage('weekly_spin')">🗓️ Weekly Spin</a>
                <a onclick="loadPage('spin_history')">📜 Spin History</a>
            </div>
        </div>

        <a onclick="loadPage('vpn')">🔒 VPN Services</a>
        <a onclick="loadPage('website')">🌐 Website Hub</a> <!-- ✅ NEW -->
        <a href="logout.php">🚪 Logout</a>
    </nav>

    <main class="main-content">
        <iframe id="contentFrame" src="home.php" title="Main content"></iframe>
    </main>
</div>

<!-- overlay for mobile sidebar -->
<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<script>
function loadPage(page){
    document.getElementById("contentFrame").src = page + ".php";
}

function toggleSidebar(){
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const isMobile = window.innerWidth <= 900;

    if(isMobile){
        if(sidebar.classList.contains('open')){
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        } else {
            sidebar.classList.add('open');
            overlay.classList.add('show');
        }
    } else {
        sidebar.classList.toggle('collapsed');
    }
}

function closeSidebar(){
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
}

// decorative bubbles
const colors=["#ff0000","#00ff00","#0000ff","#ff00ff","#00ffff","#ffff00","#ff8800","#8800ff","#00ff88","#ff0088"];
function createBubble(){ 
    const bubble=document.createElement("div"); 
    bubble.classList.add("bubble"); 
    const size=Math.random()*25+15+"px"; 
    bubble.style.width=size; 
    bubble.style.height=size; 
    bubble.style.left=Math.random()*window.innerWidth+"px"; 
    bubble.style.backgroundColor=colors[Math.floor(Math.random()*colors.length)]; 
    bubble.style.animationDuration=(Math.random()*4+3)+"s"; 
    document.body.appendChild(bubble); 
    setTimeout(()=>{bubble.remove();},7000); 
}
setInterval(createBubble,350);
</script>

</body>
</html>
