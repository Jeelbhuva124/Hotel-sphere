<?php
session_start();
include '../config.php';

if(!isset($_SESSION['admin'])){
 header("Location: login.php");
 exit();
}

// Fetch today's bookings
$today = date('Y-m-d');
$q = $conn->query("SELECT b.*, h.name AS hotel_name, l.name AS city_name 
   FROM bookings b 
   LEFT JOIN hotels h ON b.hotel_id = h.id 
   LEFT JOIN locations l ON h.city_id = l.id 
   WHERE b.checkin_date = '$today' 
   ORDER BY b.id DESC");

if(!$q){
 die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Today's Bookings</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
 <h1 class="text-2xl font-bold mb-6">Today's Bookings (<?php echo $today; ?>)</h1>

 <table class="w-full table-auto border-collapse">
 <thead>
  <tr class="bg-gray-200">
  <th class="p-3 border">#</th>
  <th class="p-3 border">User Name</th>
  <th class="p-3 border">Hotel</th>
  <th class="p-3 border">City</th>
  <th class="p-3 border">Check-in</th>
  <th class="p-3 border">Check-out</th>
  <th class="p-3 border">Rooms</th>
  <th class="p-3 border">Status</th>
  </tr>
 </thead>
 <tbody>
  <?php if($q->num_rows > 0): $i=1; ?>
  <?php while($row = $q->fetch_assoc()): ?>
  <tr class="border-t">
   <td class="p-2 border"><?php echo $i++; ?></td>
   <td class="p-2 border"><?php echo htmlspecialchars($row['user_name']); ?></td>
   <td class="p-2 border"><?php echo htmlspecialchars($row['hotel_name']); ?></td>
   <td class="p-2 border"><?php echo htmlspecialchars($row['city_name']); ?></td>
   <td class="p-2 border"><?php echo $row['checkin_date']; ?></td>
   <td class="p-2 border"><?php echo $row['checkout_date']; ?></td>
   <td class="p-2 border"><?php echo $row['rooms']; ?></td>
   <td class="p-2 border">
   <?php 
   $status = $row['status'];
   $color = $status == 'Confirmed' ? 'bg-green-500' : ($status=='Pending' ? 'bg-yellow-400' : 'bg-red-500');
   ?>
   <span class="<?php echo $color; ?> text-white px-2 py-1 rounded"><?php echo $status; ?></span>
   </td>
  </tr>
  <?php endwhile; ?>
  <?php else: ?>
  <tr>
   <td class="p-2 border text-center" colspan="8">No bookings found for today.</td>
  </tr>
  <?php endif; ?>
 </tbody>
 </table>
</div>

</body>
</html>
