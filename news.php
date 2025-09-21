<?php
session_start();
$isLoggedIn = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>News - RefNet Agencies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
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
            max-width: 1000px;
            margin: auto;
            background-color: white;
            border-radius: 10px;
            margin-top: 30px;
            margin-bottom: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .news-post {
            margin-bottom: 40px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }

        .news-post h2 {
            color: #2c3e50;
        }

        .news-post p {
            color: #555;
            line-height: 1.6;
        }

        .news-post .date {
            font-size: 0.9em;
            color: #888;
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

<!-- Navigation -->
<div class="navbar">
        
    <a href="news.php">📰 News</a>
   <a href="about.php">ℹ️ About Us</a>
    <a href="tutorials.php">📘 Tutorials</a>
 <a href="news.php">📰 News</a>
    <a href="guidelines.php">📄 Guidelines</a>
    <?php if (!$isLoggedIn): ?>
        
       
    <?php else: ?>
       
        <a href="logout.php">🚪 Logout</a>
    <?php endif; ?>
</div>

<!-- News Content -->
<div class="container">
    <h1>📰 Latest News & Updates</h1>

    <div class="news-post">
        <h2>🎉 RefNet Launches New Spinner Tool!</h2>
        <p class="date">Posted on August 6, 2025</p>
        <p>We’re excited to introduce our brand-new Spinner Tool that helps you rewrite academic content easily. It's fast, AI-powered, and perfect for all your writing needs!</p>
    </div>

    <div class="news-post">
        <h2>💡 RefNet Introduces Quiz Generator</h2>
        <p class="date">Posted on August 3, 2025</p>
        <p>Now you can create academic quizzes instantly with our smart generator. Whether you're a teacher or a student, this feature saves time and improves learning.</p>
    </div>

    <div class="news-post">
        <h2>📈 Referral Rewards Increased!</h2>
        <p class="date">Posted on July 30, 2025</p>
        <p>Good news! We've increased the bonus for every new member you refer. Invite friends and earn more from your referrals.</p>
    </div>

    <!-- You can add dynamic news from database later here -->

</div>

<!-- Footer -->
<div class="footer">
    &copy; <?php echo date("Y"); ?> RefNet Agencies. All Rights Reserved.
</div>

</body>
</html>
