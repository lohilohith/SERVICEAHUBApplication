<?php
session_start();

// If not logged in, redirect
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// Get admin name
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : "Admin";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .box {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 2px solid black;
            text-align: center;
        }
        .logout {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Welcome, <?php echo $admin_name; ?> 👋</h2>

    <p>You are now logged in.</p>

    <a class="logout" href="logout.php">Logout</a>
</div>

</body>
</html>
