<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ServiceHub - Vehicle Service Management</title>

  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }
    body {
      background: url('bg.png') no-repeat center center fixed;
      background-size: cover;
      color: #fff;
      min-height: 100vh;
    }
    header {
      background: rgba(0, 0, 0, 0.7);
      color: #fff;
      padding: 15px 8%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #e50b0bff;
    }
    header h2 {
      font-size: 26px;
      letter-spacing: 1px;
    }
    nav a {
      color: #ffffffff;
      text-decoration: none;
      margin-left: 20px;
      font-weight: 500;
      transition: 0.3s;
    }
    nav a:hover {
      color: #0b63e5;
    }

    /* Moving marquee animation */
    .marquee-container {
      overflow: hidden;
      white-space: nowrap;
      background: rgba(0, 0, 0, 0.6);
      border-top: 1px solid #0b63e5;
      border-bottom: 1px solid #0b63e5;
    }
    .marquee-text {
      display: inline-block;
      color: #e50b0bff;
      font-weight: bold;
      font-size: 16px;
      padding: 10px 0;
      animation: move 15s linear infinite;
    }
    @keyframes move {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    .hero {
      text-align: center;
      padding: 60px 20px;
      background: rgba(0, 0, 0, 0.5);
    }
    .hero h1 {
      font-size: 42px;
      color: #e50b0bff;
      margin-bottom: 15px;
    }
    .hero p {
      font-size: 18px;
      max-width: 700px;
      margin: auto;
      color: #fff;
      margin-bottom: 30px;
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
      color: #0b63e5;
    }
    video {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 10px;
    }

    .contact {
      padding: 40px 8%;
      text-align: center;
      background: rgba(0, 0, 0, 0.6);
    }
    iframe {
      width: 100%;
      height: 300px;
      border: 0;
      border-radius: 10px;
      margin-top: 20px;
    }

    footer {
      background: rgba(0, 0, 0, 0.8);
      color: #fff;
      text-align: center;
      padding: 10px 0;
      border-top: 2px solid #0b63e5;
    }

    /* ---------------- AI CHATBOX ---------------- */
    #chatIcon {
      width: 60px;
      height: 60px;
      background: #0b63e5;
      border-radius: 50%;
      position: fixed;
      bottom: 25px;
      right: 25px;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-size: 28px;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0,0,0,0.4);
      z-index: 999;
    }

    #chatBox {
      width: 320px;
      height: 450px;
      background: rgba(0,0,0,0.85);
      border: 2px solid #0b63e5;
      border-radius: 12px;
      position: fixed;
      bottom: 95px;
      right: 25px;
      display: none;
      flex-direction: column;
      color: white;
      z-index: 999;
    }

    #chatHeader {
      padding: 12px;
      background: #0b63e5;
      border-radius: 12px 12px 0 0;
      text-align: center;
      font-size: 18px;
      font-weight: bold;
    }

    #messages {
      flex: 1;
      padding: 12px;
      overflow-y: auto;
      font-size: 14px;
    }

    .msg {
      margin-bottom: 10px;
      padding: 8px;
      border-radius: 8px;
      max-width: 85%;
      word-wrap: break-word;
    }

    .user {
      background: #e50b0b;
      margin-left: auto;
    }

    .bot {
      background: #333;
      margin-right: auto;
    }

    #chatInput {
      width: 100%;
      padding: 10px;
      border: none;
      outline: none;
      background: #111;
      color: white;
      font-size: 14px;
      border-radius: 0 0 12px 12px;
    }
  </style>
</head>
<body>

<header>
  <h2> 🛠️ServiceHub🧑‍🔧</h2>
  <nav>
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>
    <a href="profile.php">Profile</a>
  </nav>
</header>

<!-- Moving marquee -->
<div class="marquee-container">
  <div class="marquee-text">
         𝙈𝙖𝙙𝙚 𝙗𝙮 𝘿𝙞𝙋 𝘽𝙤𝙮𝙨 𝟽𝟷  █▓▒▒░░░  𝓜𝓪𝓭𝓮 𝓫𝔂 𝓓𝓲𝓟 𝓑𝓸𝔂𝓼 𝟕𝟏  ░░░▒▒▓█   M̳̿͟͞a̳̿͟͞d̳̿͟͞e̳̿͟͞ b̳̿͟͞y̳̿͟͞ D̳̿͟͞i̳̿͟͞P̳̿͟͞ B̳̿͟͞o̳̿͟͞y̳̿͟͞s̳̿͟͞ 𝟕𝟏 
  </div>
</div>

<section class="hero">
  <h1>Welcome to 𝟕𝟏ServiceHub</h1>
  <p>
    Simplify and streamline your vehicle servicing and machine shop management.<br>
    Book, track, and manage services for 2-wheelers, 3-wheelers, 4-wheelers, and workshop operations — all in one place.
  </p>
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

<section class="contact">
  <h2>Contact Us 📍</h2>
  <p>📞 +91 9**********</p>
  <p>📧 support@servicehub.com</p>
  <p>🗺️ map helps to find nearest</p>
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d23542.123456789!2d85.1234567!3d25.6543210!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39xxxxxx%3A0xyyyyyyyyyyyyy!2sShop%20No.%205%20Main%20Road%20Patna%2C%20Bihar%2C%20India!5e0!3m2!1sen!2sin!4v1700000000000" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
</section>

<footer>
  &copy; <?php echo date('Y'); ?> ServiceHub. All rights reserved.
</footer>

<!-- ---------------- AI CHATBOX ---------------- -->
<div id="chatIcon">💬</div>
<div id="chatBox">
  <div id="chatHeader">AI Assistant</div>
  <div id="messages"></div>
  <input type="text" id="chatInput" placeholder="Type your message...">
</div>

<script>
  const chatIcon = document.getElementById("chatIcon");
  const chatBox = document.getElementById("chatBox");
  const messages = document.getElementById("messages");
  const chatInput = document.getElementById("chatInput");

  chatIcon.onclick = () => {
    chatBox.style.display = chatBox.style.display === "flex" ? "none" : "flex";
  };

  function addMessage(text, type) {
    let div = document.createElement("div");
    div.className = "msg " + type;
    div.innerText = text;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  function getAIReply(userMsg) {
    userMsg = userMsg.toLowerCase();
    if(userMsg.includes("service") || userMsg.includes("booking")) return "You can book services from the Login → Dashboard → Book Service section 😊";
    if(userMsg.includes("login")) return "If you're facing Login issues, try resetting your password or contact support!";
    if(userMsg.includes("hello") || userMsg.includes("hi")) return "Hello! How can I assist you today? 😊";
    if(userMsg.includes("contact")) return "📞 Phone: +91 9876543210\n📧 Email: support@servicehub.com";
    if(userMsg.includes("help")) return "Sure! Tell me what you need help with.";
    return "I'm here to help you with ServiceHub information!";
  }

  chatInput.addEventListener("keypress", function(e){
    if(e.key === "Enter" && chatInput.value.trim() !== ""){
      let userMsg = chatInput.value.trim();
      addMessage(userMsg,"user");
      chatInput.value = "";
      setTimeout(() => {
        let botReply = getAIReply(userMsg);
        addMessage(botReply,"bot");
      },700);
    }
  });
</script>

</body>
</html>
