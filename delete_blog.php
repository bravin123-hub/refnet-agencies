<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include __DIR__ . "/connection.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_POST['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$delete_id = intval($_POST['id']);

$stmt = $conn->prepare("DELETE FROM blogs WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $delete_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => '✅ Blog deleted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '❌ Blog not found or not yours']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => '❌ Error deleting blog']);
}

$stmt->close();
$conn->close();
?>
