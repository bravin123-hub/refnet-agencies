<?php
session_start();
include("connection.php");

// ✅ Restrict to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// ✅ Handle search & filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$role_filter = isset($_GET['role']) ? trim($_GET['role']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';  // default sort
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// ✅ Allowed sortable columns
$sortable = ['id','first_name','last_name','username','balance','role'];
if (!in_array($sort, $sortable)) $sort = 'id';

$query = "SELECT id, first_name, last_name, username, email, phone, balance, role, profile_pic 
          FROM users WHERE 1=1";

$params = [];
$types = "";

// Search by name/username/email
if ($search !== '') {
    $query .= " AND (first_name LIKE ? OR last_name LIKE ? OR username LIKE ? OR email LIKE ?)";
    $like = "%$search%";
    $params = array_merge($params, [$like, $like, $like, $like]);
    $types .= "ssss";
}

// Filter by role
if ($role_filter !== '') {
    $query .= " AND role = ?";
    $params[] = $role_filter;
    $types .= "s";
}

$query .= " ORDER BY $sort $order";

$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// ✅ Export to CSV
if (isset($_GET['export']) && $_GET['export'] == "csv") {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ID','First Name','Last Name','Username','Email','Phone','Balance','Role']);
    while ($row = $result->fetch_assoc()) {
        fputcsv($out, [$row['id'],$row['first_name'],$row['last_name'],$row['username'],
                       $row['email'],$row['phone'],$row['balance'],$row['role']]);
    }
    fclose($out);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Users - Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            margin:0;
            padding:20px;
        }
        h1 {
            text-align:center;
            margin-bottom:20px;
            color:#333;
        }
        .filters {
            text-align:center;
            margin-bottom:20px;
        }
        input, select, button {
            padding:8px 12px;
            margin:5px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:14px;
        }
        button {
            background:#2c3e50;
            color:white;
            cursor:pointer;
        }
        button:hover {
            background:#1a242f;
        }
        table {
            width:100%;
            border-collapse: collapse;
            background:white;
            border-radius:10px;
            box-shadow:0 4px 12px rgba(0,0,0,0.1);
        }
        th, td {
            padding:10px;
            border-bottom:1px solid #ddd;
            text-align:left;
            font-size:14px;
        }
        th {
            background:#2c3e50;
            color:white;
        }
        tr:hover {
            background:#f1f1f1;
        }
        img {
            width:45px;
            height:55px;
            border-radius:6px;
            object-fit:cover;
        }
        .actions a {
            margin-right:8px;
            text-decoration:none;
            color:#2980b9;
            font-weight:bold;
        }
        .top-actions {
            text-align:right;
            margin-bottom:10px;
        }
        .top-actions a {
            background:#27ae60;
            padding:8px 15px;
            color:white;
            border-radius:6px;
            text-decoration:none;
        }
        .top-actions a:hover {
            background:#1e8449;
        }
    </style>
</head>
<body>

<h1>👑 Admin - All Users</h1>

<div class="filters">
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name, username, email" 
               value="<?php echo htmlspecialchars($search); ?>">
        <select name="role">
            <option value="">All Roles</option>
            <option value="user" <?php if($role_filter=="user") echo "selected"; ?>>User</option>
            <option value="admin" <?php if($role_filter=="admin") echo "selected"; ?>>Admin</option>
        </select>
        <button type="submit">Filter</button>
        <a href="users.php" style="margin-left:10px;">Reset</a>
    </form>
</div>

<div class="top-actions">
    <a href="users.php?<?php echo http_build_query(array_merge($_GET,['export'=>'csv'])); ?>">⬇ Export CSV</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Photo</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Balance</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><img src="<?php echo htmlspecialchars($row['profile_pic'] ?: 'uploads/default.png'); ?>"></td>
        <td><?php echo htmlspecialchars($row['first_name']." ".$row['last_name']); ?></td>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['phone']); ?></td>
        <td>KES <?php echo number_format($row['balance'],2); ?></td>
        <td><?php echo htmlspecialchars($row['role']); ?></td>
        <td class="actions">
            <a href="view_user.php?id=<?php echo $row['id']; ?>">View</a> | 
            <a href="edit_user.php?id=<?php echo $row['id']; ?>">Edit</a> | 
            <a href="delete_user.php?id=<?php echo $row['id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
