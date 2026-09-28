<?php
session_start();
include("config.php");

/* LOGIN CHECK */
if(!isset($_SESSION['user_id'])){
 header("Location: login.php");
 exit();
}

$user_id = $_SESSION['user_id'];

$package_id = $_POST['package_id'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$guests = $_POST['guests'];

/* INSERT */
mysqli_query($conn,"INSERT INTO booking 
(user_id, package_id, check_in, check_out, guests, status)
VALUES 
('$user_id','$package_id','$check_in','$check_out','$guests','pending')");

/* REDIRECT */
header("Location: mybookings.php");
exit();
