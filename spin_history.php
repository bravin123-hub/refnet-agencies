<?php
session_start();
include("includes/connection.php");

// Check login
if (!isset($_SESSION['email'])) {
    echo "Please log in to view spin history.";
    exit();
}

$user_email = $_SESSION['email'];

// Get user ID
$result = $conn->query("SELECT id FROM users WHERE email='$user_email' LIMIT 1");
if(!$result || $result->num_rows == 0){
    echo "User not found.";
    exit();
}
$row = $result->fetch_assoc();
$user_id = $row['id'];

// Fetch spin history
$query = $conn->prepare("SELECT spin_type, reward, spin_time FROM spin_history WHERE user_id=? ORDER BY spin_time DESC");
$query->bind_param("i", $user_id);
$query->execute();
$res = $query->get_result();

echo "<h2>Your Spin History</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>#</th><th>Spin Type</th><th>Prize</th><th>Date</th></tr>";

if ($res->num_rows == 0) {
    echo "<tr><td colspan='4'>No spins yet!</td></tr>";
} else {
    $count = 1;
    while($row = $res->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$count."</td>";
        echo "<td>".htmlspecialchars($row['spin_type'])."</td>";
        echo "<td>".htmlspecialchars($row['reward'])."</td>";
        echo "<td>".htmlspecialchars($row['spin_time'])."</td>";
        echo "</tr>";
        $count++;
    }
}

echo "</table>";
?>
