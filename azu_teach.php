<?php
session_start();
include("connection.php");

// ✅ Handle new teaching
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teach'])) {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);

    if ($question !== "" && $answer !== "") {
        $stmt = $conn->prepare("INSERT INTO azu_memory (question, answer) VALUES (?, ?)");
        $stmt->bind_param("ss", $question, $answer);
        $stmt->execute();
        $stmt->close();
        $msg = "✅ AZU has learned a new answer!";
    }
}

// ✅ Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM azu_memory WHERE id=$id");
    $msg = "🗑️ Answer deleted successfully.";
}

// ✅ Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $stmt = $conn->prepare("UPDATE azu_memory SET question=?, answer=? WHERE id=?");
    $stmt->bind_param("ssi", $question, $answer, $id);
    $stmt->execute();
    $stmt->close();
    $msg = "✏️ Answer updated successfully.";
}

// ✅ Fetch all memory
$result = $conn->query("SELECT * FROM azu_memory ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Teach AZU</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #4facfe, #00f2fe); color: #333; margin:0; padding:0;}
        .container { width:85%; max-width:900px; margin:40px auto; background:#fff; border-radius:12px; padding:25px; box-shadow:0 6px 20px rgba(0,0,0,0.2);}
        h2, h3 { text-align:center; color:#0077b6; }
        .msg { text-align:center; background:#e0f7fa; color:#00796b; padding:10px; border-radius:6px; margin-bottom:15px; }
        form input, form textarea { width:100%; padding:12px; margin:8px 0; border:1px solid #ccc; border-radius:8px; outline:none; transition:0.3s; }
        form input:focus, form textarea:focus { border-color:#0077b6; box-shadow:0 0 8px rgba(0,119,182,0.4);}
        button { padding:10px 20px; border-radius:8px; border:none; cursor:pointer; transition:0.3s;}
        button[name="teach"] { background:#0077b6; color:white; }
        button[name="teach"]:hover { background:#005f87; }
        .edit { background:#f4b400; color:white; }
        .edit:hover { background:#c38e00; }
        .delete { background:#d93025; color:white; }
        .delete:hover { background:#a32018; }
        .box { border:1px solid #ddd; background:#fafafa; padding:15px; margin:15px 0; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1);}
        .box b { color:#0077b6; }
        .box small { color:#666; }
        .actions { margin-top:10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>📚 Teach AZU</h2>
        <?php if(isset($msg)) echo "<p class='msg'>$msg</p>"; ?>

        <form method="POST">
            <input type="text" name="question" placeholder="Enter question" required>
            <textarea name="answer" placeholder="Enter answer (can include HTML for headings, paragraphs, bullet points)" required></textarea>
            <button type="submit" name="teach">➕ Teach AZU</button>
        </form>

        <hr>
        <h3>📖 AZU’s Memory</h3>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="box">
                <b>Q:</b> <?= htmlspecialchars($row['question']); ?><br>
                <b>A:</b> <?= $row['answer']; ?><br>
                <small><i>🕒 <?= $row['created_at']; ?></i></small>
                <div class="actions">
                    <!-- Edit form -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id']; ?>">
                        <input type="text" name="question" value="<?= htmlspecialchars($row['question']); ?>" required>
                        <input type="text" name="answer" value="<?= htmlspecialchars($row['answer']); ?>" required>
                        <button type="submit" name="update" class="edit">✏️ Edit</button>
                    </form>
                    <!-- Delete button -->
                    <a href="?delete=<?= $row['id']; ?>" onclick="return confirm('Are you sure?');">
                        <button type="button" class="delete">🗑️ Delete</button>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
