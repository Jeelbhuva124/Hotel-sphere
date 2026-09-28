<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];

// Fetch all bookings of the user
$bookings = mysqli_query($conn,"
SELECT b.*, h.hotel_name
FROM bookings b
JOIN hotels h ON b.hotel_id = h.id
WHERE b.user_id='$user_id'
ORDER BY b.id DESC
");
?>

<table class="min-w-full border">
<tr class="bg-gray-200">
<th class="px-4 py-2">Hotel</th>
<th class="px-4 py-2">Check-in</th>
<th class="px-4 py-2">Check-out</th>
<th class="px-4 py-2">Status</th>
<th class="px-4 py-2">Total</th>
</tr>

<?php while($b=mysqli_fetch_assoc($bookings)){ ?>
<tr class="border-b">
<td class="px-4 py-2"><?= htmlspecialchars($b['hotel_name']) ?></td>
<td class="px-4 py-2"><?= $b['check_in'] ?></td>
<td class="px-4 py-2"><?= $b['check_out'] ?></td>
<td class="px-4 py-2 font-semibold"><?= $b['status'] ?></td>
<td class="px-4 py-2">₹<?= $b['total_amount'] ?></td>
</tr>
<?php } ?>
</table>