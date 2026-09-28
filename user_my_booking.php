<?php
include("config.php");
session_start();

$email = $_SESSION['user_email'] ?? '';

$result = mysqli_query($conn,"
SELECT * FROM booking 
WHERE user_email='$email'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>My Booking</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>

<body class="bg-gray-100 p-6">

<h2 class="text-2xl font-bold mb-4">My Bookings</h2>

<table class="w-full bg-white shadow rounded text-center">

<tr class="bg-gray-200">
<th>Hotel</th>
<th>Checkin</th>
<th>Checkout</th>
<th>Rooms</th>
<th>Price</th>
<th>Status</th>
<th>Message</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr class="border">

<td><?= $row['hotel_name'] ?></td>
<td><?= $row['checkin_date'] ?></td>
<td><?= $row['checkout_date'] ?></td>
<td><?= $row['rooms'] ?></td>
<td>₹<?= $row['price'] ?></td>

<td>

<?php if($row['status']=="pending"){ ?>
<span class="text-yellow-600">Pending</span>
<?php } ?>

<?php if($row['status']=="approved"){ ?>
<span class="text-blue-600">Approved</span>
<?php } ?>

<?php if($row['status']=="confirmed"){ ?>
<span class="text-success">Confirmed</span>
<?php } ?>

<?php if($row['status']=="cancelled"){ ?>
<span class="text-error">Cancelled</span>
<?php } ?>

</td>

<td>
<?= $row['manager_message'] ?>
</td>

<td>

<?php if($row['status']=="approved"){ ?>

<a href="payment.php?id=<?= $row['id'] ?>"
class="bg-green-600 text-white px-3 py-1 rounded">
Pay Now
</a>

<?php } ?>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>
