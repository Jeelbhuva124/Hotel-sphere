<?php
include("config.php");
?>

<!DOCTYPE html>
<html>
<head>

<title>Available Rooms</title>

<style>

body{
font-family:Arial;
background:#f1f3f5;
margin:0;
}

.container{
width:90%;
margin:auto;
margin-top:40px;
}

.room-card{
display:flex;
background:white;
margin-bottom:20px;
border-radius:8px;
box-shadow:0 2px 10px rgba(0,0,0,0.1);
overflow:hidden;
}

.room-img{
width:250px;
height:180px;
object-fit:cover;
}

.room-details{
padding:20px;
flex:1;
}

.room-title{
color:#0071c2;
font-size:20px;
font-weight:bold;
}

.amenities{
color:#555;
margin-top:8px;
}

.package{
color:green;
margin-top:8px;
}

.price-box{
text-align:right;
padding:20px;
width:200px;
border-left:1px solid #eee;
}

.price{
font-size:22px;
font-weight:bold;
}

.reserve-btn{
background:#0071c2;
color:white;
border:none;
padding:10px 15px;
border-radius:5px;
cursor:pointer;
margin-top:10px;
}

.reserve-btn:hover{
background:#005999;
}

</style>

 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>

<body>

<div class="container">

<h2>Available Rooms</h2>

<?php

$sql="
SELECT 
r.id as room_id,
r.room_type,
r.price,
r.image,
p.id as package_id,
p.package_name,
p.extra_price
FROM rooms r
LEFT JOIN room_packages p
ON r.id=p.room_id
WHERE r.status='available'
";

$result=mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($result)){

$total_price=$row['price']+$row['extra_price'];

?>

<div class="room-card">

<img class="room-img"
src="manager/uploads/<?php echo $row['image']; ?>">

<div class="room-details">

<div class="room-title">
<?php echo $row['room_type']; ?>
</div>

<div class="amenities">
✔ Free WiFi <br>
✔ Air Conditioning <br>
✔ Private Bathroom
</div>

<div class="package">
🍳 <?php echo $row['package_name']; ?>
</div>

</div>

<div class="price-box">

<div class="price">
₹<?php echo $total_price; ?>
</div>

<form action="booking.php" method="GET">

<input type="hidden" name="room_id"
value="<?php echo $row['room_id']; ?>">

<input type="hidden" name="package_id"
value="<?php echo $row['package_id']; ?>">

<button class="reserve-btn">

I'll Reserve

</button>

</form>

</div>

</div>

<?php } ?>

</div>

</body>
</html>
