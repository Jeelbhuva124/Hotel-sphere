<?php
session_start();
include("../config.php");

// Admin login check
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
 die("Access Denied");
}

// Fetch all rooms with hotel info
$rooms = mysqli_query($conn, "
 SELECT r.*, h.hotel_name 
 FROM room r
 LEFT JOIN hotel h ON r.hotel_id = h.id
 ORDER BY r.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Rooms - Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<style>
body { font-family: 'Segoe UI', sans-serif; }
.card-hover:hover {
 transform: translateY(-5px);
 transition: 0.3s;
 box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<aside class="w-64 bg-gray-900 text-white flex-shrink-0 flex flex-col fixed top-0 left-0 h-full shadow-lg z-10">
 <div class="p-6">
 <h2 class="text-2xl font-bold mb-6">Admin Panel</h2>
 <nav class="flex flex-col gap-2">
  <a href="dashboard.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="home"></span> Dashboard</a>
  <a href="manage_managers.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="users"></span> Manage Managers</a>
  <a href="view_hotels.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="briefcase"></span> View Hotels</a>
  <a href="view_rooms.php" class="flex items-center gap-2 p-3 rounded bg-blue-600"><span data-feather="layers"></span> View Rooms</a>
  <a href="view_bookings.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="file-text"></span> View Bookings</a>
  <a href="view_users.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="users"></span> View Users</a>
  <a href="view_feedback.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="message-square"></span> View Feedback</a>
  <a href="../index.php" class="flex items-center gap-2 p-3 rounded hover:bg-red-700"><span data-feather="log-out"></span> Logout</a>
 </nav>
 </div>
</aside>

<!-- Main Content -->
<main class="flex-1 p-6 ml-64">
 <h1 class="text-3xl font-bold mb-6 text-primary text-center">All Rooms</h1>

 <!-- Search Bar -->
 <div class="max-w-3xl mx-auto mb-6">
 <input type="text" id="searchInput" placeholder="Search by hotel, room type, bed type, or view..."
  class="w-full border rounded-lg p-3 shadow focus:outline-none focus:ring-2 border-focus"
  onkeyup="filterRooms()">
 </div>

 <!-- Rooms Grid -->
 <?php if($rooms && mysqli_num_rows($rooms) > 0): ?>
 <div id="roomsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
 <?php while($r=mysqli_fetch_assoc($rooms)): ?>
  <div class="room-card bg-white rounded-xl shadow p-4 card-hover flex flex-col">
  <div class="h-40 w-full mb-4 overflow-hidden rounded-lg">
   <?php if($r['image']): ?>
   <img src="../uploads/<?= htmlspecialchars($r['image']) ?>" class="w-full h-full object-cover">
   <?php else: ?>
   <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
   <?php endif; ?>
  </div>

  <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($r['room_type']) ?></h2>
  <p class="text-gray-600 mb-1"><span class="font-semibold">Hotel:</span> <?= htmlspecialchars($r['hotel_name']) ?></p>
  <p class="text-gray-600 mb-1"><span class="font-semibold">Bed Type:</span> <?= htmlspecialchars($r['bed_type']) ?></p>
  <p class="text-gray-600 mb-1"><span class="font-semibold">Size:</span> <?= htmlspecialchars($r['room_size']) ?></p>
  <p class="text-gray-600 mb-1"><span class="font-semibold">View:</span> <?= htmlspecialchars($r['view_type']) ?></p>
  <p class="text-gray-600 mb-2"><span class="font-semibold">Available:</span> <?= htmlspecialchars($r['available_rooms']) ?></p>

  <div class="flex flex-wrap gap-1 mb-2">
   <?php 
   $facilities = explode(",", $r['facilities'] ?? '');
   foreach($facilities as $f){
   echo "<span class='inline-block bg-blue-100 text-blue-700 px-2 py-1 text-xs rounded'>".htmlspecialchars($f)."</span>";
   }
   ?>
  </div>
  </div>
 <?php endwhile; ?>
 </div>
 <?php else: ?>
 <p class="text-center text-gray-500 text-lg mt-10">No rooms found.</p>
 <?php endif; ?>
</main>

<script>
feather.replace();

// JavaScript filter for search
function filterRooms() {
 const input = document.getElementById('searchInput').value.toLowerCase();
 const cards = document.querySelectorAll('.room-card');

 cards.forEach(card => {
 const hotel = card.querySelector('p span.font-semibold:nth-child(1)').nextSibling.textContent.toLowerCase();
 const roomType = card.querySelector('h2').textContent.toLowerCase();
 const bedType = card.querySelector('p span.font-semibold:nth-child(2)') ? card.querySelector('p span.font-semibold:nth-child(2)').nextSibling.textContent.toLowerCase() : '';
 const viewType = card.querySelector('p span.font-semibold:nth-child(4)') ? card.querySelector('p span.font-semibold:nth-child(4)').nextSibling.textContent.toLowerCase() : '';

 if(hotel.includes(input) || roomType.includes(input) || bedType.includes(input) || viewType.includes(input)){
  card.style.display = '';
 } else {
  card.style.display = 'none';
 }
 });
}
</script>

</body>
</html>