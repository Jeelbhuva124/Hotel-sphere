<?php
session_start();
include("../config.php");

// Only admin
if(!isset($_SESSION['role']) || strtolower($_SESSION['role']) != "admin"){
 header("Location: ../login.php");
 exit();
}

// Handle search
$search = $_GET['search'] ?? '';
$search_sql = '';
if(!empty($search)){
 $search = $conn->real_escape_string($search);
 $search_sql = "WHERE h.hotel_name LIKE '%$search%' 
   OR c.city_name LIKE '%$search%' 
   OR m.email LIKE '%$search%'";
}

// Fetch hotels with city/state and manager email
$hotels = $conn->query("
 SELECT 
 h.*,
 c.city_name AS city_name,
 COALESCE(s1.state_name, s2.state_name) AS state_name,
 m.email AS manager_email
 FROM hotel h
 LEFT JOIN cities c ON h.city_id = c.id
 LEFT JOIN states s1 ON c.state_id = s1.id
 LEFT JOIN states s2 ON h.state_id = s2.id
 LEFT JOIN managers m ON h.manager_id = m.id
 $search_sql
 ORDER BY h.id DESC
");

if(!$hotels){
 die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin View Hotels</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

 <!-- Sidebar -->
 <div class="w-64 bg-gray-800 text-white">
 <?php include("sidebar.php"); ?>
 </div>

 <!-- Main Content -->
 <div class="flex-1 p-6">
 <h1 class="text-3xl font-bold mb-6">All Hotels</h1>

 <!-- Search Bar -->
 <form method="GET" class="mb-6 flex">
  <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name, city, manager email..." class="flex-1 p-2 border rounded-l shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
  <button type="submit" class="bg-blue-500 text-white px-4 rounded-r hover:bg-blue-600">Search</button>
 </form>

 <?php if($hotels->num_rows > 0): ?>
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php while($h = $hotels->fetch_assoc()): ?>
  <div class="bg-white shadow rounded overflow-hidden">
  <?php if(!empty($h['image']) && file_exists("../uploads/".$h['image'])): ?>
   <img src="../uploads/<?= htmlspecialchars($h['image']) ?>" class="w-full h-48 object-cover">
  <?php else: ?>
   <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
   <span class="text-gray-400">No Image</span>
   </div>
  <?php endif; ?>
  
  <div class="p-4">
   <h2 class="text-xl font-semibold mb-2"><?= htmlspecialchars($h['hotel_name']) ?></h2>
   <p class="text-gray-600 mb-1"><strong>Manager:</strong> <?= htmlspecialchars($h['manager_email'] ?? '-') ?></p>
   <p class="text-gray-600 mb-1"><strong>State:</strong> <?= htmlspecialchars($h['state_name'] ?? '-') ?></p>
   <p class="text-gray-600 mb-1"><strong>City:</strong> <?= htmlspecialchars($h['city_name'] ?? '-') ?></p>
   <p class="text-gray-600 mb-1"><strong>Address:</strong> <?= htmlspecialchars($h['hotel_address']) ?></p>
   <p class="text-gray-600 mb-1"><strong>Pincode:</strong> <?= htmlspecialchars($h['pincode']) ?></p>
   <p class="text-gray-600 mb-1"><strong>Rating:</strong> <?= htmlspecialchars($h['rating'] ?? '-') ?></p>
   <p class="text-gray-600 mb-1"><strong>Status:</strong> <?= htmlspecialchars($h['status']) ?></p>
   <p class="text-gray-400 text-sm mt-2"><strong>Created:</strong> <?= $h['created_at'] ?></p>
  </div>
  </div>
  <?php endwhile; ?>
 </div>
 <?php else: ?>
  <p class="text-center text-gray-500 mt-6">No Hotels Found</p>
 <?php endif; ?>
 </div>
</div>

</body>
</html>
