<?php
session_start();
include("connection.php");

// ✅ Use user_id instead of email for session check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ensure we have a mysqli connection object
if (!isset($conn) || !($conn instanceof mysqli)) {
    if (isset($connection) && $connection instanceof mysqli) {
        $conn = $connection;
    } elseif (isset($db) && $db instanceof mysqli) {
        $conn = $db;
    } elseif (isset($link) && $link instanceof mysqli) {
        $conn = $link;
    } else {
        die("Database connection not found. Check connection.php and ensure it sets \$conn.");
    }
}

// ✅ Get email from users table using user_id
$email = "";
$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $email = $row['email'];
}
$stmt->close();

// Default
$alreadySpun = 0;

// ✅ Check if this user already spun (using email)
$sql = "SELECT COUNT(*) AS cnt FROM spin_history WHERE email = ? AND spin_type = 'register'";
$stmt = $conn->prepare($sql);

if ($stmt !== false) {
    $stmt->bind_param("s", $email);
    if ($stmt->execute()) {
        $res = $stmt->get_result();
        if ($res) {
            $row = $res->fetch_assoc();
            $alreadySpun = isset($row['cnt']) ? (int)$row['cnt'] : 0;
        }
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register Spin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 { margin-top: 20px; color: #333; }
        .spin-container {
            display: flex;
            gap: 40px;
            margin-top: 20px;
            align-items: center;
        }
        .bet-box {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .bet-box button {
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 16px;
            background: #1abc9c;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .bet-box button:hover:enabled { background: #16a085; }
        .bet-box button:disabled {
            background: #aaa;
            cursor: not-allowed;
        }
        #wheelCanvas {
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        #arrow {
            width: 0; height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-bottom: 30px solid red;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: -15px;
        }
        #result {
            margin-top: 20px;
            font-size: 20px;
            color: #2c3e50;
            text-align: center;
        }
    </style>
</head>
<body>

<h1>🎡 Register Spin</h1>

<div class="spin-container">
    <div class="bet-box">
        <h3>Registration Reward Spin</h3>
        <p>Click below to spin and win your bonus!</p>
        <button id="spinBtn" onclick="spinWheel()" <?php if ($alreadySpun > 0) echo 'disabled'; ?>>
            <?php echo ($alreadySpun > 0) ? 'Already Used' : 'SPIN'; ?>
        </button>
    </div>

    <div>
        <div id="arrow"></div>
        <canvas id="wheelCanvas" width="350" height="350"></canvas>
    </div>
</div>

<div id="result"><?php if ($alreadySpun > 0) echo "❌ You have already used your register spin."; ?></div>

<script>
/* --- Wheel JavaScript (unchanged) --- */
const canvas = document.getElementById("wheelCanvas");
const ctx = canvas.getContext("2d");

// 20 prizes
const prizes = [
  "KES 0","KES 50","KES 100","KES 150","KES 200","KES 250","KES 300","KES 350",
  "KES 400","KES 450","KES 500","KES 550","KES 600","KES 650","KES 700","KES 750",
  "KES 800","KES 850","KES 900","KES 1000"
];

// 20 distinct colors
const colors = [
  "#FF6384","#36A2EB","#FFCE56","#8BC34A","#9C27B0","#FF5722","#03A9F4","#E91E63",
  "#4CAF50","#FFC107","#FF9800","#CDDC39","#673AB7","#F44336","#00BCD4","#8E44AD",
  "#2ECC71","#D35400","#1ABC9C","#34495E"
];

let startAngle = 0;
const arc = 2 * Math.PI / prizes.length;
let spinTimeout = null;
let spinAngleStart = 0;
let spinTime = 0;
let spinTimeTotal = 0;

function drawWheel() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let i = 0; i < prizes.length; i++) {
        const angle = startAngle + i * arc;
        ctx.fillStyle = colors[i];
        ctx.beginPath();
        ctx.moveTo(175, 175);
        ctx.arc(175, 175, 175, angle, angle + arc, false);
        ctx.lineTo(175, 175);
        ctx.fill();

        ctx.save();
        ctx.fillStyle = "white";
        ctx.translate(175 + Math.cos(angle + arc / 2) * 120,
                      175 + Math.sin(angle + arc / 2) * 120);
        ctx.rotate(angle + arc / 2);
        ctx.fillText(prizes[i], -ctx.measureText(prizes[i]).width / 2, 0);
        ctx.restore();
    }
}

function easeOut(t, b, c, d) {
    const ts = (t/=d)*t;
    const tc = ts*t;
    return b+c*(tc + -3*ts + 3*t);
}

function rotateWheel() {
    spinTime += 30;
    if (spinTime >= spinTimeTotal) {
        stopRotateWheel();
        return;
    }
    const spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
    startAngle += spinAngle * Math.PI / 180;
    drawWheel();
    spinTimeout = setTimeout(rotateWheel, 30);
}

function stopRotateWheel() {
    const degrees = startAngle * 180 / Math.PI + 90;
    const index = Math.floor((360 - (degrees % 360)) / (360 / prizes.length)) % prizes.length;
    const prizeWon = prizes[index];
    document.getElementById("result").innerHTML = "🎉 You won " + prizeWon + "!";

    // Save to spin history
    fetch("save_spin.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "points=" + encodeURIComponent(prizeWon) + "&spin_type=register"
    }).then(() => {
        document.getElementById("spinBtn").disabled = true;
        document.getElementById("spinBtn").innerText = "Already Used";
    }).catch(err => {
        console.error("Failed to save spin:", err);
    });
}

function spinWheel() {
    spinAngleStart = Math.random() * 10 + 10;
    spinTime = 0;
    spinTimeTotal = Math.random() * 3000 + 4000;
    rotateWheel();
}

drawWheel();
</script>

</body>
</html>
