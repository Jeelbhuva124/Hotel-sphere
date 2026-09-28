<?php
session_start();
include("../config.php");

if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];
$hotel_id = intval($_GET['id']);

// Delete only if hotel belongs to manager
mysqli_query($conn,"DELETE FROM hotels WHERE id='$hotel_id' AND manager_id='$manager_id'");

header("Location: manager_dashboard.php");
exit();
