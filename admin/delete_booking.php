<?php
session_start();
include("../config.php");

if(!isset($_SESSION['admin'])){
 header("Location: login.php");
 exit();
}

if(isset($_GET['id'])) {
 $id = $_GET['id'];
 mysqli_query($conn,"DELETE FROM bookings WHERE id='$id'");

 echo "<script>
 alert('Booking Deleted Successfully');
 window.location='view_bookings.php';
 </script>";
} else {
 header("Location: view_bookings.php");
}
?>
