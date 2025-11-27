<?php
require 'db_connect.php';

if (!isset($_GET['id'])) die("Invalid request");

$id = intval($_GET['id']);
$conn->query("DELETE FROM services WHERE id = $id");

header("Location: admin_dashboard.php");
exit;
