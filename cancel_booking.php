<?php
include("config.php");

$id = $_GET['id'];

mysqli_query($conn,"UPDATE bookings
SET status='cancelled'
WHERE id='$id'");

header("Location: my_bookings.php");
?>