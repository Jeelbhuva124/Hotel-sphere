<?php
session_start();
include("config.php");

// Check if user logged in
if(!isset($_SESSION['user_id'])){
 header("Location: login.php");
 exit();
}

// Get booking ID
$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
if($booking_id <= 0){
 die("<h2 class='text-error text-center mt-10'>Invalid Booking ID!</h2>");
}

$user_id = $_SESSION['user_id'];

// Fetch booking info
$booking_q = mysqli_query($conn, "SELECT * FROM booking WHERE id='$booking_id' AND user_id='$user_id'");
if(!$booking_q || mysqli_num_rows($booking_q) == 0){
 die("<h2 class='text-error text-center mt-10'>Booking not found!</h2>");
}
$booking = mysqli_fetch_assoc($booking_q);

$message = "";

// Handle payment submission
if(isset($_POST['pay_now'])){
 $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

 // Insert payment record
 mysqli_query($conn, "
 INSERT INTO payment (booking_id, payment_method, amount, payment_status)
 VALUES ('$booking_id', '$payment_method', '".$booking['price']."', 'Completed')
 ");

 // Update booking status
 $update = mysqli_query($conn, "UPDATE booking SET status='Paid' WHERE id='$booking_id' AND user_id='$user_id'");

 if($update){
 // Redirect to invoice page for this booking
 header("Location: invoice.php?booking_id=$booking_id");
 exit();
 } else {
 $message = "<div class='bg-red-100 text-error p-3 rounded text-center mb-4'>Payment failed. Please try again.</div>";
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking Payment</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

<div class="max-w-md w-full bg-white shadow-xl rounded-lg p-6">

 <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Booking Payment</h2>

 <?php if($message) echo $message; ?>

 <!-- Booking Info -->
 <div class="bg-gray-100 rounded-lg p-4 mb-6 shadow-inner">
 <p class="mb-2"><span class="font-semibold">Booking No:</span> <?= htmlspecialchars($booking['booking_no']) ?></p>
 <p class="mb-2"><span class="font-semibold">Hotel:</span> <?= htmlspecialchars($booking['hotel_name']) ?></p>
 <p class="mb-2"><span class="font-semibold">Check-in:</span> <?= htmlspecialchars($booking['checkin_date']) ?></p>
 <p class="mb-2"><span class="font-semibold">Check-out:</span> <?= htmlspecialchars($booking['checkout_date']) ?></p>
 <p class="mb-2"><span class="font-semibold">Rooms:</span> <?= htmlspecialchars($booking['rooms']) ?></p>
 <p class="mb-2"><span class="font-semibold">Room Type:</span> <?= htmlspecialchars($booking['room_type'] ?? 'Standard') ?></p>
 <p class="mb-2"><span class="font-semibold">Total Amount:</span> <span class="text-blue-600 font-bold">₹<?= htmlspecialchars($booking['price']) ?></span></p>
 <p class="mb-0"><span class="font-semibold">Status:</span> 
  <?php
  $status = strtolower(trim($booking['status']));
  if($status == 'paid'){
  echo '<span class="text-success font-semibold">Paid</span>';
  } elseif($status == 'approved'){
  echo '<span class="text-blue-600 font-semibold">Approved</span>';
  } elseif($status == 'cancelled'){
  echo '<span class="text-error font-semibold">Cancelled</span>';
  } else {
  echo '<span class="text-yellow-600 font-semibold">Pending</span>';
  }
  ?>
 </p>
 </div>

 <!-- Payment Form -->
 <?php if($status != 'paid'): ?>
 <form method="post" class="space-y-4">
 <label class="block text-gray-700 font-semibold">Select Payment Method:</label>
 <select name="payment_method" required class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
  <option value="">-- Select --</option>
  <option value="Credit Card">Credit Card</option>
  <option value="Debit Card">Debit Card</option>
  <option value="UPI">UPI</option>
  <option value="Net Banking">Net Banking</option>
 </select>
 <button type="submit" name="pay_now" class="w-full bg-blue-600 hover:bg-blue-700 transition text-white font-semibold py-2 rounded shadow">Pay Now</button>
 </form>
 <?php endif; ?>

 <div class="mt-4 flex justify-between">
 <a href="invoice.php?booking_id=<?= $booking_id ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
  View / Print Invoice
 </a>
 <a href="my_booking.php" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded shadow">
  Back to My Bookings
 </a>
 </div>

</div>
</body>
</html>