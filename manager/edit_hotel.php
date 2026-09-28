
<?php
session_start();
include("../config.php");

if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];

/* CHECK HOTEL ID */
if(!isset($_GET['id'])){
 header("Location: manage_hotel.php");
 exit();
}

$hotel_id = $_GET['id'];

/* FETCH HOTEL DATA */
$result = mysqli_query($conn,"SELECT * FROM hotel 
WHERE id='$hotel_id' AND manager_id='$manager_id'");

$hotel = mysqli_fetch_assoc($result);

if(!$hotel){
 echo "Hotel not found!";
 exit();
}

/* UPDATE HOTEL */
if(isset($_POST['update_hotel'])){

$hotel_name = $_POST['hotel_name'];
$hotel_address = $_POST['hotel_address'];
$pincode = $_POST['pincode'];
$rating = $_POST['rating'];

if($_FILES['image']['name']!=""){

$image=time().'_'.$_FILES['image']['name'];
$tmp=$_FILES['image']['tmp_name'];

move_uploaded_file($tmp,"../uploads/".$image);

$update="UPDATE hotel SET 
hotel_name='$hotel_name',
hotel_address='$hotel_address',
pincode='$pincode',
rating='$rating',
image='$image'
WHERE id='$hotel_id' AND manager_id='$manager_id'";

}else{

$update="UPDATE hotel SET
hotel_name='$hotel_name',
hotel_address='$hotel_address',
pincode='$pincode',
rating='$rating'
WHERE id='$hotel_id' AND manager_id='$manager_id'";

}

mysqli_query($conn,$update);

header("Location: manage_hotel.php");
exit();

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Hotel</title>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>

 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>

<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<?php include("sidebar.php"); ?>

<!-- Main Content -->
<div class="flex-1 p-8">

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

<h2 class="text-2xl font-bold mb-6">Edit Hotel</h2>

<form method="POST" enctype="multipart/form-data" class="space-y-4">

<div>
<label class="font-semibold">Hotel Name</label>
<input type="text" name="hotel_name"
value="<?= $hotel['hotel_name'] ?>"
class="w-full border px-3 py-2 rounded"
required>
</div>

<div>
<label class="font-semibold">Hotel Address</label>
<textarea name="hotel_address"
class="w-full border px-3 py-2 rounded"
required><?= $hotel['hotel_address'] ?></textarea>
</div>

<div>
<label class="font-semibold">Hotel Image</label>

<img src="../uploads/<?= $hotel['image'] ?>"
class="w-32 mb-2 rounded">

<input type="file" name="image" class="w-full">
</div>

<div>
<label class="font-semibold">Pincode</label>
<input type="text" name="pincode"
value="<?= $hotel['pincode'] ?>"
class="w-full border px-3 py-2 rounded">
</div>

<div>
<label class="font-semibold">Rating</label>
<input type="number" step="0.1"
name="rating"
value="<?= $hotel['rating'] ?>"
class="w-full border px-3 py-2 rounded">
</div>

<button name="update_hotel"
class="bg-blue-600 text-white px-4 py-2 rounded">
Update Hotel
</button>

</form>

</div>

</div>

</body>
</html>
