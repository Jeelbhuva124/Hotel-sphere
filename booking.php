<?php
session_start();
include("config.php");

$hotel_id = $_GET['hotel_id'] ?? 0;
$rooms = $_GET['rooms'] ?? 0;
$total = $_GET['total'] ?? 0;

$guestData = [];
if (isset($_GET['data'])) {
 $guestData = json_decode($_GET['data'], true);
}

$hotel = mysqli_query($conn, "SELECT * FROM hotel WHERE id='$hotel_id'");
$h = mysqli_fetch_assoc($hotel);

$toast = "";
$error = "";

if (isset($_POST['book'])) {
 $name = trim($_POST['name']);
 $email = trim($_POST['email']);
 $checkin = $_POST['checkin'];
 $checkout = $_POST['checkout'];
 $price = $_POST['price'];
 $guest = $_POST['guestData'];

 $user_id = $_SESSION['user_id'] ?? 0;

 $dup_check = mysqli_query($conn, "
 SELECT * FROM booking
 WHERE hotel_id='$hotel_id'
  AND user_email='$email'
  AND user_name='$name'
  AND NOT (checkout_date <= '$checkin' OR checkin_date >= '$checkout')
 ");

 if (mysqli_num_rows($dup_check) > 0) {
 $error = "You already have a booking for these dates!";
 } else {

 $booking_no = "BK" . rand(10000, 99999);

 mysqli_query($conn, "
 INSERT INTO booking
 (booking_no, hotel_id, hotel_name, user_name, user_email,
 checkin_date, checkout_date, rooms, price, status, guest_data, user_id)
 VALUES
 ('$booking_no', '$hotel_id', '".$h['hotel_name']."', '$name', '$email',
 '$checkin', '$checkout', '$rooms', '$price', 'pending', '$guest', '$user_id')
 ");

 $toast = "Booking Request Sent! Manager will respond within 24 hours";
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Booking - <?= $h['hotel_name'] ?></title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>

<body class="bg-white min-h-screen flex justify-center items-center p-4">

<!-- Back button -->
<div class="absolute top-6 left-6">
<a href="index.php"
class="bg-white shadow px-4 py-2 rounded-lg hover:shadow-lg transition">
← Back To Home
</a>
</div>

<?php if ($toast) { ?>
<div class="fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded shadow-lg">
<?= $toast ?>
</div>
<?php } ?>

<?php if ($error) { ?>
<div class="fixed top-5 right-5 bg-red-600 text-white px-4 py-2 rounded shadow-lg">
<?= $error ?>
</div>
<?php } ?>

<div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl p-6">

<h2 class="text-2xl font-bold mb-4 text-gray-800">
<?= $h['hotel_name'] ?>
</h2>

<!-- Booking Summary -->
<div class="bg-blue-50 p-4 rounded-xl mb-5">
<div class="flex justify-between mb-2">
<span>Total Rooms</span>
<b><?= $rooms ?></b>
</div>

<div class="flex justify-between mb-2">
<span>Total Price</span>
<b class="text-blue-600">₹ <?= $total ?></b>
</div>

<hr class="my-2">

<?php foreach($guestData as $g){ ?>
<div class="flex justify-between text-sm mb-1">
<span><?= $g['room'] ?> × <?= $g['qty'] ?></span>
<span>Guests <?= $g['guests'] ?> • ₹<?= $g['price'] ?></span>
</div>
<?php } ?>

</div>

<form method="POST">

<input type="hidden" name="price" value="<?= $total ?>">
<input type="hidden" name="guestData" value='<?= json_encode($guestData) ?>'>

<div class="grid grid-cols-2 gap-3 mb-3">
<input type="text" name="name" placeholder="Your Name" required
class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">

<input type="email" name="email" placeholder="Email" required
class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400">
</div>

<div class="grid grid-cols-2 gap-3 mb-4">
<div>
<label class="text-sm font-medium">Check-in</label>
<input type="date" name="checkin" id="checkin" required
class="w-full border rounded-lg px-3 py-2">
</div>

<div>
<label class="text-sm font-medium">Check-out</label>
<input type="date" name="checkout" id="checkout" required
class="w-full border rounded-lg px-3 py-2">
</div>
</div>

<button name="book"
class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold
hover:bg-blue-700 transition shadow">
Confirm Booking
</button>

</form>

</div>

<script>
setTimeout(()=>{
document.querySelectorAll('.fixed').forEach(e=>e.remove())
},3000);

const checkin = document.getElementById('checkin');
const checkout = document.getElementById('checkout');

const today = new Date().toISOString().split('T')[0];
checkin.setAttribute('min', today);
checkout.setAttribute('min', today);

checkin.addEventListener('change', ()=>{
const minCheckout = new Date(checkin.value);
minCheckout.setDate(minCheckout.getDate()+1);
checkout.value='';
checkout.setAttribute('min', minCheckout.toISOString().split('T')[0]);
});
</script>

</body>
</html>
