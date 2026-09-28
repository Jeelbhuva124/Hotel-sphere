<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "hotel_db";

$conn = mysqli_connect($host,$user,$password,$database);

if(!$conn)
{
die("Database connection failed");
}

include_once("alerts_interceptor.php");
?>