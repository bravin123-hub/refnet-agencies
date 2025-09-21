<?php
session_start();
include("includes/connection.php");

// Run the blog query and join user info
$query = "SELECT blogs.*, users.fullname 
          FROM blogs 
          JOIN users ON blogs.user_id = users.id 
          ORDER BY blogs.created_at DESC";

$result = $conn->query($query);

// If the query fails, show error and stop
if (!$result) {
    die("<p style='color:red;'>Database Error: " . $conn->error . "</p>");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>RefNet Blog Posts</title>
    <style>
        body {
            font-family: Arial;
            background: #f0f0f0;
            padding: 40px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 0 15px #ccc;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .blog {
            margin-bottom: 30px;
            padding: 15px;
            border-left: 6px solid #00b894;
            border-radius: 4px;
            background: #f9f9f9;
        }
        .blog h2 {
            margin: 0;
            font-size: 20px;
        }
        .blog p {
            font-size: 16px;
        }
        .meta {
            font-size: 14px;
            color: #666;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 RefNet Blog Posts</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="blog">
                    <h2><?= htmlspecialchars($row['title']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                    <div class="meta">
                        By <strong><?= htmlspecialchars($row['fullname']) ?></strong>
                        on <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No blog posts yet. Be the first to <a href="write_blog.php">write one</a>!</p>
        <?php endif; ?>
    </div>
</body>
</html>
