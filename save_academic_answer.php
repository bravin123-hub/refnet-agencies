<?php
session_start();
include("connection.php");

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "Please log in to submit your answer.";
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['answer'])) {
    $answer = trim($_POST['answer']);

    // Prepare statement with error check
    $stmt = $conn->prepare("INSERT INTO academic_answers (email, answer, submitted_at) VALUES (?, ?, NOW())");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("ss", $email, $answer);
    if ($stmt->execute()) {
        echo "<h2 style='color: green;'>Answer submitted successfully!</h2>";
    } else {
        echo "<h2 style='color: red;'>Error submitting answer: " . htmlspecialchars($stmt->error) . "</h2>";
    }

    $stmt->close();
} else {
    echo "No answer submitted.";
}
?>

<div style="margin-top: 20px;">
    <a href="academics.php" 
       style="background-color: #007bff; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; margin-right: 10px;">
       ← Go Back
    </a>

    <a href="publish_academics.php" 
       style="background-color: #28a745; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
       📚 Go to Publish
    </a>
</div>
