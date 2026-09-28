<?php
session_start();
include("config.php");

/* LOGIN CHECK */
if(!isset($_SESSION['user_id'])){
 header("Location: login.php");
 exit();
}

$user_id = $_SESSION['user_id'];

/* FETCH BOOKINGS */
$bookings = mysqli_query($conn,"
SELECT b.*, p.package_name 
FROM booking b
JOIN packages p ON b.package_id = p.id
WHERE b.user_id='$user_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<style>
body{font-family:Arial;background:#f5f7ff;}
.card{background:white;padding:15px;margin:15px;border-radius:10px;}
button{padding:8px;background:blue;color:white;border:none;}
.pending{color:orange;}
.approved{color:green;}
</style>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body>

<h2>My Bookings</h2>

<?php if(mysqli_num_rows($bookings)==0){ echo "No bookings"; } ?>

<?php while($b=mysqli_fetch_assoc($bookings)){ ?>

<div class="card">

<h3><?php echo $b['package_name']; ?></h3>

<p>Status:
<span class="<?php echo $b['status']; ?>">
<?php echo $b['status']; ?>
</span>
</p>

<p>Check-in: <?php echo $b['check_in']; ?></p>
<p>Check-out: <?php echo $b['check_out']; ?></p>

<?php if($b['status']=="approved"){ ?>
<form action="payment.php" method="POST">
<input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
<button>Pay Now</button>
</form>
<?php } ?>

</div>

<?php } ?>

</body>
</html>