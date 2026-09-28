<?php
session_start();
include("../config.php");

$query = mysqli_query($conn,"
SELECT b.*,u.name,u.email,h.hotel_name
FROM booking b
JOIN users u ON u.id=b.user_id
JOIN hotel h ON h.id=b.hotel_id
WHERE b.status='pending'
");
?>

<h2>Pending Bookings</h2>

<table border="1" cellpadding="10">
<tr>
<th>User</th>
<th>Hotel</th>
<th>Checkin</th>
<th>Checkout</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<tr>
<td><?= $row['name'] ?></td>
<td><?= $row['hotel_name'] ?></td>
<td><?= $row['checkin'] ?></td>
<td><?= $row['checkout'] ?></td>

<td>
<a href="approve.php?id=<?= $row['id'] ?>">
<button style="background:green;color:white">Approve</button>
</a>

<a href="cancel.php?id=<?= $row['id'] ?>">
<button style="background:red;color:white">Cancel</button>
</a>
</td>

</tr>

<?php } ?>

</table>
