<?php
session_start();
require 'db_connect.php';

// STOP if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// GET user data from DB
$sql = "SELECT fullname, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    echo "User not found!";
    exit();
}

$user = $res->fetch_assoc();

// Determine emoji based on name (simple heuristic)
$firstLetter = strtolower(substr($user['fullname'], -1));
if (in_array($firstLetter, ['a','i','e'])) {
    $emoji = "👧"; // girl
} else {
    $emoji = "👦"; // boy
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile - 71ServiceHub</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('profile.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .brand {
            font-size: 40px;
            font-weight: bold;
            color: #fff;
            text-shadow: 3px 3px 10px rgba(0,0,0,0.7);
            margin-bottom: 30px;
        }

        .profile-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 50px 30px 30px 30px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
            text-align: center;
            width: 350px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .profile-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: #f0f0f0;
            margin: -90px auto 20px auto;
            border: 5px solid #fff;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            font-size: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .profile-card h2 {
            margin: 15px 0 25px 0;
            color: #333;
        }

        .profile-card p {
            font-size: 18px;
            margin: 10px 0;
            color: #555;
        }

        .profile-card b {
            color: #222;
        }

        @media (max-width: 400px) {
            .profile-card {
                width: 90%;
                padding: 40px 20px 20px 20px;
            }

            .brand {
                font-size: 32px;
            }

            .profile-card p {
                font-size: 16px;
            }

            .avatar {
                width: 100px;
                height: 100px;
                font-size: 50px;
                margin: -70px auto 20px auto;
            }
        }
    </style>
</head>
<body>

<div class="brand">71ServiceHub</div>

<div class="profile-card">
    <div class="avatar"><?php echo $emoji; ?></div>
    <h2><?php echo $user['fullname']; ?></h2>
    <p><b>Email:</b> <?php echo $user['email']; ?></p>
    <p><b>Phone:</b> <?php echo $user['phone']; ?></p>
</div>

</body>
</html>
