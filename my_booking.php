<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id'])){
 header("Location: login.php");
 exit();
}

$user_id = $_SESSION['user_id'];

// Handle AJAX cancel request
if(isset($_POST['action']) && $_POST['action'] == 'cancel_booking'){
 $booking_id = intval($_POST['booking_id']);
 $reason = mysqli_real_escape_string($conn, $_POST['reason'] ?? '');
 if($booking_id > 0 && !empty($reason)){
 $update = mysqli_query($conn, "UPDATE booking SET status='Cancelled', cancel_reason='$reason' WHERE id='$booking_id' AND user_id='$user_id'");
 echo json_encode($update ? ['status'=>'success','reason'=>$reason] : ['status'=>'error','msg'=>'Database update failed']);
 } else {
 echo json_encode(['status'=>'error','msg'=>'Invalid input']);
 }
 exit();
}

// Fetch bookings with hotel info
$result = mysqli_query($conn, "
 SELECT b.*, h.hotel_name 
 FROM booking b
 JOIN hotel h ON b.hotel_id = h.id
 WHERE b.user_id='$user_id'
 ORDER BY b.id DESC
");

$bookings = [];
if($result){
 while($row = mysqli_fetch_assoc($result)){
 $booking_id = $row['id'];
 $booking_no = $row['booking_no'];

 // Check payment table for this booking
 // Check payment table for this booking
$pay_check = mysqli_query($conn, "
 SELECT * FROM payment
 WHERE booking_id='$booking_id' AND payment_status='paid'
");
if($pay_check && mysqli_num_rows($pay_check) > 0 && strtolower($row['status']) != 'paid'){
 // Update booking status to Paid
 mysqli_query($conn, "UPDATE booking SET status='Paid' WHERE id='$booking_id'");
 $row['status'] = 'Paid'; // update row for display
}

 $bookings[] = $row;
 }
} else {
 die("Database error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Bookings</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-50 min-h-screen">

<?php include("navbar.php"); ?>

<div class="max-w-7xl mx-auto p-4 flex justify-end">
 <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">
 Logout
 </a>
</div>

<main class="max-w-7xl mx-auto p-6">
<?php
if(empty($bookings)){ 
 echo "<p>No bookings yet</p>"; 
} else {
 echo '<div class="grid gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">';
 foreach($bookings as $row){
 $status = strtolower(trim($row['status'] ?? 'pending'));
 $status_html = '';
 $action_html = '';

 // STATUS DISPLAY
 switch($status){
  case "pending":
  $status_html = '<span class="bg-yellow-500 text-white px-3 py-1 rounded-full">Pending</span>';
  break;
  case "approved":
  case "confirm":
  case "confirmed":
  $status_html = '<span class="bg-green-600 text-white px-3 py-1 rounded-full">Approved</span>';
  $action_html .= '<a href="payment.php?booking_id='.$row['id'].'" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow mt-3 inline-block">
     <i data-feather="credit-card"></i> Pay Now
     </a>';
  $action_html .= '<button onclick="openCancel('.$row['id'].')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow mt-3 ml-2">Cancel Booking</button>';
  break;
  case "paid":
  $status_html = '<span class="bg-green-700 text-white px-3 py-1 rounded-full">Paid</span>';
  $action_html .= '<button onclick="openCancel('.$row['id'].')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow mt-3">Cancel Booking</button>';
  break;
  case "cancelled":
  $reason = $row['cancel_reason'] ?? 'No reason provided';
  $status_html = '<span class="bg-red-600 text-white px-3 py-1 rounded-full">Cancelled</span>
    <p class="text-xs text-error mt-1">Reason: '.htmlspecialchars($reason).'</p>';
  break;
  default:
  $status_html = '<span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full">Unknown</span>';
 }

 echo '<div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all" id="booking-'.$row['id'].'">
  <h2 class="text-xl font-bold mb-2">Booking #'.$row['booking_no'].'</h2>
  <p>Hotel: '.$row['hotel_name'].'</p>
  <p>Check-in: '.$row['checkin_date'].'</p>
  <p>Check-out: '.$row['checkout_date'].'</p>
  <p>Rooms: '.$row['rooms'].'</p>
  <p>Room Type: '.($row['room_type'] ?? 'Standard').'</p>
  <p>Price: ₹'.$row['price'].'</p>
  <div class="status mt-2" id="status-'.$row['id'].'">'.$status_html.'</div>
  <div class="actions mt-2" id="actions-'.$row['id'].'">'.$action_html.'</div>
  </div>';
 }
 echo '</div>';
}
?>
</main>

<!-- Cancel Modal -->
<div id="cancelModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
 <div class="bg-white p-6 rounded-lg shadow-lg w-96">
 <h3 class="text-xl font-bold mb-3 text-gray-800">Cancel Booking</h3>
 <textarea id="cancel_reason" class="w-full border p-3 rounded focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter cancel reason..."></textarea>
 <div class="flex justify-end gap-3 mt-4">
  <button onclick="closeCancel()" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded transition">Close</button>
  <button onclick="submitCancel()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">Cancel Booking</button>
 </div>
 </div>
</div>

<script>
let currentCancelId = 0;

function openCancel(id){
 currentCancelId = id;
 document.getElementById("cancel_reason").value = '';
 document.getElementById("cancelModal").classList.remove("hidden");
}
function closeCancel(){
 document.getElementById("cancelModal").classList.add("hidden");
}
function submitCancel(){
 let reason = document.getElementById("cancel_reason").value.trim();
 if(reason === ""){ alert("Please enter a reason"); return; }

 fetch("my_booking.php", {
 method: "POST",
 headers: {"Content-Type":"application/x-www-form-urlencoded"},
 body: "action=cancel_booking&booking_id=" + currentCancelId + "&reason=" + encodeURIComponent(reason)
 })
 .then(res => res.json())
 .then(data => {
 if(data.status === "success"){
  document.getElementById("status-" + currentCancelId).innerHTML = '<span class="bg-red-600 text-white px-3 py-1 rounded-full">Cancelled</span><p class="text-xs text-error mt-1">Reason: '+data.reason+'</p>';
  document.getElementById("actions-" + currentCancelId).innerHTML = '';
  closeCancel();
 } else {
  alert(data.msg || "Error cancelling booking");
 }
 });
}

feather.replace();
</script>

</body>
</html>