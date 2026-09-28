<?php
session_start();
include("../config.php");

if(!isset($_SESSION['admin'])){
 header("Location: login.php");
 exit();
}

if(isset($_GET['id']))
{
 $id = $_GET['id'];

 // First get image name
 $q = mysqli_query($conn,"SELECT image FROM rooms WHERE id='$id'");
 $data = mysqli_fetch_assoc($q);

 // Delete image from uploads folder
 if($data && file_exists("../uploads/".$data['image'])){
 unlink("../uploads/".$data['image']);
 }

 // Delete room record
 mysqli_query($conn,"DELETE FROM rooms WHERE id='$id'");

 echo "<script>
 alert('Room Deleted Successfully');
 window.location='view_rooms.php';
 </script>";
}
else
{
 header("Location: view_rooms.php");
}
?>
