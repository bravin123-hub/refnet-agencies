<?php
session_start();
include("connection.php");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Define language data
$languages = [
    "html"   => ["HTML", "🌐", "#3498db", "HTML is the backbone of web development. It structures web pages and provides the foundation for everything online."],
    "css"    => ["CSS", "🎨", "#e67e22", "CSS styles the web! It makes websites beautiful with colors, layouts, and responsive design."],
    "php"    => ["PHP", "🐘", "#8e44ad", "PHP powers the backend of websites, handling logic, databases, and server-side processing."],
    "javascript" => ["JavaScript", "⚡", "#f1c40f", "JavaScript makes websites interactive with animations, forms, and dynamic updates."],
    "python" => ["Python", "🐍", "#2ecc71", "Python is versatile and widely used for web, AI, automation, and data science."],
    "java"   => ["Java", "☕", "#e74c3c", "Java powers enterprise apps, Android, and is one of the most used languages worldwide."],
    "c"      => ["C", "🔧", "#34495e", "C is the mother of all languages. Fast, powerful, and still critical for systems programming."],
    "cpp"    => ["C++", "🚀", "#9b59b6", "C++ is widely used in games, engines, and performance-heavy applications."],
    "csharp" => ["C#", "🎮", "#1abc9c", "C# is Microsoft’s powerful language for Windows apps, games (Unity), and web development."],
    "ruby"   => ["Ruby", "💎", "#d35400", "Ruby and Rails make web apps fast and developer-friendly."],
    "swift"  => ["Swift", "🍏", "#27ae60", "Swift is Apple’s modern language for iOS and macOS development."],
    "kotlin" => ["Kotlin", "📱", "#2980b9", "Kotlin is the modern Android development language backed by Google."],
    "go"     => ["Go", "🐹", "#16a085", "Go (Golang) is fast, simple, and efficient for web servers and cloud systems."],
    "rust"   => ["Rust", "🦀", "#c0392b", "Rust is loved for memory safety, speed, and reliability in modern systems."],
    "typescript" => ["TypeScript", "📘", "#7f8c8d", "TypeScript enhances JavaScript with type safety for large projects."],
    "perl"   => ["Perl", "🐪", "#9b59b6", "Perl is flexible, great for text processing, and scripting automation."],
    "scala"  => ["Scala", "⚡", "#34495e", "Scala combines OOP and functional programming. Used in big data projects."],
    "r"      => ["R", "📊", "#16a085", "R is built for statistics, data science, and visualization."],
    "shell"  => ["Shell (Bash)", "💻", "#2ecc71", "Shell scripting automates tasks and controls systems with ease."],
    "matlab" => ["MATLAB", "📐", "#e74c3c", "MATLAB is the favorite of engineers and scientists for math and simulations."]
];

// Get requested language
$langKey = strtolower($_GET['lang'] ?? '');
if (!isset($languages[$langKey])) {
    echo "<h2>Language not found</h2><a href='website.php'>⬅ Back to Hub</a>";
    exit();
}

list($langName, $emoji, $color, $description) = $languages[$langKey];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Intro to <?= htmlspecialchars($langName) ?></title>
    <style>
        body {
            font-family: Arial;
            background: <?= $color ?>;
            color: white;
            text-align: center;
            padding: 40px;
        }
        h1 { font-size: 36px; }
        p { max-width: 800px; margin: 20px auto; font-size: 18px; }
        a {
            padding: 12px 25px;
            background: #ffd700;
            color: black;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1><?= $emoji ?> Introduction to <?= htmlspecialchars($langName) ?></h1>
    <p><?= htmlspecialchars($description) ?></p>
    <a href="website.php">⬅ Back to Hub</a>
</body>
</html>
