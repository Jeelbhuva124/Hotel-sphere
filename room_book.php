<?php
session_start();
include("config.php");

if(!isset($_SESSION['user'])){
 header("Location: login.php");
 exit();
}

if(!isset($_GET['id'])){
 header("Location: index.php");
 exit();
}

$room_id = intval($_GET['id']);
$room = $conn->query("SELECT * FROM rooms WHERE id=$room_id AND status='Available'")->fetch_assoc();

if(!$room){
 echo "Room Not Available";
 exit();
}

$message = "";

if(isset($_POST['book'])){

 $user_id = $_SESSION['user'];
 $checkin = $_POST['checkin'];
 $checkout = $_POST['checkout'];

 // Date validation
 if(strtotime($checkout) <= strtotime($checkin)){
 $message = "Checkout date must be after Checkin date!";
 }else{

 $days = (strtotime($checkout)-strtotime($checkin))/86400;
 $total = $days * $room['price'];

 // Insert booking
 $stmt = $conn->prepare("INSERT INTO bookings (user_id,room_id,checkin_date,checkout_date,total_amount,status) VALUES (?,?,?,?,?,'Pending')");
 $stmt->bind_param("iissd",$user_id,$room_id,$checkin,$checkout,$total);
 $stmt->execute();

 // Update room status
 $conn->query("UPDATE rooms SET status='Booked' WHERE id=$room_id");

 $message = "Room Booked Successfully!";
 }
}
?>

<!DOCTYPE html>
<html>
<head>
 <title>Book Room</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100">

<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">

 <h2 class="text-2xl font-bold mb-6">Book Room</h2>

 <?php if($message!=""): ?>
 <div class="mb-4 p-3 bg-green-100 text-success rounded">
  <?= $message ?>
 </div>
 <?php endif; ?>

 <div class="grid grid-cols-2 gap-6">

 <div>
  <?php if($room['image']): ?>
  <img src="../uploads/<?= $room['image'] ?>" class="rounded-lg w-full h-64 object-cover">
  <?php endif; ?>
 </div>

 <div>
  <h3 class="text-xl font-semibold"><?= $room['room_name'] ?></h3>
  <p class="text-gray-600 mt-2">Room No: <?= $room['room_no'] ?></p>
  <p class="text-gray-600">Location: <?= $room['location'] ?></p>
  <p class="text-lg font-bold mt-3 text-blue-600">₹ <?= $room['price'] ?> / per night</p>

  <form method="POST" class="mt-6 space-y-4">

  <div>
   <label>Checkin Date</label>
   <input type="date" name="checkin" required class="w-full border p-2 rounded">
  </div>

  <div>
   <label>Checkout Date</label>
   <input type="date" name="checkout" required class="w-full border p-2 rounded">
  </div>

  <button type="submit" name="book" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">
   Confirm Booking
  </button>

  </form>
 </div>

 </div>

</div>

</body>
</html>