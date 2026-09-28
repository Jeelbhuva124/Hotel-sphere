<?php
$current = basename($_SERVER['PHP_SELF']);
?>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


<!-- SIDEBAR -->
<div class="fixed h-screen w-64 bg-gray-900 text-gray-200 p-6 sidebar">

<h2 class="text-2xl font-bold mb-6 text-white">
<i class="fa fa-hotel"></i> Admin Panel
</h2>

<nav class="space-y-2">

<a href="dashboard.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='dashboard.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-chart-line mr-3"></i> Dashboard
</a>

<a href="manage_managers.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='manage_managers.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-user-tie mr-3"></i> Manage Managers
</a>

<a href="view_hotels.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='view_hotels.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-hotel mr-3"></i> View Hotels
</a>

<a href="view_rooms.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='view_rooms.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-bed mr-3"></i> View Rooms
</a>

<a href="manage_bookings.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='manage_bookings.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-calendar mr-3"></i> View Bookings
</a>

<a href="manage_users.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='manage_users.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-users mr-3"></i> View Users
</a>

<a href="view_contact.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='view_feedback.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-star mr-3"></i> View Contact
</a>

<a href="admin_feedback.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='view_feedback.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-star mr-3"></i> View Feedback
</a>

<!-- <a href="services_packages.php"
class="flex items-center px-4 py-3 rounded-lg <?php if($current=='services_packages.php') echo 'bg-blue-600 text-white'; else echo 'hover:bg-gray-700'; ?>">
<i class="fa fa-concierge-bell mr-3"></i> View Services & Packages
</a> -->

<a href="logout.php"
class="flex items-center px-4 py-3 rounded-lg hover:bg-red-600 mt-6">
<i class="fa fa-sign-out-alt mr-3"></i> Logout
</a>

</nav>

</div>

