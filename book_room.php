<?php
session_start();
include("config.php");

// ======= SELECT FIRST HOTEL WITH AVAILABLE ROOMS =======
$hotel_res = mysqli_query($conn, "
 SELECT h.id AS hotel_id, h.hotel_name 
 FROM hotel h
 JOIN rooms r ON r.hotel_id = h.id
 WHERE r.available_rooms > 0
 GROUP BY h.id
 ORDER BY h.id ASC
 LIMIT 1
");

if(mysqli_num_rows($hotel_res) > 0){
 $hotel = mysqli_fetch_assoc($hotel_res);
 $selected_hotel_id = $hotel['hotel_id'];
 $hotel_name = $hotel['hotel_name'];

 $rooms = mysqli_query($conn, "SELECT * FROM rooms WHERE hotel_id='$selected_hotel_id' AND available_rooms>0");
} else {
 $selected_hotel_id = null;
 $hotel_name = "No Hotel Found";
 $rooms = [];
}

// ======= BOOKING SUBMISSION =======
if(isset($_POST['book']) && $selected_hotel_id){
 $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
 $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
 $checkin = $_POST['checkin'];
 $checkout = $_POST['checkout'];

 $total_amount = 0;
 $rooms_count = 0;
 $room_type = '';

 foreach($_POST['qty'] as $i => $qty){
 $q = intval($qty);
 if($q > 0){
  $room_id = intval($_POST['room_id'][$i]);
  $room = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM rooms WHERE id='$room_id'"));

  $total_amount += $room['price'] * $q;
  $room_type = $room['room_type']; // store first room type
  $rooms_count += $q;

  // Reduce available rooms
  mysqli_query($conn,"UPDATE rooms SET available_rooms = available_rooms - '$q' WHERE id='$room_id'");
 }
 }

 if($rooms_count > 0){
 $insert = mysqli_query($conn,"INSERT INTO booking
  (hotel_id, user_name, user_email, checkin_date, checkout_date, rooms, room_type, price, status, created_at)
  VALUES
  ('$selected_hotel_id', '$user_name', '$user_email', '$checkin', '$checkout', '$rooms_count', '$room_type', '$total_amount', 'Pending', NOW())
 ");

 if($insert){
  echo "<script>alert('Booking Successful'); window.location='".$_SERVER['PHP_SELF']."';</script>";
  exit;
 } else {
  die("Booking failed: " . mysqli_error($conn));
 }
 } else {
 echo "<script>alert('Please select at least one room.');</script>";
 }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Room</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100">

<div class="max-w-6xl mx-auto p-6">

<h2 class="text-3xl font-bold mb-6"><?php echo $hotel_name; ?></h2>

<?php if($selected_hotel_id && mysqli_num_rows($rooms) > 0){ ?>
<form method="POST">

<!-- User info -->
<div class="grid grid-cols-2 gap-4 mb-6">
 <input type="text" name="user_name" placeholder="Enter your name" required class="border p-3 rounded">
 <input type="email" name="user_email" placeholder="Enter your email" required class="border p-3 rounded">
</div>

<!-- Check-in / Check-out -->
<div class="grid grid-cols-2 gap-4 mb-6">
 <input type="date" name="checkin" id="checkin" required class="border p-3 rounded" onchange="calc()">
 <input type="date" name="checkout" id="checkout" required class="border p-3 rounded" onchange="calc()">
</div>

<!-- Rooms -->
<div class="grid grid-cols-2 gap-6">
<?php while($r=mysqli_fetch_assoc($rooms)){ ?>
<div class="bg-white shadow rounded p-4">
 <?php if(!empty($r['image'])){ ?>
 <img src="uploads/rooms/<?php echo $r['image']; ?>" class="w-full h-40 object-cover mb-3">
 <?php } else { ?>
 <div class="w-full h-40 bg-gray-200 flex items-center justify-center mb-3">No Image</div>
 <?php } ?>
 <h3 class="text-xl font-bold"><?php echo $r['room_type']; ?></h3>
 <p>Price: ₹<?php echo $r['price']; ?></p>
 <p>Available: <?php echo $r['available_rooms']; ?></p>

 <input type="hidden" name="room_id[]" value="<?php echo $r['id']; ?>">
 <input type="hidden" class="price" value="<?php echo $r['price']; ?>">
 <input type="number" name="qty[]" value="0" min="0" max="<?php echo $r['available_rooms']; ?>" class="qty border p-2 w-full mt-2" onchange="calc()">
</div>
<?php } ?>
</div>

<!-- Booking summary -->
<div class="mt-6 bg-white p-4 shadow rounded">
 <h3 class="text-xl font-bold mb-2">Booking Summary</h3>
 <p>Nights: <span id="nights">0</span></p>
 <p>Total: ₹<span id="total">0</span></p>
 <input type="hidden" name="total_amount" id="total_amount">
</div>

<button name="book" class="bg-blue-600 text-white px-6 py-3 mt-4 rounded">Confirm Booking</button>
</form>
<?php } else { ?>
<p>No rooms available for this hotel.</p>
<?php } ?>

</div>

<script>
function calc(){
 let qty = document.querySelectorAll(".qty");
 let price = document.querySelectorAll(".price");
 let nights = 1;

 let checkin = document.getElementById("checkin").value;
 let checkout = document.getElementById("checkout").value;

 if(checkin && checkout){
 let d1 = new Date(checkin);
 let d2 = new Date(checkout);
 nights = (d2 - d1) / (1000*60*60*24);
 if(nights <= 0) nights = 1;
 }
 document.getElementById("nights").innerText = nights;

 let total = 0;
 for(let i=0; i<qty.length; i++){
 let q = parseInt(qty[i].value) || 0;
 let p = parseInt(price[i].value) || 0;
 total += q*p*nights;
 }
 document.getElementById("total").innerText = total;
 document.getElementById("total_amount").value = total;
}
</script>

</body>
</html>
