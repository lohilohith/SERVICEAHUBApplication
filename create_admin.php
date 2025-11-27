<?php
// create_admin.php - remove or protect after use
require_once "db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($fullname === '' || $email === '' || $password === '') {
        $err = "All fields required.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO serviceadmin (fullname, email, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            $err = "DB error: " . $conn->error;
        } else {
            $stmt->bind_param("sss", $fullname, $email, $hash);
            if ($stmt->execute()) {
                $ok = "Admin created.";
            } else {
                $err = "Insert failed: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Create Admin</title></head><body>
<h2>Create Admin</h2>
<?php if (!empty($err)) echo "<div style='color:red'>$err</div>"; ?>
<?php if (!empty($ok)) echo "<div style='color:green'>$ok</div>"; ?>
<form method="post">
    <input name="fullname" placeholder="Full name"><br>
    <input name="email" placeholder="Email"><br>
    <input name="password" placeholder="Password" type="password"><br>
    <button>Create</button>
</form>
</body></html>
