<?php
session_start();
include("../config.php");

if(!isset($_SESSION['manager_id'])) header("Location: manager_login.php");

if(!isset($_GET['id'])) die("Room ID missing");

$room_id = intval($_GET['id']);

/* Delete room amenities first */
mysqli_query($conn,"DELETE FROM room_amenities WHERE room_id='$room_id'");

/* Delete room */
mysqli_query($conn,"DELETE FROM rooms WHERE id='$room_id'");

header("Location: manage_rooms.php");
exit;