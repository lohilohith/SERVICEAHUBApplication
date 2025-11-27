<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$search = "";
$where = "";

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = $_GET['search'];
    $where = "WHERE u.fullname LIKE '%$search%' 
              OR s.service_name LIKE '%$search%'
              OR s.status LIKE '%$search%'";
}

$services = $conn->query("
    SELECT s.*, u.fullname, u.email 
    FROM services s
    JOIN users u ON s.user_id = u.id
    $where
    ORDER BY s.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Services</title>
<style>
table {width:100%; border-collapse: collapse;}
th, td {padding:10px; border:1px solid #444;}
th {background:#eee;}
</style>
</head>
<body>

<h2>All Services</h2>

<form method="GET">
    <input type="text" name="search" value="<?= $search ?>" placeholder="Search service or user">
    <button type="submit">Search</button>
    <a href="export_services.php">Export to Excel</a>
</form>

<br>

<table>
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Service</th>
    <th>Description</th>
    <th>Price</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while($row = $services->fetch_assoc()) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['fullname'] ?></td>
    <td><?= $row['service_name'] ?></td>
    <td><?= $row['description'] ?></td>
    <td><?= $row['price'] ?></td>
    <td><?= $row['status'] ?></td>
    <td>
        <a href="service_approve.php?id=<?= $row['id'] ?>">Approve</a> |
        <a href="service_reject.php?id=<?= $row['id'] ?>">Reject</a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
