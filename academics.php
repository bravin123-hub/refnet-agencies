<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academics - RefNet Agencies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #e8f5e9, #f1f8e9);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .question-box {
            border: 2px solid #4caf50;
            border-radius: 15px;
            background: #e8f5e9;
            padding: 20px;
            margin-bottom: 25px;
            font-size: 18px;
            line-height: 1.6;
            color: #1b5e20;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        textarea {
            width: 100%;
            height: 800px; /* Tall to simulate multiple pages */
            border: 2px solid #c8e6c9;
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
            line-height: 1.6;
            resize: none;
            background: #f9fff9;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);
        }
        textarea:focus {
            border-color: #4caf50;
            outline: none;
            background: #ffffff;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.5);
        }
        button {
            display: block;
            margin: 25px auto 0;
            background: #4caf50;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }
        button:hover {
            background: #388e3c;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Academic Question</h2>

    <div class="question-box">
        <strong>Question:</strong><br>
        You are now a RefNet Agent. Explain the best strategies you will use to make sure you join more members into the system. 
        Provide your answer in 3 pages.
    </div>

    <form method="post" action="save_academic_answer.php">
        <textarea name="answer" placeholder="Write your detailed 3-page answer here..."></textarea>
        <button type="submit">Submit Answer</button>
    </form>
</div>
</body>
</html>
