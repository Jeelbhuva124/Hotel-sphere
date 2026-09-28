<?php include '../db.php'; ?>

<h2>Pending Bookings</h2>

<table border="1">
<tr>
 <th>User</th>
 <th>Hotel</th>
 <th>Checkin</th>
 <th>Checkout</th>
 <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM bookings WHERE status='pending'");

while($row = $result->fetch_assoc()){
?>
<tr>
 <td><?= $row['user_email'] ?></td>
 <td><?= $row['hotel_name'] ?></td>
 <td><?= $row['checkin'] ?></td>
 <td><?= $row['checkout'] ?></td>
 <td>
 <a href="approve.php?id=<?= $row['id'] ?>">Approve</a> |
 <a href="cancel.php?id=<?= $row['id'] ?>">Cancel</a>
 </td>
</tr>
<?php } ?>
</table>
