<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo "<p>Please <a href='login.php'>log in</a> to take the quiz.</p>";
    exit();
}

// Sample Questions – You can replace with DB-based logic later
$questions = [
    [
        'question' => 'What is the capital of Kenya?',
        'options' => ['Nairobi', 'Kisumu', 'Mombasa', 'Eldoret'],
        'answer' => 'Nairobi'
    ],
    [
        'question' => 'Which planet is known as the Red Planet?',
        'options' => ['Earth', 'Venus', 'Mars', 'Jupiter'],
        'answer' => 'Mars'
    ]
];

$score = 0;
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submitted = true;
    foreach ($questions as $index => $q) {
        if (isset($_POST["q$index"]) && $_POST["q$index"] == $q['answer']) {
            $score++;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Academic Quiz</title>
</head>
<body>
    <h1>🧪 Academic Quiz</h1>

    <?php if ($submitted): ?>
        <p>You scored <?= $score ?> out of <?= count($questions) ?>!</p>
        <a href="quiz.php">Try Again</a>
    <?php else: ?>
        <form method="POST">
            <?php foreach ($questions as $index => $q): ?>
                <fieldset>
                    <legend><?= ($index + 1) . ". " . $q['question'] ?></legend>
                    <?php foreach ($q['options'] as $option): ?>
                        <label>
                            <input type="radio" name="q<?= $index ?>" value="<?= $option ?>" required>
                            <?= $option ?>
                        </label><br>
                    <?php endforeach; ?>
                </fieldset>
                <br>
            <?php endforeach; ?>
            <button type="submit">Submit Quiz</button>
        </form>
    <?php endif; ?>
</body>
</html>
