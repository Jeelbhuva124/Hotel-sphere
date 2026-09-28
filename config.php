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

<<<<<<< HEAD
?>
=======
include_once("alerts_interceptor.php");
?>
>>>>>>> dc11e22003aa1e3bff617af62710879c1b6085a5
