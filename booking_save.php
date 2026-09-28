<?php
include("db.php");

$name=$_POST['name'];
$email=$_POST['email'];
$checkin=$_POST['checkin'];
$rooms=$_POST['rooms'];
$adults=$_POST['adults'];
$children=$_POST['children'];

$sql="INSERT INTO bookings(name,email,checkin,rooms,adults,children)
VALUES('$name','$email','$checkin','$rooms','$adults','$children')";

if($conn->query($sql)){
 echo "<h2>Booking Successful ✅</h2>";
 echo "<a href='index.php'>Back</a>";
}else{
 echo "Error saving booking";
}
?>
