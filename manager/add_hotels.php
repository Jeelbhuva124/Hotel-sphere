<?php
session_start();
include("../config.php");

$manager_id=$_SESSION['manager_id'];

$states=mysqli_query($conn,"SELECT * FROM states WHERE status='active'");
$cities=mysqli_query($conn,"SELECT * FROM cities WHERE status='active'");

if(isset($_POST['add_hotel']))
{

$hotel_name=$_POST['hotel_name'];
$hotel_address=$_POST['hotel_address'];
$state_id=$_POST['state_id'];
$city_id=$_POST['city_id'];
$pincode=$_POST['pincode'];
$rating=$_POST['rating'];

$image=time()."_".$_FILES['image']['name'];
$tmp=$_FILES['image']['tmp_name'];

move_uploaded_file($tmp,"../uploads/".$image);

mysqli_query($conn,"
INSERT INTO hotel
(manager_id,hotel_name,image,hotel_address,state_id,city_id,pincode,rating,status)
VALUES
('$manager_id','$hotel_name','$image','$hotel_address','$state_id','$city_id','$pincode','$rating','approved')
");

header("Location: manage_hotels.php");

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add Hotel</title>
<script src="https://cdn.tailwindcss.com"></script>

 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body class="bg-gray-100 p-6">

<h1 class="text-3xl font-bold mb-6">Add Hotel</h1>

<form method="POST" enctype="multipart/form-data"
class="bg-white p-6 rounded shadow space-y-3">

<input type="text" name="hotel_name"
placeholder="Hotel Name"
class="w-full p-2 border">

<textarea name="hotel_address"
placeholder="Address"
class="w-full p-2 border"></textarea>

<input type="file" name="image"
class="w-full p-2 border">

<select name="state_id"
class="w-full p-2 border">

<option value="">Select State</option>

<?php while($s=mysqli_fetch_assoc($states)){ ?>

<option value="<?= $s['id'] ?>">
<?= $s['state_name'] ?>
</option>

<?php } ?>

</select>

<select name="city_id"
class="w-full p-2 border">

<option value="">Select City</option>

<?php while($c=mysqli_fetch_assoc($cities)){ ?>

<option value="<?= $c['id'] ?>">
<?= $c['city_name'] ?>
</option>

<?php } ?>

</select>

<input type="text" name="pincode"
placeholder="Pincode"
class="w-full p-2 border">

<input type="number" step="0.1"
name="rating"
placeholder="Rating"
class="w-full p-2 border">

<button name="add_hotel"
class="bg-green-600 text-white px-4 py-2 rounded">

Add Hotel

</button>

</form>

</body>
</html>