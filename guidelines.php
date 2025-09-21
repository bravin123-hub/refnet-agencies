<?php
session_start();
$isLoggedIn = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>RefNet Agencies - Guidelines</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        .navbar {
            background-color: #2c3e50;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
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
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #f1c40f;
            padding-bottom: 10px;
        }
        ul {
            padding-left: 20px;
            line-height: 1.8em;
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

        
    </div>

    <!-- Main Content -->
    <div class="container">
        <h2>✅ What You Must Do:</h2>
        <ul>
            <li>Be respectful to all users and team members.</li>
            <li>Use appropriate language in all submissions and comments.</li>
            <li>Respect others' opinions and contributions.</li>
            <li>Use your account responsibly and protect your login credentials.</li>
            <li>Report any suspicious or abusive behavior immediately.</li>
        </ul>

        <h2>🚫 What You Must Not Do:</h2>
        <ul>
            <li>Do not post or share offensive, harmful, or illegal content.</li>
            <li>Do not spam or misuse platform features.</li>
            <li>Do not plagiarize or steal others' content or ideas.</li>
            <li>Do not attempt to hack, reverse engineer, or exploit the platform.</li>
            <li>Do not create multiple accounts to cheat or manipulate the system.</li>
        </ul>

        <h2>⚠️ Violation Consequences:</h2>
        <ul>
            <li>Warnings, temporary suspensions, or permanent bans based on severity.</li>
            <li>Loss of privileges, rewards, or referral bonuses.</li>
            <li>Legal action for serious violations (e.g. fraud, impersonation, abuse).</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?php echo date("Y"); ?> RefNet Agencies. All Rights Reserved.
    </div>

</body>
</html>
