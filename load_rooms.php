<?php
include("config.php");

$id = $_GET['id'];

$res = mysqli_query($conn,"SELECT * FROM booking WHERE id='$id'");
$data = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>

<body class="bg-gray-100 p-10">

<div class="max-w-2xl mx-auto bg-white p-8 shadow-lg rounded-xl">

<h2 class="text-2xl font-bold text-center mb-4">
Hotel Booking Invoice
</h2>

<hr class="mb-4">

<p><b>Booking ID:</b> <?= $data['id'] ?></p>
<p><b>Name:</b> <?= $data['user_name'] ?></p>
<p><b>Hotel:</b> <?= $data['hotel_name'] ?></p>
<p><b>Rooms:</b> <?= $data['rooms'] ?></p>
<p><b>Checkin:</b> <?= $data['checkin_date'] ?></p>
<p><b>Checkout:</b> <?= $data['checkout_date'] ?></p>

<hr class="my-4">

<p class="text-xl font-bold">
Total Paid: ₹<?= $data['price'] ?>
</p>

<button onclick="window.print()"
class="mt-4 bg-primary text-white px-4 py-2 rounded">
Download Invoice
</button>

</div>

</body>
</html>
