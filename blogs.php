<?php
session_start();
include __DIR__ . "/connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch blogs
$stmt = $conn->prepare("SELECT id, title, content, created_at, status FROM blogs WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$blogs = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Blogs</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #2c3e50; }
        .msg { padding: 10px; margin-bottom: 15px; border-radius: 6px; font-weight: bold; display:none; }
        .msg.success { background: #2ecc71; color: white; }
        .msg.error { background: #e74c3c; color: white; }
        .blog-card { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px; background: #f9f9f9; }
        .blog-card h2 { margin: 0; font-size: 20px; }
        .blog-card p { margin: 5px 0 10px; }
        .blog-card small { color: gray; }
        .status { display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: bold; margin-left: 10px; }
        .status.pending { background: #f39c12; color: white; }
        .status.approved { background: #2ecc71; color: white; }
        .status.rejected { background: #e74c3c; color: white; }
        .actions { margin-top: 10px; }
        .actions a { padding: 6px 10px; text-decoration: none; border-radius: 4px; margin-right: 5px; font-size: 14px; }
        .edit-btn { background: #4CAF50; color: white; }
        .delete-btn { background: #f44336; color: white; cursor:pointer; }
    </style>
</head>
<body>

<h1>📚 My Blogs</h1>
<div id="msg" class="msg"></div>

<div id="blogs-container">
<?php if ($blogs->num_rows > 0): ?>
    <?php while ($row = $blogs->fetch_assoc()): ?>
        <div class="blog-card" id="blog-<?php echo $row['id']; ?>">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 150))); ?>...</p>
            <small>Posted on: <?php echo $row['created_at']; ?></small>
            <span class="status <?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span>
            <div class="actions">
                <?php if ($row['status'] === 'pending'): ?>
                    <a class="edit-btn" href="edit_blog.php?id=<?php echo $row['id']; ?>">✏️ Edit</a>
                <?php endif; ?>
                <span class="delete-btn" data-id="<?php echo $row['id']; ?>">🗑 Delete</span>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No blog posts found. <a href="write_blog.php">Write your first blog</a></p>
<?php endif; ?>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        if(!confirm('Are you sure you want to delete this blog?')) return;

        let blogId = this.getAttribute('data-id');
        let blogDiv = document.getElementById('blog-' + blogId);
        let msgDiv = document.getElementById('msg');

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete_blog.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function(){
            if(xhr.status === 200){
                let response = JSON.parse(xhr.responseText);
                msgDiv.style.display = 'block';
                msgDiv.textContent = response.message;
                msgDiv.className = 'msg ' + (response.status === 'success' ? 'success' : 'error');
                if(response.status === 'success'){
                    blogDiv.remove();
                }
                setTimeout(() => { msgDiv.style.display='none'; }, 3000);
            } else {
                alert('Server error: ' + xhr.status);
            }
        };
        xhr.send('id=' + blogId);
    });
});
</script>

</body>
</html>

<?php $conn->close(); ?>
