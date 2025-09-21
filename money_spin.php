<?php
session_start();
include("connection.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user balance
$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$balance = $user['balance'];
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Money Spin</title>
<style>
    body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; flex-direction: column; align-items: center; padding: 20px; }
    h1 { color: #333; margin-bottom: 20px; }
    .balance-box { background: #fff; padding: 15px 20px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); margin-bottom: 20px; font-size: 18px; color: #16a085; }
    .spin-container { display: flex; gap: 40px; align-items: center; flex-wrap: wrap; justify-content: center; }
    .bet-box { background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.15); text-align: center; min-width: 220px; }
    .bet-box input { width: 100px; padding: 8px; margin-top: 10px; font-size: 16px; text-align: center; border-radius: 6px; border: 1px solid #ccc; }
    .bet-box button { margin-top: 10px; padding: 10px 15px; font-size: 16px; background: #1abc9c; color: white; border: none; cursor: pointer; border-radius: 5px; transition: all 0.3s ease; }
    .bet-box button:hover { background: #16a085; transform: translateY(-2px); }
    #wheelCanvas { background: #fff; border-radius: 50%; box-shadow: 0 0 15px rgba(0,0,0,0.3); margin-bottom: 10px; }
    #arrow { width: 0; height: 0; border-left: 20px solid transparent; border-right: 20px solid transparent; border-bottom: 30px solid red; margin: auto; margin-bottom: -15px; }
    #result { margin-top: 20px; font-size: 20px; color: #2c3e50; text-align: center; }
</style>
</head>
<body>

<h1>🎡 Money Spin</h1>
<div class="balance-box" id="userBalance">Balance: KES <?php echo number_format($balance,2); ?></div>

<div class="spin-container">
    <div class="bet-box">
        <h3>Place Your Bet</h3>
        <label>Amount (KES)</label><br>
        <input type="number" id="betAmount" min="50" placeholder="50+" /><br>
        <button onclick="placeBet()">Place Bet</button>
    </div>

    <div>
        <div id="arrow"></div>
        <canvas id="wheelCanvas" width="350" height="350"></canvas><br>
        <button id="spinBtn" onclick="spinWheel()" disabled>SPIN</button>
    </div>
</div>

<div id="result"></div>

<script>
const canvas = document.getElementById("wheelCanvas");
const ctx = canvas.getContext("2d");

let userBalance = <?php echo $balance; ?>;
let betPlaced = false;
let currentBet = 0;

// 20 prizes
const prizes = [0, 40, 80, 20, 60, 100, 200, 400, 50, 500, 300, 250, 150, 350, 450, 600, 700, 800, 900, 1000];
// 20 colors
const colors = ["#FF6384","#36A2EB","#FFCE56","#8BC34A","#9C27B0","#FF5722","#03A9F4","#E91E63","#4CAF50","#FFC107","#FF9800","#CDDC39","#673AB7","#F44336","#00BCD4","#8E44AD","#2ECC71","#D35400","#1ABC9C","#34495E"];

let startAngle = 0;
const arc = 2 * Math.PI / prizes.length;
let spinTimeout = null;
let spinAngleStart = 0;
let spinTime = 0;
let spinTimeTotal = 0;

function drawWheel() {
    ctx.clearRect(0, 0, 350, 350);
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
        ctx.translate(175 + Math.cos(angle + arc/2)*120, 175 + Math.sin(angle + arc/2)*120);
        ctx.rotate(angle + arc/2);
        ctx.fillText("KES "+prizes[i], -ctx.measureText("KES "+prizes[i]).width/2, 0);
        ctx.restore();
    }
}

// Easing
function easeOut(t, b, c, d) {
    const ts = (t/=d)*t;
    const tc = ts*t;
    return b+c*(tc + -3*ts + 3*t);
}

function rotateWheel() {
    spinTime += 30;
    if(spinTime >= spinTimeTotal) { stopRotateWheel(); return; }
    const spinAngle = spinAngleStart - easeOut(spinTime,0,spinAngleStart,spinTimeTotal);
    startAngle += spinAngle*Math.PI/180;
    drawWheel();
    spinTimeout = setTimeout(rotateWheel, 30);
}

function stopRotateWheel() {
    const degrees = startAngle * 180 / Math.PI + 90;
    const index = Math.floor((360 - (degrees % 360)) / (360 / prizes.length)) % prizes.length;
    const prizeWon = prizes[index];
    document.getElementById("result").innerHTML = "🎉 You won KES " + prizeWon + "!";

    // Update user balance
    userBalance = userBalance - currentBet + prizeWon;
    document.getElementById("userBalance").innerHTML = "Balance: KES " + userBalance.toFixed(2);

    // Send to server to save spin and update balance
    fetch("save_spin.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "amount=" + prizeWon + "&bet=" + currentBet
    });

    currentBet = 0;
    betPlaced = false;
    document.getElementById("spinBtn").disabled = true;
}

function spinWheel() {
    if(!betPlaced) { alert("Please place a bet first."); return; }
    if(currentBet > userBalance) { alert("Insufficient balance to spin."); return; }
    spinAngleStart = Math.random() * 10 + 10;
    spinTime = 0;
    spinTimeTotal = Math.random()*3000+4000;
    rotateWheel();
}

function placeBet() {
    const bet = parseInt(document.getElementById("betAmount").value);
    if(isNaN(bet) || bet < 50) { alert("Minimum bet is KES 50."); return; }
    if(bet > userBalance) { alert("Insufficient balance. Your balance is KES " + userBalance.toFixed(2)); return; }
    currentBet = bet;
    betPlaced = true;
    document.getElementById("spinBtn").disabled = false;
    alert("Bet of KES "+bet+" placed successfully!");
}

drawWheel();
</script>

</body>
</html>
