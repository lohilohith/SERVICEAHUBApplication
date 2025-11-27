<?php
session_start();
require 'db_connect.php';

$msg = "";
$showOTP = false;

// ✅ STEP 1: SEND OTP
if (isset($_POST['send_otp'])) {
    $identifier = trim($_POST['identifier']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (!password_verify($password, $user['password'])) {
            $msg = "<span style='color:red;'>Incorrect password!</span>";
        } else {
            $_SESSION['otp'] = rand(100000, 999999);
            $_SESSION['otp_user'] = $identifier;

            $msg = "<span style='color:green;'>Your OTP is: <b>" . $_SESSION['otp'] . "</b></span>";
            $showOTP = true;
        }

    } else {
        $msg = "<span style='color:red;'>No account found with that email or phone.</span>";
    }
}

// ✅ STEP 2: VERIFY OTP
if (isset($_POST['verify_otp'])) {

    $enteredOTP = trim($_POST['otp']);
    $identifier = $_SESSION['otp_user'];

    if ($enteredOTP == $_SESSION['otp']) {

        $sql = "SELECT * FROM users WHERE email = ? OR phone = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];

        unset($_SESSION['otp']);
        unset($_SESSION['otp_user']);

        header("Location: dashboard.php");
        exit();

    } else {

        // ✅ NEW OTP when wrong
        $_SESSION['otp'] = rand(100000, 999999);

        $msg = "<span style='color:red;'>Incorrect OTP! New OTP: <b>" . $_SESSION['otp'] . "</b></span>";

        $showOTP = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - ServiceHub</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif}

body{
  background: url('login.png') no-repeat center center fixed;
  background-size: cover;
  color:#222;
  animation: fadeIn 1.2s ease-in-out;
}

/* Smooth Fade */
@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity: 1;}
}

/* Header */
header{
  background:rgba(11,99,229,0.75);
  color:#fff;
  padding:15px 8%;
  display:flex;
  justify-content:space-between;
  align-items:center;
  backdrop-filter: blur(6px);
  animation: slideDown 0.7s ease-out;
}

@keyframes slideDown {
  from {transform: translateY(-60px); opacity: 0;}
  to {transform: translateY(0); opacity: 1;}
}

/* Marquee */
.marquee-box {
  width: 100%;
  padding: 10px 0;
  text-align: center;
}
.marquee-text {
  font-size: 20px;
  font-weight: 800;
  background: linear-gradient(90deg, red, black, red);
  -webkit-background-clip: text;
  color: transparent;
  text-shadow:0 0 4px black;
}

/* Login Box */
.container{
  width:380px;
  margin:40px auto;
  background:rgba(255,255,255,0.95);
  padding:25px 30px;
  border-radius:15px;
  box-shadow:0 8px 20px rgba(0,0,0,0.25);
  backdrop-filter: blur(5px);
  animation: popUp 0.9s ease-out;
}

@keyframes popUp {
  0% { transform: scale(0.8); opacity: 0;}
  100% { transform: scale(1); opacity: 1; }
}

.container:hover{
  box-shadow:0 12px 30px rgba(0,0,0,0.35);
}

h2{text-align:center;margin-bottom:20px;color:#0b63e5}

label{font-weight:600;margin-top:10px;display:block}

input{
  width:100%;
  padding:10px;
  border:2px solid transparent;
  background:#f5f5f5;
  margin-top:5px;
  border-radius:5px;
  transition:0.3s;
}

input:focus{
  border:2px solid #0b63e5;
  background:#fff;
  box-shadow:0 0 8px rgba(11,99,229,0.5);
  transform: scale(1.02);
}

.btn{
  width:100%;
  background:#0b63e5;
  color:#fff;
  padding:10px;
  border:none;
  border-radius:8px;
  margin-top:20px;
  cursor:pointer;
  font-size:16px;
  transition:0.3s;
}

.btn:hover{
  background:#084ec0;
  transform:scale(1.05);
  box-shadow:0 6px 12px rgba(0,0,0,0.2);
}

.msg{
  text-align:center;
  margin-top:10px;
  font-weight:600;
}

.forgot-link, .register-link{
  text-align:center;
  margin-top:15px;
}

a:hover{
  color:#084ec0 !important;
  text-decoration: underline;
}

/* Footer */
footer{
  background:rgba(11,99,229,0.85);
  color:#fff;
  text-align:center;
  padding:10px 0;
  position:fixed;
  bottom:0;
  width:100%;
  backdrop-filter: blur(5px);
  animation: slideUp 0.7s ease-out;
}

@keyframes slideUp {
  from {transform: translateY(60px); opacity: 0;}
  to {transform: translateY(0); opacity: 1;}
}
</style>
</head>
<body>

<header>
  <h2>ServiceHub</h2>
  <nav>
    <a href="index.php" style="color:white">Home</a>
    <a href="register.php" style="color:white">Register</a>
  </nav>
</header>

<div class="marquee-box">
  <marquee scrollamount="8">
    <span class="marquee-text">
      𝙈𝙖𝙙𝙚 𝙗𝙮 𝘿𝙞𝙋 𝘽𝙤𝙮𝙨 𝟽𝟷 █▓▒▒░░░ 𝓜𝓪𝓭𝓮 𝓫𝔂 𝓓𝓲𝓟 𝓑𝓸𝔂𝓼 𝟕𝟏 ░░░▒▒▓█
    </span>
  </marquee>
</div>

<div class="container">
  <h2>Login</h2>

  <?php if($msg): ?>
    <p class="msg"><?= $msg ?></p>
  <?php endif; ?>

  <?php if(!$showOTP): ?>

  <form method="POST">
    <label>Email or Phone Number</label>
    <input type="text" name="identifier" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" name="send_otp" class="btn">Send OTP</button>
  </form>

  <div class="forgot-link">
    <a href="forgot_password.php" style="color:#0b63e5">Forgot Password?</a>
  </div>

  <?php else: ?>

  <form method="POST">
    <label>Enter OTP</label>
    <input type="text" name="otp" maxlength="6" required>

    <button type="submit" name="verify_otp" class="btn">Verify OTP & Login</button>
  </form>

  <?php endif; ?>

  <div class="register-link">
    <a href="register.php" style="color:#0b63e5">Create Account</a>
  </div>

</div>

<footer>
  © <?php echo date('Y'); ?> ServiceHub. All rights reserved.
</footer>

</body>
</html>
