<?php
include '../config.php';
include '../mail/send_mail.php';

$id = $_GET['id'];

$conn->query("UPDATE booking SET status='cancelled' WHERE id=$id");

$res = $conn->query("SELECT * FROM booking WHERE id=$id");
$data = $res->fetch_assoc();

$email = $data['user_email'];

$message = "
<h2>Booking Cancelled</h2>
<p>Your booking cancelled</p>
<p>Hotel: {$data['hotel_name']}</p>
";

sendMail($email, "Booking Cancelled", $message);

header("Location: index.php");
?>
