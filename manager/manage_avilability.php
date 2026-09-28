<?php
include("../config.php");

$checkin = "";
$checkout = "";
$nights = 0;
$error = "";

if(isset($_POST['search'])){
 $checkin = $_POST['checkin'];
 $checkout = $_POST['checkout'];

 if($checkin >= $checkout){
 $error = "Check-out date must be after Check-in!";
 }else{
 $in = strtotime($checkin);
 $out = strtotime($checkout);
 $nights = ($out - $in) / (60*60*24);
 }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Availability & Pricing</title>

<style>
body{
 font-family:Arial;
 background:#f2f2f2;
}

.container{
 width:90%;
 margin:auto;
}

.search-box{
 background:#fff;
 padding:20px;
 margin:20px 0;
 border-radius:10px;
}

.room{
 background:#fff;
 padding:15px;
 margin:15px 0;
 border-radius:10px;
}

.available{ color:green; }
.not{ color:red; }

.price{
 font-size:20px;
 font-weight:bold;
}

button{
 background:green;
 color:#fff;
 padding:8px 15px;
 border:none;
 border-radius:5px;
}
</style>

 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body>

<div class="container">

<h2>Check Availability & Pricing</h2>

<!-- 🔍 SEARCH -->
<div class="search-box">
<form method="post">
 Check-in:
 <input type="date" name="checkin" required value="<?php echo $checkin; ?>">

 Check-out:
 <input type="date" name="checkout" required value="<?php echo $checkout; ?>">

 <button name="search">Search</button>
</form>

<p style="color:red;"><?php echo $error; ?></p>

</div>

<?php
$sql = mysqli_query($conn,"SELECT * FROM rooms");

while($row = mysqli_fetch_assoc($sql)){

 $price = $row['price'];
 $available = $row['available_rooms'];

 $total = $price * $nights;
?>

<div class="room">

<h3><?php echo $row['room_type']; ?></h3>

<?php if(isset($_POST['search']) && $error == ""){ ?>

 <!-- AVAILABILITY -->
 <?php if($available > 0){ ?>
 <p class="available">✔ Available (<?php echo $available; ?> rooms left)</p>
 <?php }else{ ?>
 <p class="not">❌ Not Available</p>
 <?php } ?>

 <!-- PRICING -->
 <p>Price: ₹<?php echo $price; ?> / night</p>
 <p>Nights: <?php echo $nights; ?></p>
 <p class="price">Total Price: ₹<?php echo $total; ?></p>

 <?php if($available > 0){ ?>
 <button>Book Now</button>
 <?php } ?>

<?php }else{ ?>

 <p>Price: ₹<?php echo $price; ?> / night</p>

<?php } ?>

</div>

<?php } ?>

</div>

</body>
</html>