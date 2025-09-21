<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id   = $_SESSION['user_id'];
    $full_name = trim($_POST['full_name']);
    $id_number = trim($_POST['id_number']);
    $file      = $_FILES['id_file'];

    // ✅ reCAPTCHA Verification
    $secretKey = "6LdayM4rAAAAAPm2xaGoZOU4k3SuS81HzlsiPMlI"; // replace with your secret key
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];

    $verifyURL = "https://www.google.com/recaptcha/api/siteverify";
    $response = file_get_contents($verifyURL . "?secret=" . $secretKey . "&response=" . $responseKey . "&remoteip=" . $userIP);
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        $message = "❌ reCAPTCHA verification failed. Please try again.";
    } else {
        // ✅ File Validation
        if ($file['error'] === 0) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== "pdf") {
                $message = "❌ Only PDF files allowed.";
            } else {
                $newFileName = "uploads/id_" . $user_id . "_" . time() . ".pdf";
                if (!is_dir("uploads")) {
                    mkdir("uploads", 0777, true);
                }
                if (move_uploaded_file($file['tmp_name'], $newFileName)) {
                    // ✅ Save Loan Request in DB
                    $stmt = $conn->prepare("INSERT INTO loan_requests (user_id, full_name, id_number, id_file) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("isss", $user_id, $full_name, $id_number, $newFileName);

                    if ($stmt->execute()) {
                        $message = "✅ Loan request submitted successfully. Pending admin verification.";
                    } else {
                        $message = "❌ Database error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "❌ Failed to upload file.";
                }
            }
        } else {
            $message = "❌ File upload error.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Loan Request</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        label { display: block; margin-top: 10px; }
        input, button { width: 100%; padding: 10px; margin-top: 5px; }
        .g-recaptcha { margin-top: 15px; }
        .msg { margin-top: 15px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>Loan Request Form</h2>
    <?php if ($message): ?>
        <p class="msg"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Full Name:</label>
        <input type="text" name="full_name" required>

        <label>ID Number:</label>
        <input type="text" name="id_number" required>

        <label>Upload Scanned ID (PDF only):</label>
        <input type="file" name="id_file" accept="application/pdf" required>

        <div class="g-recaptcha" data-sitekey="6LdayM4rAAAAAPm2xaGoZOU4k3SuS81HzlsiPMlI"></div>

        <button type="submit">Submit Loan Request</button>
    </form>
</div>
</body>
</html>
