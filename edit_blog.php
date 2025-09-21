<?php
session_start();
include("connection.php");

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Blog ID missing.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $sql = "UPDATE blogs SET title=?, content=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $id);

    if ($stmt->execute()) {
        header("Location: blogs.php?success=Blog updated successfully");
        exit();
    } else {
        echo "Error updating blog.";
    }
} else {
    $sql = "SELECT * FROM blogs WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 150px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #218838;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #555;
        }
        .back-link:hover {
            color: #000;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>✏️ Edit Blog</h2>
    <form method="POST">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>

        <label>Content</label>
        <textarea name="content" required><?php echo htmlspecialchars($blog['content']); ?></textarea>

        <button type="submit">Update Blog</button>
    </form>
    <a class="back-link" href="blogs.php">⬅ Back to My Blogs</a>
</div>
</body>
</html>
