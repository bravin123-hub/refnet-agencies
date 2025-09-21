<?php
session_start();
$isLoggedIn = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>RefNet Agencies - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        .navbar {
            background-color: #2c3e50;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            padding: 15px;
            z-index: 10;
            position: relative;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 10px;
            font-weight: bold;
        }
        .navbar a:hover {
            color: #f1c40f;
        }
        .hero {
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px 20px;
            z-index: 10;
            position: relative;
        }
        .hero h1 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .hero p {
            font-size: 18px;
            color: #555;
            max-width: 800px;
            margin: auto;
        }
        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 30px 10px;
            background-color: #fff;
            z-index: 10;
            position: relative;
        }
        .gallery img {
            width: 300px;
            height: 200px;
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.2);
            object-fit: cover;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            z-index: 10;
            position: relative;
        }

        /* Canvas behind content */
        #bubblesCanvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>

    <!-- Background Bubbles -->
    <canvas id="bubblesCanvas"></canvas>

    <!-- Navigation Bar -->
    <div class="navbar">
        <a href="about.php">ℹ️ About Us</a>
        <a href="tutorials.php">📘 Tutorials</a>
        <a href="contact.php">📞 Contact Us</a>
        <a href="news.php">📰 News</a>
        <a href="guidelines.php">📄 Guidelines</a>
   <a href="dashboard.php">📊 Dashboard</a>
        <?php if (!$isLoggedIn): ?>
         
        <?php else: ?>
            
            <a href="logout.php">🚪 Logout</a>
        <?php endif; ?>
    </div>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to RefNet Agencies</h1>
        <p>
            RefNet Agencies is your all-in-one platform for digital innovation and empowerment.
            We offer services like referral marketing, blogging, academic quizzes, and AI-based text tools.
            Whether you're a student, freelancer, or entrepreneur — RefNet gives you tools and a network to grow your success.
        </p>
    </div>

    <!-- Gallery Section with Independent Slideshows -->
    <div class="gallery">
        <img id="slide1" src="images/business1.jpg" alt="Business Strategy">
        <img id="slide2" src="images/team1.jpg" alt="Team Collaboration">
        <img id="slide3" src="images/marketing1.jpg" alt="Referral Marketing">
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?php echo date("Y"); ?> RefNet Agencies. All Rights Reserved.
    </div>

    <!-- JavaScript Slideshow -->
    <script>
        const images1 = ["images/business1.jpg", "images/business2.jpg"];
        const images2 = ["images/team1.jpg", "images/team2.jpg"];
        const images3 = ["images/marketing1.jpg", "images/marketing2.jpg"];

        let index1 = 0, index2 = 0, index3 = 0;

        function slideShow1() {
            index1 = (index1 + 1) % images1.length;
            document.getElementById("slide1").src = images1[index1];
        }
        function slideShow2() {
            index2 = (index2 + 1) % images2.length;
            document.getElementById("slide2").src = images2[index2];
        }
        function slideShow3() {
            index3 = (index3 + 1) % images3.length;
            document.getElementById("slide3").src = images3[index3];
        }

        setInterval(slideShow1, 3000);
        setInterval(slideShow2, 4000);
        setInterval(slideShow3, 5000);

        // Falling bubbles effect
        const canvas = document.getElementById('bubblesCanvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const colors = ["#FF5733","#33FF57","#3357FF","#FF33A8","#A833FF","#33FFF5","#FFD133","#FF6F61","#6AFF33","#FF1493"];
        const bubbles = [];

        class Bubble {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * -canvas.height;
                this.radius = Math.random() * 10 + 5;
                this.color = colors[Math.floor(Math.random() * colors.length)];
                this.speed = Math.random() * 2 + 1;
            }
            update() {
                this.y += this.speed;
                if (this.y > canvas.height) {
                    this.y = 0;
                    this.x = Math.random() * canvas.width;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                }
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.fill();
                ctx.closePath();
            }
        }

        function initBubbles() {
            for (let i = 0; i < 60; i++) {
                bubbles.push(new Bubble());
            }
        }

        function animateBubbles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            bubbles.forEach(bubble => {
                bubble.update();
                bubble.draw();
            });
            requestAnimationFrame(animateBubbles);
        }

        initBubbles();
        animateBubbles();

        // Adjust canvas on resize
        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>

</body>
</html>
