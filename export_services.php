<?php
require 'db_connect.php';

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=services_report.xls");
header("Pragma: no-cache");
header("Expires: 0");

$services = $conn->query("
    SELECT s.*, u.fullname, u.email
    FROM services s
    JOIN users u ON s.user_id = u.id
");

echo "<table border='1'>";
echo "<tr>
        <th>ID</th>
        <th>User</th>
        <th>Email</th>
        <th>Service</th>
        <th>Description</th>
        <th>Price</th>
        <th>Status</th>
        <th>Created</th>
      </tr>";

while($row = $services->fetch_assoc()) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['fullname']}</td>
            <td>{$row['email']}</td>
            <td>{$row['service_name']}</td>
            <td>{$row['description']}</td>
            <td>{$row['price']}</td>
            <td>{$row['status']}</td>
            <td>{$row['created_at']}</td>
          </tr>";
}

echo "</table>";
?>
