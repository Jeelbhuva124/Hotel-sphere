<?php
include '../config.php';
include '../mail/send_mail.php';

$id = $_GET['id'];

$conn->query("UPDATE booking SET status='confirmed' WHERE id=$id");

$res = $conn->query("SELECT * FROM booking WHERE id=$id");
$data = $res->fetch_assoc();

$email = $data['user_email'];

$message = "
<h2>Booking Confirmed</h2>
<p>Your booking confirmed</p>
<p>Hotel: {$data['hotel_name']}</p>
";

sendMail($email, "Booking Confirmed", $message);

header("Location: index.php");
?>