<?php
session_start();
require 'db_connect.php';

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'] ?? "Admin";

// -------------------------------------
// FETCH TOTAL COUNTS
// -------------------------------------

// total users
$total_users = $conn->query("SELECT COUNT(*) AS c FROM users")->fetch_assoc()['c'];

// total bookings
$total_bookings = $conn->query("SELECT COUNT(*) AS c FROM service_bookings")->fetch_assoc()['c'];

// latest 10 users
$latest_users = $conn->query("
    SELECT id, fullname, email, phone, created_at 
    FROM users 
    ORDER BY id DESC 
    LIMIT 10
");

// latest service bookings
$service_q = "
    SELECT 
        id,
        customer_name,
        vehicle_number,
        vehicle_type,
        service_category,
        service_details,
        service_duration,
        payment_mode,
        total_bill,
        booking_time
    FROM service_bookings
    ORDER BY id DESC
    LIMIT 20
";

$latest_services = $conn->query($service_q);

// -------------------------------------
// DELETE SINGLE BOOKING
// -------------------------------------
if (isset($_GET['delete_id'])) {
    $did = intval($_GET['delete_id']);
    $conn->query("DELETE FROM service_bookings WHERE id = $did");
    header("Location: admin_dashboard.php");
    exit();
}

// -------------------------------------
// RESET ALL BOOKINGS
// -------------------------------------
if (isset($_GET['reset_all'])) {
    $conn->query("TRUNCATE TABLE service_bookings");
    header("Location: admin_dashboard.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <style>
        body { font-family: Arial; margin: 20px; }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: #004aad;
            color: white;
            border-radius: 5px;
        }

        .header div a {
            margin-left: 10px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
        }

        .home-btn { background: #28a745; } /* green */
        .logout-btn { background: #dc3545; } /* red */

        table {
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
        }

        th, td {
            padding: 10px; 
            border: 1px solid #ddd;
        }

        th {
            background: #f2f2f2;
        }

        .box {
            display: inline-block;
            padding: 15px;
            margin: 10px;
            background: #eef;
            border-radius: 8px;
            font-size: 18px;
        }

        .btn-del {
            padding: 5px 10px;
            background: red;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .btn-reset {
            padding: 8px 10px;
            background: #000;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>

</head>
<body>

<div class="header">
    <h2>Welcome, <?php echo $admin_name; ?></h2>
    <div>
        <a href="index.php" class="home-btn">Home</a>
        <a href="admin_logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<br>

<h2>Dashboard Summary</h2>

<div class="box">Total Users: <b><?php echo $total_users; ?></b></div>
<div class="box">Total Bookings: <b><?php echo $total_bookings; ?></b></div>

<hr>

<!-- ================= USERS TABLE ================ -->

<h2>Latest Registered Users</h2>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Registered At</th>
</tr>

<?php while ($u = $latest_users->fetch_assoc()) { ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= $u['fullname'] ?></td>
    <td><?= $u['email'] ?></td>
    <td><?= $u['phone'] ?></td>
    <td><?= $u['created_at'] ?></td>
</tr>
<?php } ?>

</table>

<hr>

<!-- ================= BOOKINGS TABLE ================ -->

<h2>Latest Service Bookings</h2>

<a href="admin_dashboard.php?reset_all=1" class="btn-reset"
   onclick="return confirm('Delete ALL bookings?');">
   RESET ALL BOOKINGS
</a>

<table>
<tr>
    <th>ID</th>
    <th>Customer</th>
    <th>Vehicle No</th>
    <th>Vehicle Type</th>
    <th>Category</th>
    <th>Details</th>
    <th>Duration</th>
    <th>Payment Mode</th>
    <th>Total Bill</th>
    <th>Time</th>
    <th>Action</th>
</tr>

<?php 
if ($latest_services->num_rows > 0) {
while ($s = $latest_services->fetch_assoc()) { ?>
<tr>
    <td><?= $s['id'] ?></td>
    <td><?= $s['customer_name'] ?></td>
    <td><?= $s['vehicle_number'] ?></td>
    <td><?= $s['vehicle_type'] ?></td>
    <td><?= $s['service_category'] ?></td>
    <td><?= $s['service_details'] ?></td>
    <td><?= $s['service_duration'] ?></td>
    <td><?= $s['payment_mode'] ?></td>
    <td><?= $s['total_bill'] ?></td>
    <td><?= $s['booking_time'] ?></td>

    <td>
        <a class="btn-del" 
           href="admin_dashboard.php?delete_id=<?= $s['id'] ?>"
           onclick="return confirm('Delete this booking?');">
           Delete
        </a>
    </td>
</tr>
<?php }} else { ?>

<tr>
    <td colspan="11" style="text-align:center;">No bookings found</td>
</tr>

<?php } ?>

</table>

</body>
</html>
