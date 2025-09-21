<?php
session_start();
include("connection.php");

if (!isset($_SESSION['email'])) {
    echo "<p style='color:red; text-align:center; font-family: Arial;'>Please log in to access your published academics.</p>";
    exit();
}

$email = $_SESSION['email'];

// Fetch academic submissions
$stmt = $conn->prepare("
    SELECT question, answer, submitted_at 
    FROM academics 
    WHERE email = ?
    ORDER BY submitted_at DESC
");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Published Academics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        .academic-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s ease-in-out;
        }
        .academic-card:hover {
            transform: scale(1.02);
        }
        .question {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .answer {
            background-color: #f0f2f5;
            padding: 12px;
            border-radius: 8px;
            color: #555;
        }
        .date {
            font-size: 0.85em;
            color: #888;
            text-align: right;
            margin-top: 10px;
        }
        .no-data {
            text-align: center;
            color: #777;
            font-size: 1.1em;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📚 My Published Academics</h2>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='academic-card'>";
            echo "<div class='question'>Q: " . htmlspecialchars($row['question']) . "</div>";
            echo "<div class='answer'>" . nl2br(htmlspecialchars($row['answer'])) . "</div>";
            echo "<div class='date'>Submitted on: " . date("F j, Y, g:i a", strtotime($row['submitted_at'])) . "</div>";
            echo "</div>";
        }
    } else {
        echo "<div class='no-data'>No academic submissions found.</div>";
    }
    ?>
</div>

</body>
</html>
