<?php
session_start();
include("config.php");

$hotel_id = $_GET['hotel_id'] ?? 0;

/* ROOMS */
$result = mysqli_query($conn,"SELECT * FROM room WHERE hotel_id='$hotel_id'");

/* HOTEL INFO */
$hotel_info = mysqli_query($conn,"SELECT * FROM hotel WHERE id='$hotel_id'");
$hotel = mysqli_fetch_assoc($hotel_info);
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo htmlspecialchars($hotel['hotel_name']); ?> - Rooms</title>
<style>
body{font-family:Arial;background:#f2f2f2;margin:0;}
.hero{
position:relative;
width:100%;
height:400px;
background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
url('uploads/<?php echo $hotel['image']; ?>') top center / cover no-repeat;
display:flex;
align-items:center;
justify-content:center;
color:white;
text-align:center;
}
.hero h1{font-size:48px;font-weight:bold;text-shadow:2px 2px 6px rgba(0,0,0,0.7);}
.main{display:flex;width:95%;margin:auto;gap:20px;flex-wrap:wrap;margin-top:20px;}
.left{flex:3;min-width:700px;overflow-x:auto;background:white;padding:0;border-radius:10px; transition: all 0.3s; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05);}
table{width:100%;min-width:900px;border-collapse:collapse;}
th{background:#3b82f6;color:white;padding:12px 20px;text-align:center; font-weight:600; white-space:nowrap;}
td{border:1px solid #e5e7eb;padding:16px 20px;text-align:center;vertical-align:middle;}
th:first-child, td:first-child { text-align: left; width: 35%; padding-left: 24px; }
th:last-child, td:last-child { padding-right: 24px; }
tr:hover { background: #f9fafb; }
.room img{width:150px;height:100px;object-fit:cover;border-radius:6px;cursor:pointer; margin-bottom: 10px;}
.room-title{color:#3b82f6;font-size:20px;font-weight:bold;margin-bottom:8px;}
.feature{display:inline-block;border:1px solid #d1d5db;padding:4px 8px;margin:3px;font-size:12px;border-radius:6px;background:#f3f4f6; color: #374151;}
.feature.red{color:#dc2626;border-color:#fca5a5;background:#fef2f2;}
.price{font-size:18px;font-weight:bold;}
.available{font-size:14px;font-weight:bold;margin-top:5px;}
.available.green{color:#16a34a;}
.available.red{color:#dc2626;}
.right{flex:1;min-width:250px;}
.summary{position:sticky;top:100px;background:#eff6ff;padding:20px;border-radius:10px; transition: all 0.3s; color: #1f2937; border: 1px solid #bfdbfe;}
.reserve{background:#3b82f6;color:white;padding:12px;border:none;width:100%;border-radius:8px;cursor:pointer;font-weight:bold;transition: background 0.3s;}
.reserve:hover{background:#2563eb;}
.selected-box{background:white;padding:12px;border-radius:8px;margin-bottom:12px;font-size:14px;text-align:left; color: #374151; border: 1px solid #d1d5db;}
select.qty, select.guests{padding:6px;border-radius:6px;border:1px solid #d1d5db;width:65px; color: #374151; outline:none;}
select.qty:focus, select.guests:focus{border-color:#3b82f6;}
.popup{
display:none;
position:fixed;
top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.8);
justify-content:center;align-items:center;
z-index: 9999;
}
.popup img{max-width:90%;max-height:80%;border-radius:10px;box-shadow: 0 10px 25px rgba(0,0,0,0.5);}
.close{position:absolute;top:20px;right:30px;font-size:40px;color:white;cursor:pointer;}

/* DARK MODE OVERRIDES */
[data-theme="dark"] .left { background: #1f2937; border: 1px solid #374151; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
[data-theme="dark"] td, [data-theme="dark"] th { border: 1px solid #374151; }
[data-theme="dark"] th { background: #1e3a8a; color: #ffffff; }
[data-theme="dark"] tr:hover { background: rgba(255,255,255,0.03); }
[data-theme="dark"] .room-title { color: #60a5fa; }
[data-theme="dark"] .feature { background: #374151; color: #f3f4f6; border-color: #4b5563; }
[data-theme="dark"] .feature.red { background: #7f1d1d; color: #fca5a5; border-color: #991b1b; }
[data-theme="dark"] .summary { background: #1f2937; border: 1px solid #374151; color: #f3f4f6; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
[data-theme="dark"] .selected-box { background: #374151; color: #f3f4f6; border: 1px solid #4b5563; }
[data-theme="dark"] select.qty, [data-theme="dark"] select.guests { background: #374151; color: #fff; border: 1px solid #4b5563; }
[data-theme="dark"] .available.green { color: #4ade80; }
[data-theme="dark"] .available.red { color: #f87171; }
</style>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body>

<?php include("navbar.php"); ?>

<div class="hero">
<h1><?php echo htmlspecialchars($hotel['hotel_name']); ?></h1>
</div>

<div class="main">
<div class="left">

<table>
<tr>
<th>Room type</th>
<th>Guests</th>
<th>Price</th>
<th>Your choices</th>
<th>Select rooms</th>
<th>Available</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ 
$packages = mysqli_query($conn,"SELECT * FROM packages WHERE room_id=".$row['id']);
$pkgCount = mysqli_num_rows($packages);

$booked_result = mysqli_query($conn,"SELECT SUM(br.quantity) as booked FROM booking_rooms br JOIN bookings b ON br.booking_id = b.id WHERE br.room_id=".$row['id']." AND b.status='confirmed'");
$booked_rooms = 0;
if($booked_result){
$booked_rooms = mysqli_fetch_assoc($booked_result)['booked'] ?? 0;
}

$total_available = $row['available_rooms'] ?? 0;
$available_rooms = max(0, $total_available - $booked_rooms);

$roomType = strtolower($row['room_type']);
$maxGuests = (strpos($roomType,'triple')!==false || strpos($roomType,'family')!==false)?4:2;
?>

<?php if($pkgCount > 0){ $first = true; ?>
<?php while($p=mysqli_fetch_assoc($packages)){ ?>

<tr>

<?php if($first){ ?>
<td rowspan="<?php echo $pkgCount; ?>" class="room">

<img src="uploads/<?php echo $row['image']; ?>" onclick="openPopup(this.src)">

<br>

<div class="room-title"><?php echo $row['room_type']; ?></div>
<p><?php echo $row['bed_type']; ?></p>

<div class="feature"><?php echo $row['room_size']; ?> m²</div>
<div class="feature"><?php echo $row['view_type']; ?></div>

<br>

<?php
$fac = explode(",", $row['facilities']);
foreach($fac as $f){
$f = trim($f);
if(stripos($f,'no smoking')!==false){
echo "<span class='feature red'>$f</span>";
}else{
echo "<span class='feature'>$f</span>";
}
}
?>

</td>
<?php $first = false; } ?>

<td>
<select class="guests">
<?php for($g=1;$g<=$maxGuests;$g++){ echo "<option>$g</option>"; } ?>
</select>
</td>

<td class="price">₹ <?php echo $p['price']; ?></td>

<td>
<div class="green">✔ <?php echo $p['description']; ?></div>
Pay at property
</td>

<td>
<select class="qty"
data-room="<?php echo $row['room_type']; ?>"
data-price="<?php echo $p['price']; ?>"
data-available="<?php echo $available_rooms; ?>">
<?php for($i=0;$i<=$available_rooms;$i++){ echo "<option>$i</option>"; } ?>
</select>
</td>

<td>
<span class="available <?php echo ($available_rooms>0)?"green":"red"; ?>">
<?php echo ($available_rooms>0)?$available_rooms." rooms":"Fully booked"; ?>
</span>
</td>

</tr>

<?php } } ?>

<?php } ?>

</table>
</div>

<div class="right">
<div class="summary">

<h3>Selected Rooms</h3>
<div id="roomsList" class="selected-box">No rooms selected</div>

<h3>Total</h3>
<h2 id="total">₹ 0</h2>

<button class="reserve" id="reserveBtn">I'll reserve</button>

<p style="font-size:12px;">You won't be charged yet</p>

</div>
</div>
</div>

<div class="popup" id="popup">
<span class="close" onclick="closePopup()">×</span>
<img id="popupImg">
</div>

<script>

function getData(){

let total = 0;
let rooms = 0;
let roomText = "";

let qtySelects = document.querySelectorAll(".qty");
let guestSelects = document.querySelectorAll(".guests");

qtySelects.forEach((s, i) => {

let qty = parseInt(s.value);
let price = parseFloat(s.dataset.price);
let room = s.dataset.room;
let guests = parseInt(guestSelects[i].value);

if(qty > 0){
roomText += room + " x " + qty + " (Guests: " + guests + ")<br>";
}

total += qty * price;
rooms += qty;

});

if(roomText=="") roomText="No rooms selected";

document.getElementById("roomsList").innerHTML = roomText;
document.getElementById("total").innerText = "₹ " + total;

return {total, rooms};

}

document.querySelectorAll(".qty, .guests").forEach(select=>{
select.addEventListener("change", getData);
});


document.getElementById("reserveBtn").addEventListener("click", function(){

let qtySelects = document.querySelectorAll(".qty");
let guestSelects = document.querySelectorAll(".guests");

let guestData = [];
let totalRooms = 0;
let totalPrice = 0;

qtySelects.forEach((s, i)=>{

let qty = parseInt(s.value);
let price = parseFloat(s.dataset.price);

if(qty > 0){

guestData.push({
room: s.dataset.room,
qty: qty,
guests: parseInt(guestSelects[i].value),
price: price
});

totalRooms += qty;
totalPrice += qty * price;

}

});

if(totalRooms == 0){
alert("Select at least 1 room");
return;
}

window.location.href =
"booking.php?hotel_id=<?php echo $hotel_id; ?>" +
"&rooms=" + totalRooms +
"&total=" + totalPrice +
"&data=" + encodeURIComponent(JSON.stringify(guestData));

});


function openPopup(src){
document.getElementById("popup").style.display="flex";
document.getElementById("popupImg").src = src;
}

function closePopup(){
document.getElementById("popup").style.display="none";
}

</script>

</body>
</html>
