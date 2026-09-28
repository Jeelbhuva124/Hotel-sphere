<?php
include("../config.php");

$id = $_GET['id'];
mysqli_query($conn,"UPDATE bookings SET status='Approved' WHERE id=$id");

header("Location: bookings.php");
?>

