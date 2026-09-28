<?php
session_start();
include "../config.php";

// Admin check
if(!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) != "admin"){
 header("Location: login.php");
 exit();
}

// --- Delete message safely ---
if(isset($_GET['delete_id'])){
 $delete_id = intval($_GET['delete_id']);
 if($delete_id > 0){
 mysqli_query($conn, "DELETE FROM contact WHERE id='$delete_id'");
 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
 }
}

// --- Search/filter ---
$search = $_GET['search'] ?? '';
$search_sql = '';
if($search){
 $search_sql = " AND (c.name LIKE '%$search%' OR c.email LIKE '%$search%' OR h.hotel_name LIKE '%$search%' OR c.message LIKE '%$search%') ";
}

// Fetch messages
$sql = "
SELECT c.*, h.hotel_name
FROM contact c
JOIN hotel h ON c.hotel_id = h.id
WHERE 1=1 $search_sql
ORDER BY c.created_at DESC
";
$messages = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Contact Messages - Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

 <!-- Sidebar -->
 <div class="w-64 bg-white shadow flex-shrink-0">
 <?php include("sidebar.php"); ?>
 </div>

 <!-- Main content -->
 <main class="flex-1 p-6 overflow-auto">
 <h1 class="text-3xl font-bold mb-6 text-gray-700">All Contact Messages</h1>

 <!-- Search bar -->
 <form method="GET" class="mb-4 flex gap-2">
  <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by hotel, name, email, message..."
   class="flex-1 px-4 py-2 border rounded focus:outline-none focus:ring-2 border-focus"/>
  <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary">Search</button>
  <?php if($search): ?>
  <a href="<?= $_SERVER['PHP_SELF'] ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Reset</a>
  <?php endif; ?>
 </form>

 <div class="bg-white shadow rounded overflow-x-auto">
  <table class="min-w-full divide-y divide-gray-200 table-auto">
  <thead class="bg-gray-50">
   <tr>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hotel</th>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
   <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th> -->
   </tr>
  </thead>
  <tbody class="bg-white divide-y divide-gray-200">
   <?php if($messages && mysqli_num_rows($messages) > 0): ?>
   <?php while($m = mysqli_fetch_assoc($messages)): ?>
   <tr class="hover:bg-gray-50 transition">
    <td class="px-6 py-4 break-words"><?= htmlspecialchars($m['hotel_name']) ?></td>
    <td class="px-6 py-4 break-words"><?= htmlspecialchars($m['name']) ?></td>
    <td class="px-6 py-4 break-words"><?= htmlspecialchars($m['email']) ?></td>
    <td class="px-6 py-4 break-words max-w-xs" title="<?= htmlspecialchars($m['message']) ?>">
    <?= htmlspecialchars($m['message']) ?>
    </td>
    <td class="px-6 py-4 break-words">
    <?= date("d-m-Y H:i", strtotime($m['created_at'])) ?>
    </td>
    <!-- <td class="px-6 py-4 break-words">
    <button onclick="if(confirm('Are you sure you want to delete this message?')){window.location='<?= $_SERVER['PHP_SELF'] ?>?delete_id=<?= $m['id'] ?>';}" 
     class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
    </td> -->
   </tr>
   <?php endwhile; ?>
   <?php else: ?>
   <tr>
    <td colspan="6" class="text-center py-4 text-gray-500">No messages found.</td>
   </tr>
   <?php endif; ?>
  </tbody>
  </table>
 </div>
 </main>

</div>

</body>
</html>