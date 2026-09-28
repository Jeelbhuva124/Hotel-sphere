<?php
session_start();
include("../config.php");

if(!isset($_SESSION['admin'])){
 header("Location: login.php");
 exit();
}

$id = $_GET['id'];

// Fetch old data
$data = $conn->query("SELECT * FROM rooms WHERE id=$id")->fetch_assoc();

if(isset($_POST['update']))
{
 $hotel = $_POST['hotel'];
 $location = $_POST['location'];
 $type = $_POST['type'];
 $roomno = $_POST['roomno'];
 $price = $_POST['price'];
 $desc = $_POST['desc'];

 $amenities = "";
 if(isset($_POST['amenities'])){
 $amenities = implode(",", $_POST['amenities']);
 }

 // IMAGE UPDATE CHECK
 if($_FILES['image']['name']!="")
 {
 // Delete old image
 unlink("../uploads/".$data['image']);

 // Upload new image
 $img = time().$_FILES['image']['name'];
 $tmp = $_FILES['image']['tmp_name'];
 move_uploaded_file($tmp,"../uploads/".$img);
 }
 else
 {
 $img = $data['image']; // keep old
 }

 // UPDATE QUERY
 $sql = "UPDATE rooms SET
 hotel_name='$hotel',
 location='$location',
 room_type='$type',
 room_no='$roomno',
 price='$price',
 description='$desc',
 amenities='$amenities',
 image='$img'
 WHERE id=$id";

 mysqli_query($conn,$sql);

 echo "<script>alert('Room Updated Successfully'); window.location='view_rooms.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Room</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body class="bg-gray-100">

<div class="flex">

<?php include("sidebar.php"); ?>

<div class="ml-64 p-10 w-full">

<h2 class="text-3xl font-bold mb-6">Edit Room</h2>

<form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow max-w-3xl">

<label>Hotel Name</label>
<input type="text" name="hotel" value="<?= $data['hotel_name'] ?>" class="w-full border p-2 mb-3">

<label>Location</label>
<input type="text" name="location" value="<?= $data['location'] ?>" class="w-full border p-2 mb-3">

<label>Room Type</label>
<select name="type" class="w-full border p-2 mb-3">
<option <?= $data['room_type']=="Standard"?"selected":"" ?>>Standard</option>
<option <?= $data['room_type']=="Deluxe"?"selected":"" ?>>Deluxe</option>
<option <?= $data['room_type']=="Suite"?"selected":"" ?>>Suite</option>
</select>

<label>Room No</label>
<input type="text" name="roomno" value="<?= $data['room_no'] ?>" class="w-full border p-2 mb-3">

<label>Price</label>
<input type="number" name="price" value="<?= $data['price'] ?>" class="w-full border p-2 mb-3">

<label>Description</label>
<textarea name="desc" class="w-full border p-2 mb-3"><?= $data['description'] ?></textarea>

<label>Amenities</label><br>

<?php
$amen = explode(",",$data['amenities']);
?>

<input type="checkbox" name="amenities[]" value="WiFi" <?= in_array("WiFi",$amen)?"checked":"" ?>> WiFi
<input type="checkbox" name="amenities[]" value="Pool" <?= in_array("Pool",$amen)?"checked":"" ?>> Pool
<input type="checkbox" name="amenities[]" value="Parking" <?= in_array("Parking",$amen)?"checked":"" ?>> Parking
<input type="checkbox" name="amenities[]" value="AC" <?= in_array("AC",$amen)?"checked":"" ?>> AC

<br><br>

<label>Current Image</label><br>
<img src="../uploads/<?= $data['image'] ?>" class="h-24 mb-3">

<label>Change Image</label>
<input type="file" name="image" class="mb-4">

<br>

<button name="update" class="bg-green-600 text-white px-6 py-2 rounded">
Update Room
</button>

</form>

</div>
</div>

</body>
</html>