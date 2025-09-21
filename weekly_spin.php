<?php 
session_start();
date_default_timezone_set('Africa/Nairobi');

// Handle spin restriction
$now = time();
$next_spin_time = $_SESSION['weekly_next_spin'] ?? 0;
$can_spin = $now >= $next_spin_time;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!$can_spin) {
        echo json_encode(["status"=>"error","message"=>"You can spin again after the countdown ends."]);
        exit();
    }

    $amounts = [0, 40, 80, 20, 60, 100, 200, 400, 50, 500, 300, 250, 150, 350, 450, 600, 700, 800, 900, 1000];
    $result = $amounts[array_rand($amounts)];

    $_SESSION['weekly_last_result'] = $result;
    $_SESSION['weekly_next_spin'] = strtotime("+7 days");

    // Save spin to history (optional: you can connect to DB instead)
    file_put_contents("spin_history.txt", date('Y-m-d H:i:s')." | Weekly Spin | KES $result | ".$_SESSION['email']."\n", FILE_APPEND);

    echo json_encode(["status"=>"success","amount"=>$result]);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Weekly Spin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; flex-direction: column; align-items: center; }
        h1 { margin-top: 20px; color: #333; }
        .spin-container { display: flex; flex-direction: column; gap: 20px; margin-top: 20px; align-items: center; }
        #wheelCanvas { background: #fff; border-radius: 50%; box-shadow: 0 0 15px rgba(0,0,0,0.3); }
        #arrow { width: 0; height: 0; border-left: 20px solid transparent; border-right: 20px solid transparent; border-bottom: 30px solid red; margin: auto; margin-bottom: -15px; }
        #result { margin-top: 20px; font-size: 20px; color: #2c3e50; text-align: center; }
        #countdown { font-weight: bold; margin-bottom: 10px; color: #d35400; }
        button { margin-top: 10px; padding: 10px 20px; font-size: 16px; background: #1abc9c; color: white; border: none; cursor: pointer; border-radius: 8px; }
        button:disabled { background: #bdc3c7; cursor: not-allowed; }
    </style>
</head>
<body>

<h1>🎡 Weekly Spin</h1>

<div class="spin-container">
    <div id="countdown">Loading...</div>
    <div>
        <div id="arrow"></div>
        <canvas id="wheelCanvas" width="350" height="350"></canvas><br>
        <button id="spinBtn" <?php echo !$can_spin ? "disabled" : ""; ?>>SPIN</button>
    </div>
</div>

<div id="result"></div>

<script>
const canvas = document.getElementById("wheelCanvas");
const ctx = canvas.getContext("2d");

const prizes = [0, 40, 80, 20, 60, 100, 200, 400, 50, 500, 300, 250, 150, 350, 450, 600, 700, 800, 900, 1000];
const colors = ["#FF6384","#36A2EB","#FFCE56","#8BC34A","#9C27B0","#FF5722","#03A9F4","#E91E63","#4CAF50","#FFC107",
                "#FF9800","#CDDC39","#673AB7","#F44336","#00BCD4","#8E44AD","#2ECC71","#D35400","#1ABC9C","#34495E"];
let startAngle = 0;
const arc = 2 * Math.PI / prizes.length;
let spinTimeout = null;
let spinAngleStart = 0;
let spinTime = 0;
let spinTimeTotal = 0;

function drawWheel() {
    ctx.clearRect(0,0,350,350);
    for(let i=0;i<prizes.length;i++){
        const angle = startAngle + i*arc;
        ctx.fillStyle = colors[i];
        ctx.beginPath();
        ctx.moveTo(175,175);
        ctx.arc(175,175,175,angle,angle+arc,false);
        ctx.lineTo(175,175);
        ctx.fill();

        ctx.save();
        ctx.fillStyle = "white";
        ctx.translate(175+Math.cos(angle+arc/2)*120, 175+Math.sin(angle+arc/2)*120);
        ctx.rotate(angle+arc/2);
        ctx.fillText("KES "+prizes[i], -ctx.measureText("KES "+prizes[i]).width/2,0);
        ctx.restore();
    }
}

function easeOut(t,b,c,d){const ts=(t/=d)*t;const tc=ts*t;return b+c*(tc - 3*ts + 3*t);}
function rotateWheel(){spinTime+=30;if(spinTime>=spinTimeTotal){stopRotateWheel();return;}const spinAngle=spinAngleStart-easeOut(spinTime,0,spinAngleStart,spinTimeTotal);startAngle+=spinAngle*Math.PI/180;drawWheel();spinTimeout=setTimeout(rotateWheel,30);}
function stopRotateWheel(){
    const degrees = startAngle*180/Math.PI + 90;
    const index = Math.floor((360-(degrees%360))/(360/prizes.length))%prizes.length;
    document.getElementById("result").textContent = "🎉 You won KES "+prizes[index]+"!";
}

document.getElementById("spinBtn").addEventListener("click", ()=>{
    fetch("weekly_spin.php",{method:"POST"}).then(res=>res.json()).then(data=>{
        if(data.status==="success"){
            spinAngleStart=Math.random()*10+10;
            spinTime=0;
            spinTimeTotal=Math.random()*3000+4000;
            rotateWheel();
        }else{alert(data.message);}
    });
});

// Countdown
let canSpin = <?php echo $can_spin ? 'true':'false'; ?>;
let nextSpinTime = <?php echo $next_spin_time ? $next_spin_time:'null'; ?>;
let countdownEl = document.getElementById('countdown');

function updateCountdown(){
    if(canSpin && !nextSpinTime){countdownEl.textContent="✅ Ready to Spin!";return;}
    let now = Math.floor(Date.now()/1000);
    let distance = nextSpinTime - now;
    if(distance<=0){countdownEl.textContent="✅ Ready to Spin!";canSpin=true;document.getElementById("spinBtn").disabled=false;return;}
    let days=Math.floor(distance/(60*60*24));
    let hours=Math.floor((distance%(60*60*24))/(60*60));
    let minutes=Math.floor((distance%(60*60))/60);
    let seconds=distance%60;
    countdownEl.textContent=`${days}d ${hours}h ${minutes}m ${seconds}s`;
}

setInterval(updateCountdown,1000);
updateCountdown();
drawWheel();
</script>
</body>
</html>
