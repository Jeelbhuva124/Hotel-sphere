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
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
</script>
<script src="https://cdn.tailwindcss.com"></script>
<title><?php echo htmlspecialchars($hotel['hotel_name']); ?> - Rooms</title>

<style>
body{font-family:'Roboto', sans-serif; background: var(--bg-color) !important; color: var(--text-color) !important; margin:0;}
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
.left{flex:3;min-width:700px;overflow-x:auto;background: var(--card-bg) !important;padding:10px;border-radius:10px;}
table{width:100%;min-width:900px;border-collapse:collapse;}
th{background: var(--primary-color) !important;color:white;padding:12px;text-align:center;}
td{border:1px solid var(--border-color) !important;padding:12px;text-align:center;vertical-align:middle;}
.room img{width:130px;height:90px;object-fit:cover;border-radius:6px;cursor:pointer;}
.room-title{color:#0071c2;font-size:18px;font-weight:bold;}
.feature{display:inline-block;border:1px solid #ccc;padding:4px 8px;margin:3px;font-size:12px;border-radius:5px;background:#f1f1f1;}
.feature.red{color:red;border-color:red;background:#ffeaea;}
.price{font-size:18px;font-weight:bold;}
.available{font-size:14px;font-weight:bold;margin-top:5px;}
.available.green{color:green;}
.available.red{color:red;}
.right{flex:1;min-width:250px;}
.summary{position:sticky;top:100px;background: var(--card-bg) !important; border: 1px solid var(--border-color) !important; padding:20px;border-radius:10px;}
.reserve{background: var(--btn-glass-bg) !important; color:var(--btn-glass-text) !important; padding:12px;border:none;width:100%;border-radius:5px;cursor:pointer;}
.selected-box{background: var(--bg-color) !important; border: 1px solid var(--border-color) !important; padding:10px;border-radius:8px;margin-bottom:10px;font-size:14px;text-align:left;}
select.qty, select.guests{padding:4px;border-radius:4px;border:1px solid #ccc;width:60px;}
.popup{
display:none;
position:fixed;
top:0;left:0;width:100%;height:100%;
background:rgba(0,0,0,0.8);
justify-content:center;align-items:center;
}
.popup img{max-width:90%;max-height:80%;border-radius:10px;}
.close{position:absolute;top:20px;right:30px;font-size:40px;color:white;cursor:pointer;}
</style>
 <link rel="stylesheet" href="/HotelManagement/style.css">
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

$booked_result = mysqli_query($conn,"SELECT SUM(quantity) as booked FROM bookings WHERE room_id=".$row['id']." AND status='confirmed'");
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