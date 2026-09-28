<?php
include("config.php");

$hotel_id = $_GET['id'];

$query="SELECT * FROM rooms WHERE hotel_id='$hotel_id'";
$result=mysqli_query($conn,$query);
?>

<h2>Rooms</h2>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div>

<h3><?php echo $row['room_type']; ?></h3>

<p>Price : ₹<?php echo $row['price']; ?></p>

<p>Available : <?php echo $row['available_room']; ?></p>

<a href="book_room.php?room_id=<?php echo $row['id']; ?>">Book Now</a>

</div>

<?php } ?>
