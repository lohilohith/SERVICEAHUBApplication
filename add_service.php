<?php
session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $user_id = $_SESSION['user_id'];  // Logged-in user

    $query = "INSERT INTO services (user_id, service_name, description, price)
              VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $service_name, $description, $price);

    if ($stmt->execute()) {
        echo "<script>alert('Service Added Successfully!'); window.location='view_services.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
