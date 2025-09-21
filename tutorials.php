<?php
session_start();
$isLoggedIn = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>RefNet Agencies - Tutorials</title>
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
            margin-top: 40px;
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
            
            <a href="login.php">🔐 Login</a>
        <?php else: ?>
            <a href="dashboard.php">📊 Dashboard</a>
            <a href="logout.php">🚪 Logout</a>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>📘 RefNet Tutorials</h1>
        <p>Welcome to the RefNet Agencies tutorials section. Here you'll find step-by-step guides to help you make the most of our tools and services.</p>

        <h2>📝 1. How to Write and Publish Blogs</h2>
        <p>
            - Go to your Dashboard and click on "Write Blog"<br>
            - Fill in the title and content fields<br>
            - Click "Submit" to publish<br>
            - You can edit or delete your blog anytime
        </p>

        <h2>👥 2. How Referral System Works</h2>
        <p>
            - After registration, your referral code is generated automatically<br>
            - Share it with others to invite them to join<br>
            - When someone registers with your code, you earn rewards<br>
            - Check your referral stats in your Dashboard
        </p>

        <h2>🌀 3. Using the Text Spinner Tool</h2>
        <p>
            - Go to "Text Spinner" from the Dashboard<br>
            - Paste or type your content<br>
            - Click "Spin Text" to generate an alternative version<br>
            - Use the new version for SEO, blogging, or social posts
        </p>

        <h2>📚 4. Accessing Academic Tools</h2>
        <p>
            - Log in and go to your Dashboard<br>
            - Click on "Academic Tools" or "Quizzes"<br>
            - Attempt available quizzes or upload your academic content<br>
            - Use AI tools to rewrite or enhance your academic texts
        </p>

        <h2>💡 5. Profile & Account Management</h2>
        <p>
            - Visit "Dashboard" > "Edit Profile"<br>
            - Change your profile picture, email, or password<br>
            - Always click "Save" to update changes
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?php echo date("Y"); ?> RefNet Agencies. All Rights Reserved.
    </div>

</body>
</html>
