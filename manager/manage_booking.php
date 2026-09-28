<?php
session_start();
include("../config.php");

// Check manager login
if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];

// AJAX request for approve/cancel
if(isset($_POST['action']) && isset($_POST['booking_id'])){
 $id = intval($_POST['booking_id']);
 $action = $_POST['action'];
 $reason = $_POST['reason'] ?? '';

 if($action=="approve"){
 mysqli_query($conn,"UPDATE booking SET status='approved' WHERE id='$id'");
 echo json_encode(["status"=>"approved"]);
 exit();
 }
 if($action=="cancel"){
 mysqli_query($conn,"UPDATE booking SET status='cancelled', cancel_reason='".mysqli_real_escape_string($conn,$reason)."' WHERE id='$id'");
 echo json_encode(["status"=>"cancelled", "reason"=>$reason]);
 exit();
 }
}

// Fetch bookings for display
$sql = "SELECT b.*, h.hotel_name, u.name AS user_name
 FROM booking b
 JOIN hotel h ON b.hotel_id = h.id
 JOIN users u ON b.user_id = u.id
 WHERE h.manager_id='$manager_id'
 ORDER BY b.id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Bookings</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex min-h-screen">

<!-- Sidebar -->
<div class="w-64 bg-gray-800 text-white flex-shrink-0">
<?php include("sidebar.php"); ?>
</div>

<!-- Main Content -->
<div class="flex-1 p-6">

<h2 class="text-3xl font-bold mb-6 text-gray-800">
Manage Bookings
</h2>

<div class="overflow-x-auto bg-white rounded-xl shadow-lg">

<table class="min-w-full text-sm text-center">

<thead class="bg-gray-800 text-white">
<tr>
<th class="p-3">ID</th>
<th class="p-3">Booking No</th>
<th class="p-3">Hotel</th>
<th class="p-3">User</th>
<th class="p-3">Checkin</th>
<th class="p-3">Checkout</th>
<th class="p-3">Rooms</th>
<th class="p-3">Price</th>
<th class="p-3">Status</th>
<th class="p-3">Action</th>
</tr>
</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ 
$status = strtolower(trim($row['status'] ?? 'pending'));
?>

<tr id="booking-row-<?= $row['id'] ?>" 
class="border-b hover:bg-gray-50 transition">

<td class="p-3"><?= $row['id'] ?></td>
<td class="p-3 font-semibold"><?= $row['booking_no'] ?></td>
<td class="p-3"><?= $row['hotel_name'] ?></td>
<td class="p-3"><?= $row['user_name'] ?></td>
<td class="p-3"><?= $row['checkin_date'] ?></td>
<td class="p-3"><?= $row['checkout_date'] ?></td>
<td class="p-3"><?= $row['rooms'] ?></td>

<td class="p-3 font-bold text-success">
₹<?= $row['price'] ?>
</td>

<td id="status-<?= $row['id'] ?>" class="p-3">

<?php if($status=="pending"){ ?>
<span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
Pending
</span>

<?php } elseif($status=="approved"){ ?>

<span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
Approved
</span>

<?php } else { ?>

<span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
Cancelled
</span>

<br>
<small class="text-gray-500">
<?= htmlspecialchars($row['cancel_reason']) ?>
</small>

<?php } ?>

</td>

<td id="action-<?= $row['id'] ?>" class="p-3">

<div class="flex justify-center gap-2">

<?php if($status=="pending"){ ?>

<button onclick="approve(<?= $row['id'] ?>)" 
class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg shadow text-xs">
Approve
</button>

<button onclick="openCancel(<?= $row['id'] ?>)" 
class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg shadow text-xs">
Cancel
</button>

<?php } elseif($status=="approved"){ ?>

<button onclick="openCancel(<?= $row['id'] ?>)" 
class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg shadow text-xs">
Cancel
</button>

<?php } else { ?>

<span class="text-gray-400">-</span>

<?php } ?>

</div>
</td>

</tr>

<?php } ?>

</tbody>
</table>
</div>
</div>
</div>


<!-- Cancel Modal -->
<div id="cancelBox" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">

<div class="bg-white p-6 rounded-xl shadow-xl w-96">

<h3 class="text-xl font-bold mb-3">
Cancel Booking
</h3>

<textarea id="cancel_reason"
class="w-full border p-3 rounded-lg"
placeholder="Enter cancel reason..."></textarea>

<div class="flex justify-end gap-3 mt-4">

<button onclick="closeCancel()"
class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg">
Close
</button>

<button onclick="submitCancel()"
class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
Cancel Booking
</button>

</div>
</div>
</div>


<script>

let currentCancelId = 0;

function approve(id){
fetch('manage_booking.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:'action=approve&booking_id='+id
})
.then(res=>res.json())
.then(data=>{
if(data.status=="approved"){
document.getElementById('status-'+id).innerHTML=
'<span class="bg-green-600 text-white px-3 py-1 rounded-full text-xs">Approved</span>';

document.getElementById('action-'+id).innerHTML=
'<button onclick="openCancel('+id+')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs">Cancel</button>';
}
});
}

function openCancel(id){
currentCancelId=id;
document.getElementById("cancelBox").classList.remove("hidden");
}

function closeCancel(){
document.getElementById("cancelBox").classList.add("hidden");
}

function submitCancel(){
let reason=document.getElementById("cancel_reason").value.trim();

if(reason===""){
alert("Enter cancel reason");
return;
}

fetch('manage_booking.php',{
method:'POST',
headers:{'Content-Type':'application/x-www-form-urlencoded'},
body:'action=cancel&booking_id='+currentCancelId+'&reason='+encodeURIComponent(reason)
})
.then(res=>res.json())
.then(data=>{
if(data.status=="cancelled"){
document.getElementById('status-'+currentCancelId).innerHTML=
'<span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs">Cancelled</span><br><small>'+data.reason+'</small>';

document.getElementById('action-'+currentCancelId).innerHTML=
'<span class="text-gray-400">-</span>';

closeCancel();
}
});
}

feather.replace();
</script>

</body>
</html>
