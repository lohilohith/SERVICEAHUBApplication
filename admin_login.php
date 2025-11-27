<?php
session_start();
require_once "db_connect.php";

$msg = "";
$step = "enter_identifier";

// STEP 1 → Admin enters phone or email
if (isset($_POST['send_otp'])) {

    $identifier = trim($_POST['identifier']);

    if ($identifier == "") {
        $msg = "Enter email or phone number.";
    } else {
        // Find admin
        $sql = "SELECT * FROM serviceadmin WHERE email = ? OR phone = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $admin = $result->fetch_assoc();

            // Generate OTP (for now show directly)
            $otp = rand(100000, 999999);

            $_SESSION['admin_temp_id'] = $admin['id'];
            $_SESSION['admin_otp'] = $otp;

            // Show OTP on screen (temporary)
            $msg = "Your OTP is: <b>$otp</b>";

            $step = "enter_otp";

        } else {
            $msg = "Admin not found!";
        }
    }
}

// STEP 2 → Verify OTP
if (isset($_POST['verify_otp'])) {

    $otp_entered = trim($_POST['otp']);

    if ($otp_entered == "") {
        $msg = "Enter OTP.";
        $step = "enter_otp";
    } else {
        if (isset($_SESSION['admin_otp']) && $otp_entered == $_SESSION['admin_otp']) {

            // Successful login
            $_SESSION['admin_id'] = $_SESSION['admin_temp_id'];

            // Clear temp otp
            unset($_SESSION['admin_temp_id']);
            unset($_SESSION['admin_otp']);

            header("Location: admin_dashboard.php");
            exit;

        } else {
            $msg = "Incorrect OTP!";
            $step = "enter_otp";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Admin OTP Login</title>
    <style>
        body{font-family:Arial;background:#f5f5f5;padding:40px;}
        form{background:white;padding:20px;border-radius:10px;
             max-width:350px;box-shadow:0 0 10px #0001;}
        input{width:100%;padding:10px;margin:10px 0;border-radius:5px;border:1px solid #aaa;}
        button{width:100%;padding:12px;background:#007bff;border:none;color:white;
               border-radius:5px;font-size:16px;cursor:pointer;}
        .msg{margin-bottom:10px;font-weight:bold;color:#d00;}
    </style>
</head>
<body>

<h2>Admin Login</h2>

<?php if ($msg): ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php endif; ?>

<!-- STEP 1: ENTER EMAIL/PHONE -->
<?php if ($step === "enter_identifier"): ?>
<form method="POST">
    <label>Enter Email or Phone:</label>
    <input type="text" name="identifier" placeholder="admin@gmail.com / 9876543210" required>
    <button type="submit" name="send_otp">Get OTP</button>
</form>
<?php endif; ?>

<!-- STEP 2: ENTER OTP -->
<?php if ($step === "enter_otp"): ?>
<form method="POST">
    <label>Enter OTP:</label>
    <input type="number" name="otp" placeholder="6-digit OTP" required>
    <button type="submit" name="verify_otp">Verify OTP</button>
</form>
<?php endif; ?>

</body>
</html>
