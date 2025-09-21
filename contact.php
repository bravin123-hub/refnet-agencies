<?php
session_start();
$isLoggedIn = isset($_SESSION['email']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us - RefNet Agencies</title>
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
            max-width: 800px;
            margin: auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        h1 {
            color: #2c3e50;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1abc9c;
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
        }

        .contact-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }

        .whatsapp-icon {
            display: inline-block;
            margin-left: 10px;
        }

        .whatsapp-icon img {
            height: 24px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<div class="navbar"> <a href="about.php">ℹ️ About Us</a>
    <a href="tutorials.php">📘 Tutorials</a>
 <a href="news.php">📰 News</a>
    <a href="guidelines.php">📄 Guidelines</a>
    <a href="contact.php">📞 Contact Us</a>
</div>

<!-- Main Content -->
<div class="container">
    <h1>📞 Contact RefNet Agencies</h1>
    <p>If you have any questions, feedback, or suggestions, feel free to reach out to us using the form below:</p>

    <form method="post" action="contact.php">
        <label for="name">Full Name:</label>
        <input type="text" name="name" required>

        <label for="email">Email Address:</label>
        <input type="email" name="email" required>

        <label for="message">Your Message:</label>
        <textarea name="message" rows="6" required></textarea>

        <button type="submit" name="submit">Send Message</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $message = htmlspecialchars($_POST['message']);

        echo "<p style='color: green; margin-top: 20px;'>Thank you, <strong>$name</strong>. We have received your message and will respond soon!</p>";
    }
    ?>

    <div class="contact-info">
        <h3>📱 Call or WhatsApp Us:</h3>
        <p>
            Phone: <a href="tel:+254707368654">0707 368 654</a>
            <span class="whatsapp-icon">
                <a href="https://wa.me/254707368654" target="_blank" title="Chat on WhatsApp">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
                </a>
            </span>
        </p>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    &copy; <?php echo date("Y"); ?> RefNet Agencies. All Rights Reserved.
</div>

</body>
</html>
