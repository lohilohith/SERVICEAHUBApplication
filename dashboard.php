<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard - ServiceHub</title>
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }
    body {
      background: url('dashbord.png') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    header {
      background: rgba(0, 0, 0, 0.75);
      color: #fff;
      padding: 15px 8%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #0b63e5;
    }
    header h2 {
      font-size: 26px;
      letter-spacing: 1px;
    }
    nav a {
      color: #fff;
      text-decoration: none;
      margin-left: 20px;
      font-weight: 500;
      transition: 0.3s;
    }
    nav a:hover {
      color: #e50b0bff;
    }

    .welcome {
      text-align: center;
      margin-top: 40px;
      background: rgba(0, 0, 0, 0.5);
      padding: 20px;
      border-radius: 10px;
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }
    .welcome h1 {
      color: #e50b0bff;
      font-size: 36px;
      margin-bottom: 10px;
    }

    .services {
      padding: 40px 8%;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }
    .service {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      overflow: hidden;
      text-align: center;
      padding: 15px;
    }
    .service h3 {
      margin-bottom: 10px;
      color: #e50b0bff;
    }
    video {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .book-btn {
      display: block;
      width: 220px;
      margin: 40px auto;
      text-align: center;
      padding: 12px 0;
      background: #0b63e5;
      color: #fff;
      font-weight: bold;
      text-decoration: none;
      border-radius: 8px;
      transition: 0.3s;
    }
    .book-btn:hover {
      background: #094ec0;
      transform: scale(1.05);
    }

    footer {
      background: rgba(0, 0, 0, 0.8);
      color: #fff;
      text-align: center;
      padding: 10px 0;
      border-top: 2px solid #0b63e5;
      margin-top: auto;
    }
  </style>
</head>
<body>

<header>
  <h2>ServiceHub Dashboard</h2>
  <nav>
    <a href="index.php">Home</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>
<a href="admin_login.php" 
   style="padding:10px 20px; background:#007bff; color:white; text-decoration:none; border-radius:5px;">
   Admin
</a>


<section class="welcome">
  <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?> 👋</h1>
  <p>Manage your vehicle services and explore our offerings below.</p>
</section>

<section class="services">
  <div class="service">
    <h3>2-Wheeler Services 🏍️</h3>
    <video autoplay loop muted playsinline>
      <source src="2wheeler.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
  <div class="service">
    <h3>3-Wheeler Services 🛺</h3>
    <video autoplay loop muted playsinline>
      <source src="3wheeler.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
  <div class="service">
    <h3>4-Wheeler Services 🚗</h3>
    <video autoplay loop muted playsinline>
      <source src="4wheeler.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </div>
</section>

<a href="view_services.php" class="book-btn">Book My Service</a>

<footer>
  &copy; <?php echo date('Y'); ?> ServiceHub. All rights reserved.
</footer>

</body>
</html>
