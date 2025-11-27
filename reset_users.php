<?php
require 'db_connect.php';
$conn->query("DELETE FROM users");
echo "<h2>All users deleted!</h2><a href='admin_dashboard.php'>Back</a>";
