<?php

function sendBookingMail($to,$subject,$message){

$from = "yourgmail@gmail.com";

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: Hotel Booking <$from>";

mail($to,$subject,$message,$headers);

}

?>
