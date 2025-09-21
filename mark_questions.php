<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("connection.php");

// Example: fetch one pending answer
$query = mysqli_query($conn, "
    SELECT a.id, a.user_id, a.answer, q.correct_answer 
    FROM academic_answers a
    JOIN academic_questions q ON a.question_id = q.id
    WHERE a.marked = 0
    LIMIT 1
");

if ($row = mysqli_fetch_assoc($query)) {
    $studentAnswer = strtolower(trim($row['answer']));
    $correctAnswer = strtolower(trim($row['correct_answer']));

    // Compare answers and get % similarity
    similar_text($studentAnswer, $correctAnswer, $percent);

    // Save result into DB
    $answerId = $row['id'];
    mysqli_query($conn, "UPDATE academic_answers 
                         SET marked = 1, score = '$percent' 
                         WHERE id = '$answerId'");
} else {
    $percent = null;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mark Academic Questions</title>
    <style>
        body { font-family: Arial; background: #d4edda; text-align: center; padding: 50px; }
        h1 { color: #155724; }
    </style>
</head>
<body>
    <?php if ($percent !== null): ?>
        <h1>Student Answer Marked!</h1>
        <p>Similarity with correct answer: <b><?php echo round($percent, 2); ?>%</b></p>
    <?php else: ?>
        <h1>No pending answers to mark 🎉</h1>
    <?php endif; ?>
</body>
</html>
