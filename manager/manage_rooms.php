<?php
session_start();
include("../config.php");

// Redirect if manager not logged in
if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];
$manager_name = $_SESSION['manager_name'] ?? "Manager";

// --- Get manager's hotel_id ---
$hotel_result = mysqli_query($conn, "SELECT id FROM hotel WHERE manager_id='$manager_id' LIMIT 1");
$hotel_id = 0;
if($hotel_result && mysqli_num_rows($hotel_result) > 0){
 $hotel_row = mysqli_fetch_assoc($hotel_result);
 $hotel_id = $hotel_row['id'];
}

// --- DELETE ROOM ---
if(isset($_GET['delete'])){
 $delete_id = intval($_GET['delete']);
 mysqli_query($conn,"DELETE FROM room WHERE id='$delete_id' AND hotel_id='$hotel_id'");
 echo "<script>alert('Room deleted'); window.location='manage_rooms.php';</script>";
 exit;
}

// --- EDIT ROOM ---
$edit = false;
$editData = [];
if(isset($_GET['edit'])){
 $edit = true;
 $edit_id = intval($_GET['edit']);
 $edit_query = mysqli_query($conn,"SELECT * FROM room WHERE id='$edit_id' AND hotel_id='$hotel_id'");
 $editData = mysqli_fetch_assoc($edit_query);
}

// --- ADD ROOM ---
if(isset($_POST['add_room'])){
 $room_type = mysqli_real_escape_string($conn,$_POST['room_type']);
 $bed_type = mysqli_real_escape_string($conn,$_POST['bed_type']);
 $room_size = mysqli_real_escape_string($conn,$_POST['room_size']);
 $view_type = mysqli_real_escape_string($conn,$_POST['view_type']);
 $available_rooms = intval($_POST['available_rooms']);
 $facilities = isset($_POST['facilities']) ? implode(",",$_POST['facilities']) : "";
 $image = $_FILES['image']['name'];

 if($image){
 move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);
 }

 mysqli_query($conn,"INSERT INTO room (hotel_id,room_type,bed_type,room_size,view_type,available_rooms,image,facilities)
 VALUES ('$hotel_id','$room_type','$bed_type','$room_size','$view_type','$available_rooms','$image','$facilities')");

 echo "<script>alert('Room added successfully'); window.location='manage_rooms.php';</script>";
 exit;
}

// --- UPDATE ROOM ---
if(isset($_POST['update_room'])){
 $id = intval($_POST['id']);
 $room_type = mysqli_real_escape_string($conn,$_POST['room_type']);
 $bed_type = mysqli_real_escape_string($conn,$_POST['bed_type']);
 $room_size = mysqli_real_escape_string($conn,$_POST['room_size']);
 $view_type = mysqli_real_escape_string($conn,$_POST['view_type']);
 $available_rooms = intval($_POST['available_rooms']);
 $facilities = isset($_POST['facilities']) ? implode(",",$_POST['facilities']) : "";

 if(!empty($_FILES['image']['name'])){
 $image = $_FILES['image']['name'];
 move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$image);
 mysqli_query($conn,"UPDATE room SET room_type='$room_type', bed_type='$bed_type', room_size='$room_size', view_type='$view_type', available_rooms='$available_rooms', facilities='$facilities', image='$image' WHERE id='$id' AND hotel_id='$hotel_id'");
 } else {
 mysqli_query($conn,"UPDATE room SET room_type='$room_type', bed_type='$bed_type', room_size='$room_size', view_type='$view_type', available_rooms='$available_rooms', facilities='$facilities' WHERE id='$id' AND hotel_id='$hotel_id'");
 }

 echo "<script>alert('Room updated'); window.location='manage_rooms.php';</script>";
 exit;
}

// --- FETCH ROOMS ---
$rooms = mysqli_query($conn,"SELECT * FROM room WHERE hotel_id='$hotel_id' ORDER BY id DESC");

// --- DASHBOARD STATS ---
$total_rooms = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM room WHERE hotel_id='$hotel_id'"))['total'] ?? 0;
$available_rooms_count = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM room WHERE hotel_id='$hotel_id' AND available_rooms>0"))['total'] ?? 0;
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM booking WHERE hotel_id='$hotel_id'"))['total'] ?? 0;
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(price) as total FROM booking WHERE hotel_id='$hotel_id' AND status='Confirmed'"))['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Rooms</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<style>body{font-family:'Segoe UI';}</style>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="flex min-h-screen bg-gray-100">

<!-- Sidebar -->
<aside class="w-64 bg-black text-white flex-shrink-0 p-6 flex flex-col">
 <h2 class="text-2xl font-bold mb-6">Hotel Manager</h2>
 <p class="mb-4 text-gray-300 text-sm">Welcome, <?= htmlspecialchars($manager_name) ?></p>
 <nav class="flex flex-col gap-2 flex-1">
 <a href="manager_dashboard.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="home"></span> Dashboard</a>
 <a href="manage_hotel.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="briefcase"></span> Manage Hotel</a>
 <a href="manage_rooms.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="layers"></span> Manage Rooms</a>
 <a href="manage_booking.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="file-text"></span> Manage Booking</a>
 <a href="manage_users.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="users"></span> Manage Users</a>
 <a href="view_feedback.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="message-square"></span> Feedback</a>
 <a href="manage_packages.php" class="flex items-center gap-2 p-3 rounded hover:bg-gray-800"><span data-feather="gift"></span> Services & Packages</a>
 <a href="../index.php" class="flex items-center gap-2 p-3 rounded hover:bg-red-700"><span data-feather="log-out"></span> Logout</a>
 </nav>
</aside>

<!-- Main Content -->
<main class="flex-1 p-6 overflow-x-auto">
<div class="max-w-6xl mx-auto">

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
 <div class="bg-white p-4 rounded-xl shadow text-center">
 <h3 class="text-gray-500">Total Rooms</h3>
 <p class="text-2xl font-bold text-blue-600"><?= $total_rooms ?></p>
 </div>
 <div class="bg-white p-4 rounded-xl shadow text-center">
 <h3 class="text-gray-500">Available Rooms</h3>
 <p class="text-2xl font-bold text-success"><?= $available_rooms_count ?></p>
 </div>
 <div class="bg-white p-4 rounded-xl shadow text-center">
 <h3 class="text-gray-500">Total Bookings</h3>
 <p class="text-2xl font-bold text-yellow-600"><?= $total_bookings ?></p>
 </div>
 <div class="bg-white p-4 rounded-xl shadow text-center">
 <h3 class="text-gray-500">Revenue</h3>
 <p class="text-2xl font-bold text-error">₹<?= number_format($total_revenue,2) ?></p>
 </div>
</div>

<!-- Add/Edit Room Form -->
<div class="bg-white p-6 rounded-xl shadow mb-6">
<h2 class="text-2xl font-bold text-blue-700 mb-4"><?= $edit ? "Edit Room" : "Add Room" ?></h2>
<form method="POST" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $edit ? $editData['id'] : '' ?>">
<input type="text" name="room_type" placeholder="Room Type" value="<?= $edit ? $editData['room_type'] : '' ?>" required class="w-full mb-2 p-2 border rounded">
<input type="text" name="bed_type" placeholder="Bed Type" value="<?= $edit ? $editData['bed_type'] : '' ?>" required class="w-full mb-2 p-2 border rounded">
<input type="text" name="room_size" placeholder="Room Size" value="<?= $edit ? $editData['room_size'] : '' ?>" class="w-full mb-2 p-2 border rounded">
<select name="view_type" class="w-full mb-2 p-2 border rounded">
 <option <?= $edit && $editData['view_type']=='City View'?'selected':'' ?>>Airport View</option>
<option <?= $edit && $editData['view_type']=='City View'?'selected':'' ?>>City View</option>
<option <?= $edit && $editData['view_type']=='Pool View'?'selected':'' ?>>Pool View</option>
<option <?= $edit && $editData['view_type']=='Hill View'?'selected':'' ?>>Hill View</option>
<option <?= $edit && $editData['view_type']=='Garden View'?'selected':'' ?>>Garden View</option>
<option <?= $edit && $editData['view_type']=='Sea View'?'selected':'' ?>>Sea View</option>
</select>
<input type="number" name="available_rooms" placeholder="Available Rooms" value="<?= $edit ? $editData['available_rooms'] : '' ?>" class="w-full mb-2 p-2 border rounded">
<input type="file" name="image" class="mb-2">

<h3 class="font-bold mb-2">Facilities</h3>
<div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3 mb-4">
<?php
$all_facilities = ["AC","WiFi","Smart TV","Netflix","Balcony","Seating Area","Mini Bar","Coffee Maker","Bathroom","Bathtub","Room Service","Laundry","Swimming Pool","Gym","Spa","Parking","Safe","Smoking","No Smoking"];
$selected = $edit ? explode(",",$editData['facilities']) : [];
foreach($all_facilities as $facility){
 $isChecked = in_array($facility,$selected) ? "checked" : "";
 echo '<label class="flex flex-col items-start p-3 border rounded-lg bg-blue-50 cursor-pointer hover:bg-blue-100 transition">
  <input type="checkbox" name="facilities[]" value="'.$facility.'" class="mb-1" '.$isChecked.'>
  <span class="text-gray-800">'.$facility.'</span>
  </label>';
}
?>
</div>

<button type="submit" name="<?= $edit ? 'update_room' : 'add_room' ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"><?= $edit ? 'Update Room' : 'Add Room' ?></button>
</form>
</div>

<!-- Rooms List -->
<h2 class="text-xl font-bold mb-4">Your Rooms</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
<?php while($r=mysqli_fetch_assoc($rooms)){ ?>
<div class="bg-white rounded-xl shadow overflow-hidden">
<img src="../uploads/<?= $r['image'] ?>" class="w-full h-48 object-cover">
<div class="p-4">
<h3 class="font-bold text-blue-700"><?= $r['room_type'] ?></h3>
<div class="mt-2 flex flex-wrap gap-1">
<?php
$fac = explode(",",$r['facilities']);
foreach($fac as $f){
 echo "<span class='inline-block bg-blue-100 text-blue-700 px-2 py-1 text-xs rounded mr-1 mb-1'>$f</span>";
}
?>
</div>
<div class="mt-2 flex gap-2">
<a href="?edit=<?= $r['id'] ?>" class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">Edit</a>
<a href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Delete</a>
</div>
</div>
</div>
<?php } ?>
</div>

</div>
</main>

<script>
feather.replace();
</script>
</body>
</html>
