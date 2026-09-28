<?php
session_start();
include("../config.php");

// --- Admin check ---
if(!isset($_SESSION['role']) || strtolower(trim($_SESSION['role'])) != "admin"){
 header("Location: login.php");
 exit();
}

// --- Delete Feedback safely ---
if(isset($_GET['delete_id'])){
 $delete_id = intval($_GET['delete_id']);
 if($delete_id > 0){
 mysqli_query($conn, "DELETE FROM feedback WHERE id='$delete_id'");
 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
 }
}

// --- Search / Filter ---
$search = $_GET['search'] ?? '';
$search_sql = '';
if($search){
 $search_sql = " AND (f.name LIKE '%$search%' OR h.hotel_name LIKE '%$search%' OR f.message LIKE '%$search%') ";
}

// Fetch all feedback with hotel name
$sql = "
SELECT f.*, h.hotel_name
FROM feedback f
JOIN hotel h ON f.hotel_id = h.id
WHERE 1=1 $search_sql
ORDER BY f.created_at DESC
";
$feedbacks = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Feedback</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<div class="w-64 bg-white shadow flex-shrink-0">
 <?php include("sidebar.php"); ?>
</div>

<!-- Main Content -->
<div class="flex-1 p-6 overflow-x-auto">
 <h1 class="text-3xl font-bold mb-6">All Hotel Feedback</h1>

 <!-- Search Bar -->
 <form method="GET" class="mb-4 flex items-center gap-2">
 <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by hotel, name, message..."
  class="flex-1 px-4 py-2 border rounded focus:outline-none focus:ring-2 border-focus" />
 <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary">Search</button>
 <?php if($search): ?>
  <a href="<?= $_SERVER['PHP_SELF'] ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Reset</a>
 <?php endif; ?>
 </form>

 <div class="bg-white shadow rounded overflow-x-auto">
 <table class="w-full text-sm text-gray-700 border-collapse border border-gray-200">
  <thead class="bg-gray-200 uppercase text-xs">
  <tr>
   <th class="py-2 px-3 text-center border">Hotel</th>
   <th class="py-2 px-3 text-center border">Name</th>
   <th class="py-2 px-3 text-center border">Email</th>
   <th class="py-2 px-3 text-center border">Message</th>
   <th class="py-2 px-3 text-center border">Rating</th>
   <th class="py-2 px-3 text-center border">Date</th>
   <!-- <th class="py-2 px-3 text-center border">Action</th> -->
  </tr>
  </thead>
  <tbody>
  <?php if(mysqli_num_rows($feedbacks) > 0): ?>
  <?php while($row = mysqli_fetch_assoc($feedbacks)): ?>
  <tr class="border-b hover:bg-gray-50 transition">
   <td class="py-2 px-3 text-center border"><?= htmlspecialchars($row['hotel_name']) ?></td>
   <td class="py-2 px-3 text-center border"><?= htmlspecialchars($row['name']) ?></td>
   <td class="py-2 px-3 text-center border"><?= htmlspecialchars($row['email']) ?></td>
   <td class="py-2 px-3 max-w-xs overflow-hidden text-ellipsis whitespace-nowrap border" title="<?= htmlspecialchars($row['message']) ?>">
   <?= htmlspecialchars($row['message']) ?>
   </td>
   <td class="py-2 px-3 text-center border">
   <?php for($i=1;$i<=5;$i++): ?>
    <span class="<?= ($i <= $row['rating']) ? 'text-yellow-500' : 'text-gray-300' ?>">&#9733;</span>
   <?php endfor; ?>
   </td>
   <td class="py-2 px-3 text-center border"><?= date("d-m-Y", strtotime($row['created_at'])) ?></td>
   <td class="py-2 px-3 text-center border">
   <!-- <a href="<?= $_SERVER['PHP_SELF'] ?>?delete_id=<?= $row['id'] ?>" 
    onclick="return confirm('Are you sure you want to delete this feedback?');"
    class="text-error hover:text-error font-semibold">Delete</a> -->
   </td>
  </tr>
  <?php endwhile; ?>
  <?php else: ?>
  <tr>
   <td colspan="7" class="py-4 text-center text-gray-500 border">No feedback found.</td>
  </tr>
  <?php endif; ?>
  </tbody>
 </table>
 </div>
</div>

</body>
</html>
