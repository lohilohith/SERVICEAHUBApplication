<?php
require 'db_connect.php';
$conn->query("TRUNCATE TABLE services");
echo "<h2>All services cleared!</h2><a href='admin_dashboard.php'>Back</a>";
