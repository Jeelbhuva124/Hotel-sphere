<?php
session_start();
include("../config.php");

// Only admin access
if(!isset($_SESSION['role']) || strtolower($_SESSION['role']) != "admin"){
 header("Location: ../login.php");
 exit();
}

// Fetch bookings with hotel, city, state, and user info
$sql = "SELECT 
 b.*,
 h.hotel_name,
 c.city_name,
 s.state_name,
 u.name AS user_name,
 u.email AS user_email
 FROM booking b
 LEFT JOIN hotel h ON b.hotel_id = h.id
 LEFT JOIN cities c ON h.city_id = c.id
 LEFT JOIN states s ON h.state_id = s.id
 LEFT JOIN users u ON b.user_id = u.id
 ORDER BY b.id DESC";

$bookings = mysqli_query($conn, $sql);
if(!$bookings){
 die("SQL Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Bookings</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

 <!-- Sidebar -->
 <div class="w-64">
 <?php include("sidebar.php"); ?>
 </div>

 <!-- MAIN CONTENT -->
 <div class="flex-1 p-6">

 <!-- HEADER -->
 <div class="flex justify-between items-center mb-6">
  <h1 class="text-4xl font-bold text-gray-800">View Bookings</h1>
  <input type="text" id="search" placeholder="Search booking..."
  class="border px-4 py-2 text-lg rounded-lg shadow focus:ring-2 focus:ring-blue-400">
 </div>

 <!-- BOOKINGS TABLE -->
 <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
  <div class="overflow-x-auto">
  <table class="min-w-full text-base">
   <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-lg">
   <tr>
    <th class="px-6 py-4">ID</th>
    <th class="px-6 py-4">Hotel</th>
    <th class="px-6 py-4">Location</th>
    <th class="px-6 py-4">User</th>
    <th class="px-6 py-4">Dates</th>
    <th class="px-6 py-4">Rooms</th>
    <th class="px-6 py-4">Type</th>
    <th class="px-6 py-4">Price</th>
    <th class="px-6 py-4">Status</th>
   </tr>
   </thead>
   <tbody id="tableData">
   <?php while($row = mysqli_fetch_assoc($bookings)) { 
    $status = trim($row['status'] ?? 'Pending');
   ?>
   <tr class="border-b hover:bg-gray-50 transition text-lg">

    <td class="px-6 py-4 font-bold">#<?php echo $row['id']; ?></td>

    <td class="px-6 py-4">
    <div class="font-semibold text-lg"><?php echo $row['hotel_name'] ?? 'N/A'; ?></div>
    </td>

    <td class="px-6 py-4">
    <?php echo ($row['city_name'] ?? 'N/A') . ", " . ($row['state_name'] ?? 'N/A'); ?>
    </td>

    <td class="px-6 py-4">
    <div><?php echo $row['user_name'] ?? 'N/A'; ?></div>
    <div class="text-gray-400 text-sm"><?php echo $row['user_email'] ?? ''; ?></div>
    </td>

    <td class="px-6 py-4 text-sm">
    <div>IN: <?php echo $row['checkin_date']; ?></div>
    <div>OUT: <?php echo $row['checkout_date']; ?></div>
    </td>

    <td class="px-6 py-4 text-center font-bold"><?php echo $row['rooms']; ?></td>

    <td class="px-6 py-4"><?php echo !empty($row['room_type']) ? $row['room_type'] : 'Standard'; ?></td>

    <td class="px-6 py-4 font-bold text-blue-600 text-lg">₹<?php echo $row['price']; ?></td>

    <!-- STATUS COLUMN -->
    <td class="px-6 py-4">
    <?php
    switch(strtolower($status)){
     case "pending":
     echo "<span class='bg-yellow-200 text-yellow-800 px-4 py-2 rounded-full text-sm font-bold'>Pending</span>";
     break;
     case "approved":
     echo "<span class='bg-green-200 text-success px-4 py-2 rounded-full text-sm font-bold'>Approved</span>";
     break;
     case "paid":
     echo "<span class='bg-green-600 text-white px-4 py-2 rounded-full text-sm font-bold'>Paid</span>";
     break;
     case "cancelled":
     echo "<span class='bg-red-200 text-error px-4 py-2 rounded-full text-sm font-bold'>Cancelled</span>";
     break;
     default:
     echo "<span class='bg-gray-200 text-gray-800 px-4 py-2 rounded-full text-sm font-bold'>$status</span>";
    }
    ?>
    </td>

   </tr>
   <?php } ?>
   </tbody>
  </table>
  </div>
 </div>

 </div>
</div>

<!-- LIVE SEARCH -->
<script>
document.getElementById("search").addEventListener("keyup", function(){
 let value = this.value.toLowerCase();
 let rows = document.querySelectorAll("#tableData tr");
 rows.forEach(row => {
 row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
 });
});
</script>

</body>
</html>