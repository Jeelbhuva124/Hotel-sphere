<?php
include("../config.php");

// ✅ Only Confirmed Booking
$bookings = $conn->query("
SELECT b.*, h.hotel_name
FROM booking b
LEFT JOIN hotel h ON b.hotel_id = h.id
WHERE b.status = 'Confirmed'
ORDER BY b.id DESC
");

if(!$bookings){
die("SQL Error: ".$conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Confirmed Bookings</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body class="w-64 min-h-screen">

<?php include("sidebar.php"); ?>

<h1 class="text-3xl font-bold mb-6">Confirmed Bookings</h1>

<div class="bg-white shadow rounded overflow-x-auto">

<table class="min-w-full text-sm">

<thead class="bg-gray-200">
<tr>
<th class="px-4 py-2">ID</th>
<th class="px-4 py-2">Hotel Name</th>
<th class="px-4 py-2">User Name</th>
<th class="px-4 py-2">Email</th>
<th class="px-4 py-2">Check-in</th>
<th class="px-4 py-2">Check-out</th>
<th class="px-4 py-2">Rooms</th>
<th class="px-4 py-2">Price</th>
<th class="px-4 py-2">Status</th>
</tr>
</thead>

<tbody>

<?php if($bookings->num_rows > 0){ ?>
<?php while($row=$bookings->fetch_assoc()){ ?>

<tr class="border-b">

<td class="px-4 py-2"><?= $row['id'] ?></td>
<td class="px-4 py-2"><?= $row['hotel_name'] ?></td>
<td class="px-4 py-2"><?= $row['user_name'] ?></td>
<td class="px-4 py-2"><?= $row['user_email'] ?></td>
<td class="px-4 py-2"><?= $row['checkin_date'] ?></td>
<td class="px-4 py-2"><?= $row['checkout_date'] ?></td>
<td class="px-4 py-2"><?= $row['rooms'] ?></td>
<td class="px-4 py-2">₹<?= $row['price'] * $row['rooms'] ?></td>

<td class="px-4 py-2">
<span class="bg-green-400 px-2 py-1 rounded">Confirmed</span>
</td>

</tr>

<?php } ?>
<?php } else { ?>
<tr><td colspan="9" class="text-center py-4">No Confirmed Booking Found</td></tr>
<?php } ?>

</tbody>

</table>

</div>

</body>
</html>