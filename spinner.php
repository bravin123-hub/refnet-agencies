<?php
// Handle spin result if it's a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['amount'])) {
    session_start();
    $amount = intval($_POST['amount']);

    // Optional: Save to database here
    // Example: include("connection.php"); // Save $amount for $_SESSION['email']

    echo "✅ Result saved: You won KES $amount!";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>RefNet Spin to Win</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f0f0f0;
        }

        #wheel-container {
            width: 90vw;
            max-width: 400px;
            margin: 40px auto;
            position: relative;
        }

        #wheel {
            width: 100%;
            height: auto;
            border-radius: 50%;
            border: 10px solid #333;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            transition: transform 5s ease-out;
        }

        #spin-btn {
            margin-top: 20px;
            padding: 12px 24px;
            font-size: 18px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        #spin-btn:disabled {
            background-color: gray;
        }

        #pointer {
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-bottom: 30px solid red;
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
        }

        #result {
            margin-top: 20px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <h2>🎯 RefNet Spin & Win Money!</h2>
    <div id="wheel-container">
        <div id="pointer"></div>
        <canvas id="wheel" width="400" height="400"></canvas>
    </div>
    <button id="spin-btn">SPIN</button>
    <p id="result"></p>

    <script>
        const segments = [0, 40, 80, 20, 60, 100, 500, 200, 300, 0, 1000, 250];
        const wheelCanvas = document.getElementById("wheel");
        const ctx = wheelCanvas.getContext("2d");
        const spinBtn = document.getElementById("spin-btn");
        const resultText = document.getElementById("result");
        let spinning = false;
        const center = wheelCanvas.width / 2;
        const radius = center;
        const anglePerSlice = (2 * Math.PI) / segments.length;

        // Draw the wheel
        function drawWheel() {
            for (let i = 0; i < segments.length; i++) {
                const angle = i * anglePerSlice;
                ctx.beginPath();
                ctx.moveTo(center, center);
                ctx.arc(center, center, radius, angle, angle + anglePerSlice);
                ctx.fillStyle = i % 2 === 0 ? "#ffcc00" : "#ff6600";
                ctx.fill();

                ctx.save();
                ctx.translate(center, center);
                ctx.rotate(angle + anglePerSlice / 2);
                ctx.textAlign = "right";
                ctx.fillStyle = "#000";
                ctx.font = "16px Arial";
                ctx.fillText("KES " + segments[i], radius - 10, 5);
                ctx.restore();
            }
        }

        drawWheel();

        function spinWheel() {
            if (spinning) return;
            spinning = true;
            spinBtn.disabled = true;
            resultText.textContent = "Spinning...";

            const spins = Math.floor(Math.random() * 5) + 5;
            const stopAt = Math.floor(Math.random() * segments.length);
            const finalAngle = (spins * 360) + ((segments.length - stopAt) * (360 / segments.length));
            let rotation = 0;
            const duration = 5000;
            const start = performance.now();

            function animate(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const easeOut = 1 - Math.pow(1 - progress, 3);
                rotation = easeOut * finalAngle;
                wheelCanvas.style.transform = `rotate(${rotation}deg)`;

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    const index = stopAt;
                    const amountWon = segments[index];
                    resultText.textContent = `🎉 You won KES ${amountWon}!`;

                    // Send result to PHP backend
                    fetch("spinner.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `amount=${amountWon}`
                    })
                    .then(res => res.text())
                    .then(msg => {
                        resultText.textContent += "\n" + msg;
                        spinBtn.disabled = false;
                        spinning = false;
                    });
                }
            }

            requestAnimationFrame(animate);
        }

        spinBtn.addEventListener("click", spinWheel);
    </script>
</body>
</html>
