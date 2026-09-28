<?php
session_start();
include("config.php");

$user_id = $_SESSION['user_id'];

$bookings = mysqli_query($conn,"
SELECT b.*,h.hotel_name
FROM bookings b
JOIN hotel h ON b.hotel_id=h.id
WHERE b.user_id='$user_id'
ORDER BY b.id DESC
");
?>

<h2>My Bookings</h2>

<table border="1">

<tr>
<th>Hotel</th>
<th>Checkin</th>
<th>Checkout</th>
<th>Total</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($bookings)){ ?>

<tr>

<td><?= $row['hotel_name'] ?></td>
<td><?= $row['check_in'] ?></td>
<td><?= $row['check_out'] ?></td>
<td>₹<?= $row['total_amount'] ?></td>
<td><?= $row['status'] ?></td>

<td>

<?php if($row['status']=="pending"){ ?>

<a href="cancel_booking.php?id=<?= $row['id'] ?>">
Cancel
</a>

<?php } ?>

</td>

</tr>

<?php } ?>

</table>
