<?php
session_start();

// ----- FIX UNDEFINED SESSION ERRORS -----
$currentUser = $_SESSION['username'] ?? "Guest";
$userRole = $_SESSION['role'] ?? "user";
$fullname = $_SESSION['fullname'] ?? "Customer";

// If user not logged in → redirect
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// DB Connection
$conn = new mysqli('localhost', 'root', '', 'servicehub');
if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}

// ---- CLEAR BOOKING HISTORY (RESET BUTTON FIX) ----
if (isset($_GET['reset']) && $userRole === "admin") {
    $conn->query("DELETE FROM service_bookings");
    header("Location: view_services.php");
    exit();
}

// Handle Booking
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = $_POST['customerName'];
    $vehicleNumber = $_POST['vehicleNumber'];
    $vehicleType = $_POST['vehicleType'];
    $serviceCategory = $_POST['serviceCategory'];
    $serviceDetails = $_POST['serviceDetails'];
    $serviceDuration = $_POST['serviceDuration'];
    $paymentMode = $_POST['paymentMode'];
    $totalBill = intval($_POST['totalBill']);

    $stmt = $conn->prepare("INSERT INTO service_bookings 
        (customer_name, vehicle_number, vehicle_type, 
        service_category, service_details, service_duration, 
        payment_mode, total_bill)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssssi", $customerName, $vehicleNumber, $vehicleType,
        $serviceCategory, $serviceDetails, $serviceDuration, 
        $paymentMode, $totalBill);

    $stmt->execute();
    $stmt->close();

    header("Location: view_services.php?success=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>ServiceHub — Book Service or Buy Products</title>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* GLOBAL */
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: url('bgggg.png') no-repeat center center fixed;
    background-size: cover;
    backdrop-filter: blur(5px);
}

header {
    background: rgba(0,0,0,0.85);
    color: #fff;
    padding: 20px;
    font-size: 30px;
    text-align: center;
    letter-spacing: 1px;
    font-weight: bold;
}

.container {
    width: 92%;
    max-width: 1300px;
    margin: 35px auto;
    background: rgba(255,255,255,0.10); /* TRANSPARENT */
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.35);
}

/* BUTTONS */
.back-btn, .reset-btn {
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
}

.back-btn {
    background: #2c2c2c;
    color: white;
}

.back-btn:hover {
    background: #000;
}

.reset-btn {
    background: #ff2b2b;
    color: white;
    float: right;
}

.reset-btn:hover {
    background: #c40000;
}

/* SERVICE CARDS */
.services {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: 22px;
    margin-top: 20px;
}

.card {
    background: rgba(255,255,255,0.20); /* TRANSPARENT */
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    transition: 0.3s;
    box-shadow: 0 3px 10px rgba(0,0,0,0.25);
}

.card:hover {
    transform: scale(1.07);
    background: rgba(255,255,255,0.35);
    cursor: pointer;
}

.card img {
    width: 80px;
    height: 80px;
}

/* FORM */
form {
    background: rgba(255,255,255,0.15); /* TRANSPARENT */
    padding: 25px;
    border-radius: 12px;
    margin-top: 25px;
    display: none;
    box-shadow: 0 3px 12px rgba(0,0,0,0.3);
}

label {
    font-weight: bold;
    display: block;
    margin-top: 12px;
}

input, select {
    width: 100%;
    padding: 11px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-top: 6px;
}

button {
    padding: 14px;
    width: 100%;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 18px;
    margin-top: 20px;
    cursor: pointer;
}

button:hover {
    background: #005ec4;
}

/* BILL BOX */
.bill-section {
    font-size: 22px;
    margin-top: 20px;
    background: rgba(255,255,255,0.20);
    padding: 14px;
    border-left: 6px solid #007bff;
    border-radius: 8px;
}

/* HISTORY TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 30px;
    background: rgba(255,255,255,0.15); /* TRANSPARENT */
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(0,0,0,0.25);
}

th {
    background: #007bff;
    color: white;
    padding: 14px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

footer {
    margin-top: 40px;
    padding: 18px;
    text-align: center;
    color: white;
    background: rgba(0,0,0,0.7);
}
</style>
</head>

<body>

<?php
// ---- SUCCESS MESSAGE POPUP ----
if (isset($_GET['success'])) {
    echo "<script>
        Swal.fire({
            title: 'Booking Successful!',
            text: 'Your service has been booked successfully.',
            icon: 'success',
            confirmButtonColor: '#007bff'
        });
    </script>";
}
?>

<header>ServiceHub — Vehicle Service & Product Booking</header>

<div class="container">

<a class="back-btn" onclick="history.back()">← Back</a>
<a class="reset-btn" href="view_services.php?reset=1" onclick="return confirm('Clear all history?')">🗑 Reset</a>

<h2 style="margin-top:60px;">Select Your Category</h2>

<div class="services" id="serviceCards"></div>

<!-- BOOKING FORM -->
<form id="serviceForm" method="POST" action="view_services.php">
    <h3 id="formTitle">Book a Service</h3>

    <label>Customer Name</label>
    <input type="text" name="customerName" value="<?php echo htmlspecialchars($fullname); ?>">

    <label>Vehicle Number</label>
    <input type="text" name="vehicleNumber">

    <div id="vehicleTypeContainer" style="display:none;">
        <label>Vehicle Type</label>
        <select id="vehicleSubType" name="vehicleType"></select>
    </div>

    <div id="serviceOptions"></div>
    <input type="hidden" id="serviceDetails" name="serviceDetails">

    <div id="durationContainer" style="display:none;">
        <label>Expected Duration</label>
        <select id="duration" name="serviceDuration">
            <option value="">-- Select Duration --</option>
            <option value="1">1 Hour</option>
            <option value="2">2 Hours</option>
            <option value="3">3 Hours</option>
            <option value="5">5 Hours</option>
            <option value="7">7 Hours</option>
        </select>
    </div>

    <div id="paymentContainer" style="display:none;">
        <label>Payment Mode</label>
        <select id="paymentMode" name="paymentMode">
            <option value="">-- Select Payment --</option>
            <option value="Cash">Cash</option>
            <option value="UPI">UPI</option>
            <option value="Card">Card</option>
        </select>
    </div>

    <input type="hidden" id="serviceCategory" name="serviceCategory">
    <input type="hidden" id="totalBillInput" name="totalBill">

    <div class="bill-section" id="billingDisplay">Total Bill: ₹0</div>

    <button type="submit">Confirm Booking</button>
</form>

<h3 style="margin-top:40px;">Booking History</h3>

<table>
<thead>
<tr>
<th>Customer</th>
<th>Category</th>
<th>Details</th>
<th>Duration</th>
<th>Payment</th>
<th>Total</th>
</tr>
</thead>
<tbody>
<?php
$sql = $userRole === "admin"
    ? "SELECT * FROM service_bookings ORDER BY booking_time DESC"
    : "SELECT * FROM service_bookings WHERE customer_name='".$conn->real_escape_string($fullname)."' ORDER BY booking_time DESC";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['customer_name']}</td>
        <td>{$row['service_category']}</td>
        <td>{$row['service_details']}</td>
        <td>{$row['service_duration']}</td>
        <td>{$row['payment_mode']}</td>
        <td>₹{$row['total_bill']}</td>
    </tr>";
}
?>
</tbody>
</table>

</div>

<footer>© 2025 ServiceHub | All Rights Reserved</footer>

<script>
/* JS LOGIC — SAME AS YOUR FILE */
const services = [
  { name: "2-Wheeler", img: "bike.png" },
  { name: "3-Wheeler", img: "rickshaw.png" },
  { name: "4-Wheeler", img: "car.png" },
  { name: "Products", img: "product.png" }
];

const serviceTypes = {
  "2-Wheeler": {
    subtypes: ["Bike", "Scooter", "Superbike", "EV"],
    options: {
      normal: { "General Service": 500, "Oil Change": 300, "Brake Check": 200, "Wheel Alignment": 250 },
      ev:     { "Battery Check": 400, "Motor Diagnostics": 600, "Charging System": 300 }
    }
  },
  "3-Wheeler": {
    subtypes: ["Auto", "EV Auto"],
    options: {
      normal: { "Full Checkup": 700,  "Brake Service": 350, "AC Service": 300 },
      ev:     { "Battery Health Check": 450, "Motor Repair": 650, "Electronics Diagnostics": 400 }
    }
  },
  "4-Wheeler": {
    subtypes: ["Car (Normal)", "EV Car"],
    options: {
      normal: { "General Service": 1200, "Engine Check": 1500, "Brake Check": 400 },
      ev:     { "EV Battery Inspection": 1000, "Motor Calibration": 800, "Charging System Check": 600 }
    }
  },
  "Products": {
    options: { "Engine Oil": 400, "Tyre": 2500, "Headlight": 600, "Battery": 4500 }
  }
};

const cardsContainer = document.getElementById("serviceCards");
const form = document.getElementById("serviceForm");
const serviceOptions = document.getElementById("serviceOptions");
const billingDisplay = document.getElementById("billingDisplay");
const vehicleTypeContainer = document.getElementById("vehicleTypeContainer");
const vehicleSubType = document.getElementById("vehicleSubType");

let selectedCategory = "";
let totalBill = 0;

services.forEach(svc => {
  const card = document.createElement("div");
  card.className = "card";
  card.innerHTML = `<img src="${svc.img}"><h4>${svc.name}</h4>`;
  card.onclick = () => showForm(svc.name);
  cardsContainer.appendChild(card);
});

function showForm(category) {
  selectedCategory = category;
  form.style.display = "block";
  document.querySelector(".services").style.display = "none";
  serviceOptions.innerHTML = "";
  totalBill = 0;
  billingDisplay.textContent = "Total Bill: ₹0";
  document.getElementById("formTitle").textContent = `Book ${category}`;
  document.getElementById("serviceCategory").value = category;

  if (category === "Products") {
    vehicleTypeContainer.style.display = "none";
    document.getElementById("durationContainer").style.display = "none";
    document.getElementById("paymentContainer").style.display = "block";
    displayProductOptions();
  } else {
    vehicleTypeContainer.style.display = "block";
    document.getElementById("durationContainer").style.display = "block";
    document.getElementById("paymentContainer").style.display = "block";
    populateVehicleTypes(category);
  }
}

function populateVehicleTypes(category) {
  vehicleSubType.innerHTML = `<option value="">-- Select Type --</option>`;
  serviceTypes[category].subtypes.forEach(t => {
    const opt = document.createElement("option");
    opt.value = t;
    opt.textContent = t;
    vehicleSubType.appendChild(opt);
  });
}

vehicleSubType.addEventListener("change", function() {
  const val = this.value.toLowerCase();
  const options = val.includes("ev")
    ? serviceTypes[selectedCategory].options.ev
    : serviceTypes[selectedCategory].options.normal;
  displayServiceOptions(options);
});

function displayServiceOptions(options) {
  serviceOptions.innerHTML = "";
  for (let [name, price] of Object.entries(options)) {
    const lbl = document.createElement("label");
    lbl.innerHTML = `<input type="checkbox" value="${price}" onchange="updateBill(this)"> ${name} — ₹${price}`;
    serviceOptions.appendChild(lbl);
  }
}

function displayProductOptions() {
  serviceOptions.innerHTML = "";
  for (let [name, price] of Object.entries(serviceTypes["Products"].options)) {
    const lbl = document.createElement("label");
    lbl.innerHTML =
      `<input type="checkbox" value="${price}" onchange="toggleProductQty(this,'${name}',${price})"> 
       ${name} — ₹${price} 
       <input type="number" id="qty-${name}" value="1" min="1" style="display:none; width:70px; margin-left:10px;">`;
    serviceOptions.appendChild(lbl);
  }
}

function toggleProductQty(cb, name, price) {
  const qtyInput = document.getElementById(`qty-${name}`);
  qtyInput.style.display = cb.checked ? "inline-block" : "none";
  recalcProductBill();
}

serviceOptions.addEventListener("input", recalcProductBill);

function recalcProductBill() {
  if (selectedCategory !== "Products") return;

  totalBill = 0;
  Object.entries(serviceTypes["Products"].options).forEach(([name, price]) => {
    const cb = document.querySelector(`#serviceOptions input[value="${price}"]`);
    const qty = parseInt(document.getElementById(`qty-${name}`).value || 1);
    if (cb && cb.checked) totalBill += price * qty;
  });

  billingDisplay.textContent = `Total Bill: ₹${totalBill}`;
  document.getElementById("totalBillInput").value = totalBill;
}

function updateBill(cb) {
  const price = parseInt(cb.value);
  totalBill += cb.checked ? price : -price;
  billingDisplay.textContent = `Total Bill: ₹${totalBill}`;
  document.getElementById("totalBillInput").value = totalBill;
}

form.addEventListener("submit", function(e) {
  let details = "";
  document.querySelectorAll("#serviceOptions input[type=checkbox]:checked").forEach(cb => {
    let name = cb.parentElement.textContent.split("—")[0].trim();
    if (selectedCategory === "Products") {
      let qty = document.getElementById(`qty-${name}`).value;
      details += `${name} (x${qty}), `;
    } else {
      details += name + ", ";
    }
  });

  document.getElementById("serviceDetails").value = details.slice(0, -2);
  document.getElementById("totalBillInput").value = totalBill;
});
</script>

</body>
</html>
