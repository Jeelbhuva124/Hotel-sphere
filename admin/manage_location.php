<?php
session_start();
include '../config.php';

// ================= DELETE LOCATION =================
if (isset($_GET['delete_id'])) {
 $id = intval($_GET['delete_id']);
 $conn->query("DELETE FROM locations WHERE id=$id");
 header("Location: manage_location.php");
 exit();
}

// ================= SEARCH =================
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = $search ? "WHERE l.city_name LIKE '%$search%'" : "";

// ================= FETCH LOCATIONS WITH TOTAL HOTELS =================
$q = $conn->query("
 SELECT l.id, l.city_name, COUNT(h.id) AS total_hotels
 FROM locations l
 LEFT JOIN hotels h ON h.city_id = l.id
 $search_sql
 GROUP BY l.id, l.city_name
 ORDER BY l.id DESC
");
if(!$q) die("Query Failed: " . $conn->error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Locations</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100">

<?php include("sidebar.php"); ?>

<div class="ml-64 p-6">
 <div class="bg-white p-6 rounded shadow">

 <div class="flex justify-between items-center mb-4">
  <h2 class="text-2xl font-bold">Manage Locations</h2>
  <a href="add_location.php" class="bg-blue-600 text-white px-5 py-2 rounded">
  + Add Location
  </a>
 </div>

 <!-- Search -->
 <form method="GET" class="mb-4">
  <input type="text" name="search" placeholder="Search records..."
  value="<?= htmlspecialchars($search) ?>" 
  class="border p-2 w-64 rounded-l">
  <button type="submit" class="bg-blue-500 text-white px-4 rounded-r">Search</button>
 </form>

 <!-- Locations Table -->
 <table class="w-full rounded-lg overflow-hidden border">
  <thead class="bg-gray-200">
  <tr>
   <th class="p-3 text-left border">City Name</th>
   <th class="p-3 text-left border">Total Hotels</th>
   <th class="p-3 text-center border">Actions</th>
  </tr>
  </thead>
  <tbody class="divide-y">
  <?php if($q->num_rows > 0): ?>
   <?php while($row = $q->fetch_assoc()): ?>
   <tr class="bg-white">
    <td class="p-3"><?= htmlspecialchars($row['city_name']) ?></td>
    <td class="p-3"><?= $row['total_hotels'] ?></td>
    <td class="p-3 text-center flex justify-center gap-2">
    <a href="edit_location.php?id=<?= $row['id'] ?>" class="bg-green-500 text-white px-3 py-1 rounded">Edit</a>
    <a href="manage_location.php?delete_id=<?= $row['id'] ?>" class="bg-red-600 text-white px-3 py-1 rounded"
     onclick="return confirm('Are you sure you want to delete this location?')">Delete</a>
    </td>
   </tr>
   <?php endwhile; ?>
  <?php else: ?>
   <tr>
   <td class="p-3 text-center" colspan="3">No locations found</td>
   </tr>
  <?php endif; ?>
  </tbody>
 </table>
 </div>
</div>

</body>
</html>
