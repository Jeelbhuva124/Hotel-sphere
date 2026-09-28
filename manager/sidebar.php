<?php
// sidebar.php

// Start session only if not already started
if (session_status() == PHP_SESSION_NONE) {
 session_start();
}

// Get manager name from session
$manager_name = $_SESSION['manager_name'] ?? "Manager";
?>

<!-- Sidebar -->
<div class="w-64 bg-black text-white min-h-screen p-6 hidden md:block">
 <h2 class="text-2xl font-bold mb-6">Hotel Manager</h2>
 <p class="mb-6 text-gray-300">Welcome, <?= htmlspecialchars($manager_name) ?></p>
 <nav class="flex flex-col gap-4">
 <a href="manager_dashboard.php" class="hover:bg-primary p-3 rounded flex items-center gap-2">
  <span data-feather="home"></span> Dashboard
 </a>
 <a href="manage_hotel.php" class="hover:bg-primary p-3 rounded flex items-center gap-2">
  <span data-feather="briefcase"></span> Manage Hotel
 </a>
 <a href="manage_rooms.php" class="hover:bg-primary p-3 rounded flex items-center gap-2">
  <span data-feather="layers"></span> Manage Rooms
 </a>
 <a href="manage_booking.php" class="hover:bg-primary p-3 rounded flex items-center gap-2">
  <span data-feather="file-text"></span> Manage Booking
 </a>
 
 <a href="manage_packages.php" class="hover:bg-primary p-3 rounded flex items-center gap-2">
  <span data-feather="gift"></span> Services & Packages
 </a>
  <a href="manage_users.php" class="hover:bg-primary p-3 rounded flex items-center gap-2">
  <span data-feather="users"></span> Update Users
 </a>
 <a href="view_contact.php" class="hover:bg-primary p-3 rounded flex items-center gap-2">
  <span data-feather="message-square"></span> Contact
 </a>
  <a href="manager_feedback.php" class="hover:bg-primary p-3 rounded flex items-center gap-2">
  <span data-feather="message-square"></span> Feedback
 </a>
 <a href="../index.php" class="hover:bg-red-600 p-3 rounded flex items-center gap-2">
  <span data-feather="log-out"></span> Logout
 </a>
 </nav>
</div>

<!-- Feather Icons Initialization -->
<script>
 if (typeof feather !== "undefined") {
 feather.replace();
 }
</script>