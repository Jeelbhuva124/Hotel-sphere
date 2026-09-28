<?php
include '../config.php';

$q = $conn->query("
 SELECT 
 b.id, 
 b.user_name, 
 b.checkin_date, 
 b.checkout_date, 
 b.status,
 h.name AS hotel_name, 
 l.city_name
 FROM bookings b
 LEFT JOIN hotels h ON b.hotel_id = h.id
 LEFT JOIN locations l ON h.city_id = l.id
 ORDER BY b.id DESC
");

if(!$q){
 die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Bookings</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">
<h1 class="text-2xl font-bold mb-4">Bookings</h1>

<table class="w-full border-collapse border">
 <thead>
 <tr class="bg-gray-200">
  <th class="border p-2">ID</th>
  <th class="border p-2">User</th>
  <th class="border p-2">Hotel</th>
  <th class="border p-2">City</th>
  <th class="border p-2">Check-in</th>
  <th class="border p-2">Check-out</th>
  <th class="border p-2">Status</th>
 </tr>
 </thead>
 <tbody>
 <?php while($row = $q->fetch_assoc()): ?>
 <tr class="border-t">
  <td class="p-2"><?= $row['id'] ?></td>
  <td class="p-2"><?= htmlspecialchars($row['user_name']) ?></td>
  <td class="p-2"><?= htmlspecialchars($row['hotel_name']) ?></td>
  <td class="p-2"><?= htmlspecialchars($row['city_name']) ?></td>
  <td class="p-2"><?= $row['checkin_date'] ?></td>
  <td class="p-2"><?= $row['checkout_date'] ?></td>
  <td class="p-2">
  <?php
  // Status color
  $status = $row['status'];
  $color = "bg-gray-200 text-gray-800"; // Default
  if($status == "Confirmed") $color = "bg-green-100 text-success";
  else if($status == "Pending") $color = "bg-yellow-100 text-yellow-800";
  else if($status == "Cancelled") $color = "bg-red-100 text-error";
  ?>
  <span class="px-3 py-1 rounded-full <?= $color ?>"><?= $status ?></span>
  </td>
 </tr>
 <?php endwhile; ?>
 </tbody>
</table>
</div>

</body>
</html>