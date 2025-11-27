<?php
require 'db_connect.php';
session_start();

$msg = "";
$showOTP = false;
$showReset = false;
$user_id = "";

// ✅ Step 1: Verify Email + Phone
if (isset($_POST['verify'])) {

    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $_SESSION['fp_email'] = $email;
    $_SESSION['fp_phone'] = $phone;

    $sql = "SELECT * FROM users WHERE email = ? AND phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $_SESSION['fp_user_id'] = $user_id;

        // ✅ Generate OTP
        $_SESSION['fp_otp'] = rand(100000, 999999);

        $msg = "<span style='color:green;'>Your OTP is: <b>" . $_SESSION['fp_otp'] . "</b></span>";

        $showOTP = true;

    } else {
        $msg = "<span style='color:red;'>Invalid Email or Phone Number</span>";
    }
}

// ✅ Step 2: Verify OTP
if (isset($_POST['verify_otp'])) {

    $enteredOTP = trim($_POST['otp']);

    if ($enteredOTP == $_SESSION['fp_otp']) {

        $showReset = true;

    } else {

        // ✅ Generate new OTP automatically
        $_SESSION['fp_otp'] = rand(100000, 999999);

        $msg = "<span style='color:red;'>Incorrect OTP! New OTP: <b>" . $_SESSION['fp_otp'] . "</b></span>";

        $showOTP = true;
    }
}

// ✅ Step 3: Reset password
if (isset($_POST['reset'])) {

    $user_id = $_SESSION['fp_user_id'];
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $msg = "<span style='color:red;'>Passwords do not match!</span>";
        $showReset = true;
    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $update_sql = "UPDATE users SET password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed, $user_id);
        $update_stmt->execute();

        unset($_SESSION['fp_otp']);

        header("Location: login.php?reset=success");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif}

body{
  background:url('forgot.png') no-repeat center center fixed;
  background-size:cover;
  animation:fadeIn 0.7s ease;
}

header{
  background:rgba(11,99,229,0.85);
  color:#fff;
  padding:15px 8%;
  display:flex;
  justify-content:space-between;
  align-items:center;
  backdrop-filter:blur(4px);
}

header nav a{
  color:white;
  text-decoration:none;
  margin-left:20px;
  font-weight:500;
}

.container{
  width:380px;
  margin:80px auto;
  background:rgba(255,255,255,0.9);
  padding:25px 30px;
  border-radius:10px;
  box-shadow:0 4px 15px rgba(0,0,0,.2);
  animation:fadeUp .8s ease forwards;
  backdrop-filter:blur(3px);
}

h2{text-align:center;margin-bottom:20px;color:#0b63e5}

form label{font-weight:600;margin-top:10px;display:block}
form input{
  width:100%;
  padding:10px;
  margin-top:5px;
  border:1px solid #ccc;
  border-radius:5px;
}

.btn{
  width:100%;
  background:#0b63e5;
  color:#fff;
  border:none;
  padding:10px;
  margin-top:20px;
  cursor:pointer;
  font-size:16px;
  border-radius:5px;
}
.btn:hover{background:#084ec0}

.msg{text-align:center;color:red;margin-top:10px;font-weight:600}
.success{color:green}

footer{
  background:rgba(11,99,229,0.85);
  color:#fff;
  text-align:center;
  padding:10px 0;
  position:fixed;
  bottom:0;
  width:100%;
}

@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
</style>

</head>
<body>

<header>
  <h2>ServiceHub</h2>
  <nav>
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
  </nav>
</header>

<div class="container">

  <h2>Forgot Password</h2>

  <?php if($msg): ?>
    <p class="msg"><?= $msg ?></p>
  <?php endif; ?>

  <!-- ✅ Step 1 -->
  <?php if(!$showOTP && !$showReset): ?>
  <form method="POST">
    <label>Email</label>
    <input type="email" name="email" required>

    <label>Phone Number</label>
    <input type="text" name="phone" required>

    <button class="btn" name="verify" type="submit">Send OTP</button>
  </form>
  <?php endif; ?>

  <!-- ✅ Step 2 -->
  <?php if($showOTP): ?>
  <form method="POST">
    <label>Enter OTP</label>
    <input type="text" name="otp" required maxlength="6">

    <button class="btn" name="verify_otp" type="submit">Verify OTP</button>
  </form>
  <?php endif; ?>

  <!-- ✅ Step 3 -->
  <?php if($showReset): ?>
  <form method="POST">

    <label>New Password</label>
    <input type="password" name="password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm" required>

    <button class="btn" name="reset" type="submit">Reset Password</button>
  </form>
  <?php endif; ?>

</div>

<footer>
  &copy; <?php echo date('Y'); ?> ServiceHub.
</footer>

</body>
</html>
