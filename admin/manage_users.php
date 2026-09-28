<?php
session_start();
include("../config.php");

// Only admin can access
if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
 header("Location: ../login.php");
 exit();
}

// Fetch all users
$usersResult = $conn->query("SELECT * FROM users ORDER BY id DESC");

// Current page for sidebar active class
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>
<head>
 <title>View Users</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
 <style>
 .sidebar a{
  transition:0.3s;
 }
 .active{
  background:#0d6efd;
  color:white !important;
 }
 table th, table td{
  text-align:left;
  padding:10px;
 }
 </style>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100">

<!-- SIDEBAR -->
 <?php include("sidebar.php"); ?>
<!-- MAIN CONTENT -->
<div class="ml-64 p-8">
 <h1 class="text-3xl font-bold mb-6">View Users</h1>

 <div class="bg-white shadow rounded p-6">
 <table class="w-full border border-gray-200 rounded">
  <thead class="bg-blue-600">
  <tr>
   <th class="px-6 py-3 text-left text-sm font-semibold text-white">Id</th>
 <th class="px-6 py-3 text-left text-sm font-semibold text-white">Name</th>
 <th class="px-6 py-3 text-left text-sm font-semibold text-white">Email</th>
 <th class="px-6 py-3 text-left text-sm font-semibold text-white">Role</th>
 
   
  </tr>
  </thead>
  <tbody>
  <?php if($usersResult->num_rows > 0): ?>
   <?php while($user = $usersResult->fetch_assoc()): ?>
   <tr class="border-b">
    <td><?= $user['id'] ?></td>
    <td><?= $user['name'] ?></td>
    <td><?= $user['email'] ?></td>
    <td><?= ucfirst($user['role']) ?></td>
    
   </tr>
   <?php endwhile; ?>
  <?php else: ?>
   <tr>
   <td colspan="5" class="text-center py-4">No users found.</td>
   </tr>
  <?php endif; ?>
  </tbody>
 </table>
 </div>
</div>

</body>
</html>
