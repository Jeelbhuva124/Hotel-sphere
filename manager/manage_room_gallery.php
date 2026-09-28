<?php
session_start();
include("../config.php");

// Manager login check
if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];
$room_id = intval($_GET['room_id'] ?? 0);

if($room_id==0){
 echo "Invalid Room ID"; exit;
}

// --- ADD GALLERY IMAGES ---
if(isset($_POST['add_gallery'])){
 if(isset($_FILES['room_images'])){
 foreach($_FILES['room_images']['name'] as $key=>$img){
  $tmp = $_FILES['room_images']['tmp_name'][$key];
  if($img){
  move_uploaded_file($tmp,"../uploads/".$img);
  mysqli_query($conn,"INSERT INTO room_images(room_id,image) VALUES('$room_id','$img')");
  }
 }
 }
 echo "<script>alert('Images added'); window.location='manage_room_gallery.php?room_id=$room_id';</script>";
 exit;
}

// --- DELETE GALLERY IMAGE ---
if(isset($_GET['delete_img'])){
 $img_id = intval($_GET['delete_img']);
 $img_data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT image FROM room_images WHERE id='$img_id' AND room_id='$room_id'"));
 if($img_data){
 unlink("../uploads/".$img_data['image']);
 mysqli_query($conn,"DELETE FROM room_images WHERE id='$img_id'");
 echo "<script>alert('Image deleted'); window.location='manage_room_gallery.php?room_id=$room_id';</script>";
 exit;
 }
}

// --- FETCH ROOM IMAGES ---
$images = mysqli_query($conn,"SELECT * FROM room_images WHERE room_id='$room_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Room Gallery</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<style>body{font-family:'Segoe UI';}</style>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-4xl mx-auto">

<h2 class="text-2xl font-bold mb-4">Room Gallery Images</h2>

<!-- Add Images Form -->
<div class="bg-white p-6 rounded shadow mb-6">
<form method="POST" enctype="multipart/form-data">
<label class="font-bold mb-2 block">Add Images</label>
<input type="file" name="room_images[]" multiple class="mb-3">
<button type="submit" name="add_gallery" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload Images</button>
</form>
</div>

<!-- Existing Images -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
<?php while($img=mysqli_fetch_assoc($images)){ ?>
<div class="bg-white p-2 rounded shadow relative">
<img src="../uploads/<?= $img['image'] ?>" class="w-full h-32 object-cover rounded">
<a href="?room_id=<?= $room_id ?>&delete_img=<?= $img['id'] ?>" onclick="return confirm('Delete this image?')" class="absolute top-1 right-1 bg-red-600 text-white px-2 py-1 text-xs rounded hover:bg-red-700">Delete</a>
</div>
<?php } ?>
</div>

<a href="manage_rooms.php" class="inline-block mt-6 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back to Rooms</a>

</div>

<script>
feather.replace();
</script>
</body>
</html>