<?php
session_start();
include("connection.php");

// ✅ Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🌐 Website Training Hub</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        margin:0; padding:0;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color:white;
        text-align:center;
    }
    .container {
        max-width:1000px;
        margin:40px auto;
        padding:20px;
    }
    h1 {
        font-size:32px;
        margin-bottom:10px;
    }
    p.tagline {
        font-size:18px;
        opacity:0.9;
        margin-bottom:25px;
    }
    .languages {
        display:grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap:15px;
        margin-bottom:30px;
    }
    .lang-card {
        background:rgba(255,255,255,0.1);
        border-radius:10px;
        padding:15px;
        transition: all 0.25s ease;
        cursor: pointer;
        text-decoration: none;
        color: white;
        display: block;
    }
    .lang-card:hover {
        background:rgba(255,255,255,0.2);
        transform:scale(1.05);
    }
    .lang-card h3 {
        margin:10px 0 5px;
        font-size:20px;
        color:#ffd700;
    }
    .lang-card p {
        font-size:14px;
        margin-top:8px;
        opacity:0.85;
    }
    .lang-card i {
        font-size:40px;
        color:#ffd700;
    }
    .btn {
        display:inline-block;
        padding:12px 25px;
        background:#ffd700;
        color:#000;
        border-radius:6px;
        font-weight:bold;
        text-decoration:none;
        transition: background 0.25s ease;
    }
    .btn:hover {
        background:#ffcc33;
    }
</style>
</head>
<body>

<div class="container">
    <h1>🌐 Welcome to Website Training Hub</h1>
    <p class="tagline">Unlock your future! Learn the languages that power the web and become a creator of tomorrow 🚀</p>

    <div class="languages">
        <a href="intro_html.php?lang=HTML" class="lang-card"><i class="devicon-html5-plain"></i><h3>HTML</h3><p>The skeleton of every website. Build strong foundations.</p></a>
        <a href="intro_css.php?lang=CSS" class="lang-card"><i class="devicon-css3-plain"></i><h3>CSS</h3><p>Style your imagination. Make the web look beautiful.</p></a>
        <a href="intro_javascript.php?lang=JavaScript" class="lang-card"><i class="devicon-javascript-plain"></i><h3>JavaScript</h3><p>Add interactivity and life to your websites.</p></a>
        <a href="intro_php.php?lang=PHP" class="lang-card"><i class="devicon-php-plain"></i><h3>PHP</h3><p>The power behind dynamic websites and servers.</p></a>
        <a href="intro_python.php?lang=Python" class="lang-card"><i class="devicon-python-plain"></i><h3>Python</h3><p>From AI to web apps, Python opens endless doors.</p></a>
        <a href="intro_java.php?lang=Java" class="lang-card"><i class="devicon-java-plain"></i><h3>Java</h3><p>Write once, run anywhere. Enterprise-ready power.</p></a>
        <a href="intro_csharp.php?lang=CSharp" class="lang-card"><i class="devicon-csharp-plain"></i><h3>C#</h3><p>Microsoft’s powerhouse. Create apps, games, and more.</p></a>
        <a href="intro_cpp.php?lang=C++" class="lang-card"><i class="devicon-cplusplus-plain"></i><h3>C++</h3><p>High performance and control. The language of giants.</p></a>
        <a href="intro_ruby.phpss?lang=Ruby" class="lang-card"><i class="devicon-ruby-plain"></i><h3>Ruby</h3><p>Elegant and fun. Power your startups with Rails.</p></a>
        <a href="intro_go.phps?lang=Go" class="lang-card"><i class="devicon-go-plain"></i><h3>Go (Golang)</h3><p>Fast, modern, and efficient. Google’s gift to developers.</p></a>
        <a href="intro_swift.php?lang=Swift" class="lang-card"><i class="devicon-swift-plain"></i><h3>Swift</h3><p>Build stunning iOS and Mac apps with ease.</p></a>
        <a href="intro_kotlin.php?lang=Kotlin" class="lang-card"><i class="devicon-kotlin-plain"></i><h3>Kotlin</h3><p>The modern Android language. Simpler, safer, better.</p></a>
        <a href="intro_typescript.php?lang=TypeScript" class="lang-card"><i class="devicon-typescript-plain"></i><h3>TypeScript</h3><p>JavaScript with superpowers. Scale with confidence.</p></a>
        <a href="intro_sql.php?lang=SQL" class="lang-card"><i class="devicon-mysql-plain"></i><h3>SQL</h3><p>Master data. Speak the language of databases.</p></a>
        <a href="intro_rust.php?lang=Rust" class="lang-card"><i class="devicon-rust-plain"></i><h3>Rust</h3><p>Memory-safe and blazing fast. The future of system programming.</p></a>
        <a href="intro_perl.php?lang=Perl" class="lang-card"><i class="devicon-perl-plain"></i><h3>Perl</h3><p>The original web glue language. Still powerful today.</p></a>
        <a href="intro_scala.php?lang=Scala" class="lang-card"><i class="devicon-scala-plain"></i><h3>Scala</h3><p>Functional meets object-oriented. Big data’s favorite.</p></a>
        <a href="intro_r.php?lang=R" class="lang-card"><i class="devicon-r-plain"></i><h3>R</h3><p>Statistics, machine learning, and data science magic.</p></a>
        <a href="intro_shell.php?lang=Shell" class="lang-card"><i class="devicon-bash-plain"></i><h3>Shell (Bash)</h3><p>Control your system. Automate with ease.</p></a>
        <a href="intro_matlab.php?lang=MATLAB" class="lang-card"><i class="devicon-matlab-plain"></i><h3>MATLAB</h3><p>Math, engineering, and simulation simplified.</p></a>
    </div>

    <a href="connect_client_to_earn.php" class="btn">👥 Connect Client to Earn</a>
</div>

</body>
</html>
