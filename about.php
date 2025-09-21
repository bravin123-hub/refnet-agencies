<?php
session_start();
$isLoggedIn = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>About RefNet Agencies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .navbar {
            background-color: #2c3e50;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 15px;
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
        .container {
            padding: 40px 20px;
            max-width: 900px;
            margin: auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        h1, h2 {
            color: #2c3e50;
        }
        p {
            line-height: 1.6em;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        
        <a href="about.php">ℹ️ About Us</a>
        <a href="tutorials.php">📘 Tutorials</a>
        <a href="contact.php">📞 Contact Us</a>
        <a href="news.php">📰 News</a>
        <a href="guidelines.php">📄 Guidelines</a>

        <?php if (!$isLoggedIn): ?>
            
           <a href="dashboard.php">📊 Dashboard</a>
        <?php else: ?>
            
            <a href="logout.php">🚪 Logout</a>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>ℹ️ About RefNet Agencies</h1>
        <p>
            RefNet Agencies is a digital platform designed to empower users through various online tools and opportunities.
            Whether you're looking to earn, learn, or grow your skills, RefNet offers the right environment.
        </p>

        <h2>🎯 Referral System</h2>
        <p>
            Invite friends using your referral code and earn bonuses for every successful registration linked to you.
        </p>

        <h2>✍️ Blogging Platform</h2>
        <p>
            Users can write and share blogs on topics like business, technology, motivation, and education. 
            Express yourself and grow your online presence.
        </p>

        <h2>🔄 Spinner Tool</h2>
        <p>
            Quickly rewrite or improve text using our AI-powered spinner tool – ideal for content creators and students.
        </p>

        <h2>🧪 Academic Quiz System</h2>
        <p>
            Teachers and learners can generate quizzes, post academic content, and share knowledge interactively.
        </p>

        <h2>🌟 Our Mission</h2>
        <ul>
            <li>To create a secure and engaging platform for digital innovation.</li>
            <li>To nurture creativity and skills among young people.</li>
            <li>To reward participation and effort through fair systems.</li>
            <li>To build a strong online learning and earning community.</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?php echo date("Y"); ?> RefNet Agencies. All Rights Reserved.
    </div>

</body>
</html>
