<?php
session_start();
include("../config.php");

if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];

/* DELETE HOTEL */
if(isset($_GET['delete'])){
 $id=$_GET['delete'];
 mysqli_query($conn,"DELETE FROM hotel WHERE id='$id' AND manager_id='$manager_id'");
 header("Location: manage_hotel.php");
 exit();
}

/* ADD HOTEL */
if(isset($_POST['add_hotel'])){
 $hotel_name = $_POST['hotel_name'];
 $hotel_address = $_POST['hotel_address'];
 $pincode = $_POST['pincode'];
 $rating = $_POST['rating'];

 /* FETCH STATE & CITY FROM MANAGER */
 $m = mysqli_fetch_assoc(mysqli_query($conn,"SELECT state_id, city_id FROM managers WHERE id='$manager_id'"));
 $state_id = $m['state_id'];
 $city_id = $m['city_id'];

 /* IMAGE UPLOAD */
 $image = time()."_".$_FILES['image']['name'];
 $tmp = $_FILES['image']['tmp_name'];
 move_uploaded_file($tmp,"../uploads/".$image);

 mysqli_query($conn,"INSERT INTO hotel
 (manager_id,hotel_name,hotel_address,image,pincode,rating,state_id,city_id)
 VALUES ('$manager_id','$hotel_name','$hotel_address','$image','$pincode','$rating','$state_id','$city_id')");

 header("Location: manage_hotel.php");
 exit();
}

/* FETCH HOTELS WITH CITY & STATE */
$hotels=mysqli_query($conn,"SELECT h.*, c.city_name, s.state_name
 FROM hotel h
 LEFT JOIN cities c ON h.city_id = c.id
 LEFT JOIN states s ON h.state_id = s.id
 WHERE h.manager_id='$manager_id'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Hotels</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<style>
.card-hover:hover{
 transform:translateY(-5px);
 transition:0.3s;
 box-shadow:0 10px 20px rgba(0,0,0,0.1);
}
</style>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<?php include("sidebar.php"); ?>

<!-- Main Content -->
<div class="flex-1 p-8">
 <div class="max-w-7xl mx-auto">

 <h2 class="text-3xl font-bold mb-6 text-primary">Manage Hotels</h2>

 <!-- ADD HOTEL BUTTON -->
 <button onclick="showForm()" class="bg-green-600 text-white px-5 py-2 rounded-lg mb-6 hover:bg-green-700 transition">
  + Add Hotel
 </button>

 <!-- ADD HOTEL FORM -->
 <div id="hotelForm" class="hidden bg-white shadow-xl rounded-xl p-6 mb-8">
  <h3 class="text-xl font-semibold mb-4 text-gray-700">Add New Hotel</h3>
  <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <input type="text" name="hotel_name" placeholder="Hotel Name" class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 border-focus" required>
  <input type="text" name="pincode" placeholder="Pincode" class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 border-focus">
  <textarea name="hotel_address" placeholder="Address" class="md:col-span-2 border px-3 py-2 rounded focus:outline-none focus:ring-2 border-focus" required></textarea>
  <input type="number" step="0.1" name="rating" placeholder="Rating" class="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 border-focus">
  <input type="file" name="image" required class="md:col-span-2">
  <button name="add_hotel" class="md:col-span-2 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">Save Hotel</button>
  </form>
 </div>

 <!-- HOTEL LIST -->
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php while($row=mysqli_fetch_assoc($hotels)){ ?>
  <div class="bg-white rounded-xl shadow p-4 card-hover">
   <img src="../uploads/<?= $row['image'] ?>" class="w-full h-40 object-cover rounded-lg mb-3">
   <h3 class="font-bold text-lg text-gray-800"><?= $row['hotel_name'] ?></h3>
   <p class="text-gray-500 text-sm mb-2">📍 <?= $row['city_name'] ?>, <?= $row['state_name'] ?></p>
   <p class="text-yellow-500 font-semibold mb-2">⭐ <?= $row['rating'] ?></p>
   <div class="flex gap-2">
   <a href="edit_hotel.php?id=<?= $row['id'] ?>" class="flex-1 bg-blue-500 text-white py-1 rounded text-center hover:bg-blue-600 transition">Edit</a>
   <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this hotel?')" class="flex-1 bg-red-500 text-white py-1 rounded text-center hover:bg-red-600 transition">Delete</a>
   </div>
  </div>
  <?php } ?>
 </div>

 </div>
</div>

<script>
function showForm(){
 document.getElementById("hotelForm").classList.toggle("hidden");
}
</script>

</body>
</html>