<?php
session_start();
include("includes/connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Handle new blog submission
if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $url = trim($_POST['url']);
    $content = trim($_POST['content']); // HTML content from TinyMCE
    $image_name = "";

    // Validate word count
    $word_count = str_word_count(strip_tags($content));
    if ($word_count < 1000) {
        $msg = "⚠️ Blog content must be at least 1000 words. Currently: $word_count words.";
    } elseif (!empty($_FILES['image']['name'])) {
        // Handle image upload
        $target_dir = "uploads/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false && in_array($imageFileType, ['jpg','jpeg','png','gif'])) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            $msg = "❌ Invalid image file.";
        }
    }

    if ($msg === "") {
        $stmt = $conn->prepare("INSERT INTO blogs (user_id, title, url, image, content) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $title, $url, $image_name, $content);
        if ($stmt->execute()) {
            $msg = "✅ Blog posted successfully!";
        } else {
            $msg = "❌ Error posting blog.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Write Blog - RefNet</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/z6dswyoimkmd75p8ukrxl7zdrpw9396geeiwvo7fpmyt556b/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    tinymce.init({
        selector: '#content',
        plugins: 'lists link image table code fullscreen wordcount advlist emoticons textcolor',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | emoticons | code fullscreen',
        menubar: false,
        branding: false,
        height: 500,
        content_style: "body { font-family:Roboto,sans-serif; font-size:14px; line-height:1.6; color:#333; }"
    });

    // Ensure TinyMCE content is saved on submit
    document.querySelector("form").addEventListener("submit", function() {
        tinymce.triggerSave();
    });

    // Auto-generate URL slug from title
    document.getElementById('title').addEventListener('input', function() {
        let title = this.value.trim().toLowerCase();
        let slug = title.replace(/[^a-z0-9\s]/g, '')   // remove invalid chars
                        .replace(/\s+/g, '-')          // spaces to hyphens
                        .replace(/-+/g, '-');          // remove multiple hyphens
        document.getElementById('url').value = slug;
    });
});
</script>

<style>
body { font-family: 'Roboto', sans-serif; background: #eef2f7; margin:0; padding:30px; }
.container { max-width: 950px; margin:auto; background:white; padding:30px; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.15); margin-bottom:50px; }
h2 { text-align:center; color:#2c3e50; margin-bottom:20px; }
input[type=text], input[type=url], input[type=file], textarea { width:100%; padding:12px; margin:10px 0 20px 0; border-radius:6px; border:1px solid #ccc; font-size:16px; }
small { color:#555; display:block; margin-top:-15px; margin-bottom:15px; }
button { padding:10px 20px; background:#27ae60; color:white; border:none; border-radius:6px; cursor:pointer; font-size:16px; transition:all 0.3s ease; margin-top:5px; }
button:hover { background:#1e8449; transform:scale(1.03); }
.msg { margin-bottom:15px; font-weight:bold; padding:10px; border-radius:6px; }
.msg.success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.msg.error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }
</style>
</head>
<body>

<div class="container">
<h2>📝 Write a New Blog</h2>
<?php if ($msg) echo "<div class='msg ".(strpos($msg,'successfully')!==false?"success":"error")."'>".$msg."</div>"; ?>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" id="title" placeholder="Blog Title" required>
    <input type="url" name="url" id="url" placeholder="e.g. my-first-blog" required>
    <small>The URL should be lowercase, use hyphens instead of spaces, and contain only letters and numbers.</small>
    <input type="file" name="image" accept="image/*" required>
    <textarea id="content" name="content" placeholder="Start writing your blog..."></textarea>
    <button type="submit" name="submit">Post Blog</button>
</form>
</div>

</body>
</html>
