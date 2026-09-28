<?php include "config.php";

$id=$_GET['id'];
$uid=$_SESSION['user']['id'];

$conn->query("INSERT INTO bookings(user_id,hotel_id)
VALUES($uid,$id)");

header("Location: payment.php");
?>
