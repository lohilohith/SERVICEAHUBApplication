<?php
require 'db_connect.php';
session_start();

$msg = "";
$showOTP = false;

// ✅ Step 1: SEND OTP
if (isset($_POST['send_otp'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    $_SESSION['reg_fullname'] = $fullname;
    $_SESSION['reg_email'] = $email;
    $_SESSION['reg_phone'] = $phone;
    $_SESSION['reg_password'] = $password;

    if ($password !== $confirm) {
        $msg = "<span style='color:red;'>Passwords do not match!</span>";
    } else {
        $_SESSION['reg_otp'] = rand(100000, 999999);
        $msg = "<span style='color:green;'>Your OTP is: <b>" . $_SESSION['reg_otp'] . "</b></span>";
        $showOTP = true;
    }
}

// ✅ Step 2: VERIFY OTP & REGISTER
if (isset($_POST['verify_otp'])) {

    $enteredOTP = trim($_POST['otp']);

    if ($enteredOTP == $_SESSION['reg_otp']) {

        $fullname = $_SESSION['reg_fullname'];
        $email = $_SESSION['reg_email'];
        $phone = $_SESSION['reg_phone'];
        $password = password_hash($_SESSION['reg_password'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (fullname, email, phone, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullname, $email, $phone, $password);

        if ($stmt->execute()) {
            unset($_SESSION['reg_otp']);
            unset($_SESSION['reg_email']);

            $msg = "<span style='color:green;'>Registration successful! You can login.</span>";
        } else {
            $msg = "<span style='color:red;'>Email or phone already registered.</span>";
        }

    } else {

        // ✅ Generate new OTP if user enters wrong OTP
        $_SESSION['reg_otp'] = rand(100000, 999999);

        $msg = "<span style='color:red;'>Incorrect OTP! New OTP: <b>" . $_SESSION['reg_otp'] . "</b></span>";

        $showOTP = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register - ServiceHub</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif}

body{
  background:url('register.png') no-repeat center center fixed;
  background-size:cover;
  animation:fadeBg 2s ease-in-out;
}

@keyframes fadeBg{
  from{opacity:0;}
  to{opacity:1;}
}

/* Floating circles */
.circle{
  position:fixed;
  border-radius:50%;
  background:rgba(255,255,255,0.25);
  animation: float 6s infinite ease-in-out alternate;
}

.circle.small{width:80px;height:80px;top:20%;left:10%;}
.circle.medium{width:120px;height:120px;top:60%;left:75%;}
.circle.large{width:180px;height:180px;top:30%;left:50%;}

@keyframes float{
  0%{transform:translateY(0);}
  100%{transform:translateY(-40px);}
}

/* Header */
header{
  background:rgba(0,0,0,0.55);
  color:#fff;
  padding:15px 8%;
  display:flex;
  justify-content:space-between;
  align-items:center;
  backdrop-filter:blur(4px);
  animation:slideDown 0.8s;
}

@keyframes slideDown{
  from{transform:translateY(-50px);opacity:0;}
  to{transform:translateY(0);opacity:1;}
}

/* Form Box */
.container{
  width:420px;
  margin:70px auto;
  background:rgba(255,255,255,0.92);
  padding:30px;
  border-radius:15px;
  backdrop-filter:blur(6px);
  box-shadow:0 10px 30px rgba(0,0,0,0.3);
  animation:popUp 0.9s ease-out;
}

@keyframes popUp{
  0%{transform:scale(0.85);opacity:0;}
  100%{transform:scale(1);opacity:1;}
}

h2{
  text-align:center;
  color:#0b63e5;
  font-weight:700;
  margin-bottom:20px;
  letter-spacing:1px;
}

label{font-weight:600;margin-top:10px;display:block}

input{
  width:100%;
  padding:12px;
  margin-top:5px;
  border-radius:8px;
  border:2px solid transparent;
  background:#f3f3f3;
  transition:0.3s;
}

input:focus{
  border-color:#0b63e5;
  background:#fff;
  box-shadow:0 0 10px rgba(11,99,229,0.5);
  transform:scale(1.02);
}

.btn{
  width:100%;
  background:#0b63e5;
  color:white;
  padding:12px;
  margin-top:20px;
  border:none;
  border-radius:10px;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
  transition:0.3s;
}

.btn:hover{
  background:#084ec0;
  transform:scale(1.05);
  box-shadow:0 6px 15px rgba(0,0,0,0.25);
}

.msg{text-align:center;margin-top:10px;font-weight:600}

/* Footer */
footer{
  background:rgba(0,0,0,0.6);
  color:#fff;
  text-align:center;
  padding:10px;
  position:fixed;
  bottom:0;
  width:100%;
  backdrop-filter:blur(5px);
}
</style>
</head>

<body>

<!-- Floating animation circles -->
<div class="circle small"></div>
<div class="circle medium"></div>
<div class="circle large"></div>

<header>
  <h2>ServiceHub</h2>
  <nav>
    <a href="index.php" style="color:white;margin-right:15px;">Home</a>
    <a href="login.php" style="color:white;">Login</a>
  </nav>
</header>

<div class="container">
  <h2>Create Account</h2>

  <?php if($msg): ?>
    <p class="msg"><?= $msg ?></p>
  <?php endif; ?>

  <?php if(!$showOTP): ?>

  <!-- ✅ Step 1 Form -->
  <form method="POST">
    <label>Full Name</label>
    <input type="text" name="fullname" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Phone</label>
    <input type="text" name="phone" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm" required>

    <button type="submit" name="send_otp" class="btn">Send OTP</button>
  </form>

  <?php else: ?>

  <!-- ✅ Step 2 OTP Form -->
  <form method="POST">
    <label>Enter OTP</label>
    <input type="text" name="otp" required maxlength="6">

    <button type="submit" name="verify_otp" class="btn">Verify OTP & Register</button>
  </form>

  <?php endif; ?>

  <div style="text-align:center;margin-top:15px;">
    Already have an account? <a href="login.php" style="color:#0b63e5;font-weight:600;">Login</a>
  </div>

</div>

<footer>
  © <?php echo date('Y'); ?> ServiceHub. All rights reserved.
</footer>

</body>
</html>
