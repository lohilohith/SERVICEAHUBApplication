<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>
<style>
table {width:100%; border-collapse: collapse;}
th, td {padding:10px; border:1px solid #444;}
th {background:#eee;}
</style>
</head>
<body>

<h2>User Management</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Actions</th>
</tr>

<?php while($row = $users->fetch_assoc()) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['fullname'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['phone'] ?></td>
    <td>
        <a href="admin_edit_user.php?id=<?= $row['id'] ?>">Edit</a> | 
        <a href="admin_delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete user?')">Delete</a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
