<?php
session_start();
include("config.php");

// Get booking_id from URL safely
$booking_id = intval($_GET['booking_id'] ?? 0);

// Redirect if booking_id is invalid
if ($booking_id <= 0) {
 header("Location: index.php");
 exit;
}

// Fetch booking info securely
$sql = "SELECT b.*, h.hotel_name, u.name AS user_name, u.email
 FROM booking b
 INNER JOIN hotel h ON b.hotel_id = h.id
 INNER JOIN users u ON b.user_id = u.id
 WHERE b.id = ? LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
 // Redirect if booking not found
 header("Location: my_booking.php");
 exit;
}

$row = mysqli_fetch_assoc($result);

// Map status for display
$status_display = strtolower($row['status'] ?? 'pending');
if($status_display === 'confirm' || $status_display === 'confirmed'){
 $status_display = 'Approved';
} else {
 $status_display = ucfirst($status_display);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice #<?= htmlspecialchars($row['booking_no']) ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
@media print {
 body * { visibility: hidden; }
 #invoice, #invoice * { visibility: visible; }
 #invoice { position: absolute; left: 0; top: 0; width: 100%; }
 .no-print { display: none; }
}
</style>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow" id="invoice">
 <div class="flex justify-between items-center mb-6">
 <div>
  <h1 class="text-2xl font-bold"><?= htmlspecialchars($row['hotel_name']) ?></h1>
 </div>
 <div class="text-right">
  <h2 class="text-xl font-semibold">Invoice</h2>
  <p class="text-gray-600">Booking #: <?= htmlspecialchars($row['booking_no']) ?></p>
 </div>
 </div>

 <hr class="my-4">

 <div class="mb-4 flex justify-between">
 <div>
  <h3 class="font-semibold text-gray-700">Guest Details</h3>
  <p>Name: <?= htmlspecialchars($row['user_name']) ?></p>
  <p>Email: <?= htmlspecialchars($row['email']) ?></p>
 </div>
 <div>
  <h3 class="font-semibold text-gray-700">Booking Details</h3>
  <p>Check-in: <?= htmlspecialchars($row['checkin_date']) ?></p>
  <p>Check-out: <?= htmlspecialchars($row['checkout_date']) ?></p>
  <p>Rooms: <?= htmlspecialchars($row['rooms']) ?></p>
  <p>Status: <strong><?= htmlspecialchars($status_display) ?></strong></p>
 </div>
 </div>

 <table class="w-full text-left border-collapse mb-6">
 <thead>
  <tr class="bg-gray-200">
  <th class="border px-3 py-2">Description</th>
  <th class="border px-3 py-2">Amount</th>
  </tr>
 </thead>
 <tbody>
  <tr>
  <td class="border px-3 py-2">Room Charges</td>
  <td class="border px-3 py-2">₹<?= number_format($row['price'], 2) ?></td>
  </tr>
  <tr class="font-semibold">
  <td class="border px-3 py-2 text-right">Total</td>
  <td class="border px-3 py-2">₹<?= number_format($row['price'], 2) ?></td>
  </tr>
 </tbody>
 </table>

 <p class="text-sm text-gray-600">Thank you for booking with <?= htmlspecialchars($row['hotel_name']) ?>.</p>
</div>

<div class="mt-6 flex justify-center gap-4 no-print">
 <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">Print</button>
 <a href="my_booking.php" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded shadow">Back to My Bookings</a>
</div>

</body>
</html>