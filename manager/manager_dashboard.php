<?php
session_start();
include("../config.php");

// Redirect if manager not logged in
if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];
$manager_name = $_SESSION['manager_name'] ?? "Manager";

// --- Get manager's hotel_id ---
$hotel_result = mysqli_query($conn, "SELECT id, hotel_name FROM hotel WHERE manager_id='$manager_id' LIMIT 1");
$hotel_id = 0;
$hotel_name = "";
if($hotel_result && mysqli_num_rows($hotel_result) > 0){
 $hotel_row = mysqli_fetch_assoc($hotel_result);
 $hotel_id = $hotel_row['id'];
 $hotel_name = $hotel_row['hotel_name'];
}

// --- Dashboard Stats ---
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM room WHERE hotel_id='$hotel_id'"))['total'] ?? 0;

$available_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS available FROM room WHERE hotel_id='$hotel_id' AND available_rooms>0"))['available'] ?? 0;

$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking WHERE hotel_id='$hotel_id'"))['total'] ?? 0;


// ✅ ONLY PAID REVENUE (FIXED)
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "
 SELECT SUM(price) AS revenue 
 FROM booking 
 WHERE hotel_id='$hotel_id' 
 AND LOWER(status)='paid'
"))['revenue'] ?? 0;


// Booking status count
$confirmed_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking WHERE hotel_id='$hotel_id' AND LOWER(status)='confirmed'"))['total'] ?? 0;

$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking WHERE hotel_id='$hotel_id' AND LOWER(status)='pending'"))['total'] ?? 0;

$cancelled_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM booking WHERE hotel_id='$hotel_id' AND LOWER(status)='cancelled'"))['total'] ?? 0;


// --- Recent Feedback ---
$feedback_query = mysqli_query($conn, "
 SELECT *
 FROM feedback
 WHERE hotel_id='$hotel_id'
 ORDER BY id DESC
 LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manager Dashboard</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>

 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-64 bg-black text-white flex-shrink-0 p-6 flex flex-col">
 <h2 class="text-2xl font-bold mb-6">Hotel Manager</h2>
 <p class="mb-4 text-gray-300 text-sm">Welcome, <?= htmlspecialchars($manager_name) ?></p>

 <nav class="flex flex-col gap-2 flex-1">
 <a href="manager_dashboard.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800">
  <span data-feather="home"></span> Dashboard
 </a>

 <a href="manage_hotel.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800">
  <span data-feather="briefcase"></span> Manage Hotel
 </a>

 <a href="manage_rooms.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800">
  <span data-feather="layers"></span> Manage Rooms
 </a>

 <a href="manage_booking.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800">
  <span data-feather="file-text"></span> Manage Booking
 </a>

 <a href="manage_users.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800">
  <span data-feather="users"></span> Update Users
 </a>

 <a href="manage_packages.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800">
  <span data-feather="gift"></span> Services & Packages
 </a>

 <a href="manager_feedback.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800">
  <span data-feather="message-square"></span> Feedback
 </a>

 <a href="view_contact.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800">
  <span data-feather="message-square"></span> Contact
 </a>

 <a href="../index.php" class="flex items-center gap-2 p-3 rounded hover:bg-red-700">
  <span data-feather="log-out"></span> Logout
 </a>
 </nav>
</aside>


<!-- Main -->
<main class="flex-1 p-6 overflow-x-auto">
<div class="max-w-6xl mx-auto">

<h1 class="text-3xl font-bold text-gray-700 mb-6">
<?= htmlspecialchars($hotel_name) ?> Dashboard
</h1>


<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

<div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
<i data-feather="layers" class="text-primary mb-2"></i>
<h3 class="text-gray-500">Total Rooms</h3>
<p class="text-2xl font-bold"><?= $total_rooms ?></p>
</div>

<div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
<i data-feather="check-circle" class="text-success mb-2"></i>
<h3 class="text-gray-500">Available Rooms</h3>
<p class="text-2xl font-bold"><?= $available_rooms ?></p>
</div>

<div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
<i data-feather="file-text" class="text-yellow-600 mb-2"></i>
<h3 class="text-gray-500">Total Bookings</h3>
<p class="text-2xl font-bold"><?= $total_bookings ?></p>
</div>

<div class="bg-white p-6 rounded-xl shadow flex flex-col items-center">
<i data-feather="dollar-sign" class="text-error mb-2"></i>
<h3 class="text-gray-500">Revenue</h3>
<p class="text-2xl font-bold">₹<?= number_format($total_revenue) ?></p>
</div>

</div>


<!-- Feedback -->
<div class="bg-white rounded-xl shadow p-6 mb-8">

<h2 class="text-xl font-bold mb-4">Recent Feedback</h2>

<ul class="space-y-2">

<?php if($feedback_query && mysqli_num_rows($feedback_query)>0): ?>
<?php while($fb=mysqli_fetch_assoc($feedback_query)): ?>

<li class="border p-3 rounded">

<div class="flex justify-between items-center">
<span class="font-semibold">
<?= htmlspecialchars($fb['user_name'] ?? 'User') ?>
</span>

<span class="text-yellow-500">
<?php for($i=1;$i<=5;$i++): ?>
<?= $i <= $fb['rating'] ? "★" : "☆" ?>
<?php endfor; ?>
</span>
</div>

<p class="text-gray-700 mt-1">
<?= htmlspecialchars($fb['message']) ?>
</p>

</li>

<?php endwhile; ?>
<?php else: ?>

<li>No feedback yet for this hotel.</li>

<?php endif; ?>

</ul>
</div>

</main>

<script>
feather.replace();
</script>

</body>
</html>
