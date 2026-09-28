<?php
session_start();
include("../config.php");

// Only admin access
if(!isset($_SESSION['role']) || strtolower($_SESSION['role'])!="admin"){
 header("Location: ../login.php");
 exit();
}

// Delete Manager
if(isset($_GET['delete'])){
 $id = intval($_GET['delete']);
 $conn->query("DELETE FROM managers WHERE id='$id'");
 header("Location: manage_managers.php");
 exit();
}

// Fetch managers with state and city names
$managers = $conn->query("
 SELECT m.*, s.state_name, c.city_name
 FROM managers m
 LEFT JOIN states s ON m.state_id = s.id
 LEFT JOIN cities c ON m.city_id = c.id
 ORDER BY m.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Managers</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100">

<!-- Flex container for sidebar + main content -->
<div class="flex min-h-screen">

 <!-- Sidebar -->
 <div class="w-64 bg-gray-800 text-white">
 <?php include("sidebar.php"); ?>
 </div>

 <!-- Main Content -->
 <div class="flex-1 p-6 overflow-x-auto">
 <h1 class="text-3xl font-bold mb-6">Manage Managers</h1>

 <!-- Managers Table -->
 <div class="bg-white shadow rounded overflow-x-auto">
  <table class="min-w-full text-left table-auto">
  <thead class="bg-gray-200">
   <tr>
   <th class="px-6 py-3">ID</th>
   <th class="px-6 py-3">Hotel Name</th>
   <th class="px-6 py-3">Email</th>
   <th class="px-6 py-3">Phone</th>
   <th class="px-6 py-3">State</th>
   <th class="px-6 py-3">City</th>
   <th class="px-6 py-3">Pincode</th>
   <th class="px-6 py-3">Created At</th>
   <th class="px-6 py-3">Actions</th>
   </tr>
  </thead>
  <tbody>
  <?php if($managers->num_rows>0): ?>
   <?php while($m=$managers->fetch_assoc()): ?>
   <tr class="border-b hover:bg-gray-50">
    <td class="px-6 py-3"><?= $m['id'] ?></td>
    <td class="px-6 py-3"><?= htmlspecialchars($m['hotel_name']) ?></td>
    <td class="px-6 py-3"><?= htmlspecialchars($m['email']) ?></td>
    <td class="px-6 py-3"><?= htmlspecialchars($m['phone']) ?></td>
    <td class="px-6 py-3"><?= htmlspecialchars($m['state_name'] ?? 'N/A') ?></td>
    <td class="px-6 py-3"><?= htmlspecialchars($m['city_name'] ?? 'N/A') ?></td>
    <td class="px-6 py-3"><?= htmlspecialchars($m['pincode']) ?></td>
    <td class="px-6 py-3"><?= $m['created_at'] ?></td>
    <td class="px-6 py-3 flex gap-2">
    <!-- <a href="edit_manager.php?id=<?= $m['id'] ?>" class="px-3 py-1 bg-blue-600 text-white rounded">Edit</a> -->
    <a href="?delete=<?= $m['id'] ?>" onclick="return confirm('Are you sure?')" class="px-3 py-1 bg-red-600 text-white rounded">Delete</a>
    </td>
   </tr>
   <?php endwhile; ?>
  <?php else: ?>
   <tr>
   <td colspan="9" class="px-6 py-3 text-center">No Managers Found</td>
   </tr>
  <?php endif; ?>
  </tbody>
  </table>
 </div>
 </div>
</div>

</body>
</html>
