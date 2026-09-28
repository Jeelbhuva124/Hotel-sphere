<?php
session_start();
include("../config.php");

/* ✅ SESSION CHECK */
if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];

/* ✅ HOTEL FETCH */
$hotel_q = mysqli_query($conn,"SELECT id FROM hotel WHERE manager_id='$manager_id'");
if(mysqli_num_rows($hotel_q) == 0){
 die("No hotel found! Please add hotel first.");
}
$hotel = mysqli_fetch_assoc($hotel_q);
$hotel_id = $hotel['id'];

/* ✅ ROOMS */
$rooms = mysqli_query($conn,"SELECT * FROM room WHERE hotel_id='$hotel_id'");

/* DELETE */
if(isset($_GET['delete'])){
 $id = $_GET['delete'];
 mysqli_query($conn,"DELETE FROM packages WHERE id='$id'");
 header("Location: manage_packages.php");
 exit();
}

/* EDIT FETCH */
$edit = false;
if(isset($_GET['edit'])){
 $edit = true;
 $edit_id = $_GET['edit'];
 $edit_q = mysqli_query($conn,"SELECT * FROM packages WHERE id='$edit_id'");
 $editData = mysqli_fetch_assoc($edit_q);
}

/* ADD */
if(isset($_POST['add_package'])){
 mysqli_query($conn,"INSERT INTO packages (room_id,package_name,price,description)
 VALUES ('$_POST[room_id]','$_POST[package_name]','$_POST[price]','$_POST[description]')");
 echo "<script>alert('Package Added');window.location='manage_packages.php';</script>";
}

/* UPDATE */
if(isset($_POST['update_package'])){
 $id = $_POST['id'];
 mysqli_query($conn,"UPDATE packages SET
 room_id='$_POST[room_id]',
 package_name='$_POST[package_name]',
 price='$_POST[price]',
 description='$_POST[description]'
 WHERE id='$id'");
 echo "<script>alert('Updated');window.location='manage_packages.php';</script>";
}

/* SHOW */
$packages = mysqli_query($conn,"
SELECT p.*, r.room_type 
FROM packages p 
JOIN room r ON p.room_id=r.id 
WHERE r.hotel_id='$hotel_id'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Packages</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 min-h-screen flex font-sans">

<!-- Sidebar -->
<div class="w-64 bg-gray-800 text-white flex-shrink-0">
 <?php include("sidebar.php"); ?>
</div>

<!-- Main content -->
<div class="flex-1 p-6">

 <!-- Add / Edit Package Form -->
 <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow mb-8">
 <h2 class="text-2xl font-bold mb-6 text-gray-800"><?php echo $edit ? "Edit Package" : "Add Package"; ?></h2>
 <form method="POST" class="space-y-4">
  <input type="hidden" name="id" value="<?php echo $edit ? $editData['id'] : ''; ?>">

  <select name="room_id" required class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500">
  <option value="">Select Room</option>
  <?php 
  mysqli_data_seek($rooms,0); // reset pointer
  while($r=mysqli_fetch_assoc($rooms)){ ?>
   <option value="<?php echo $r['id']; ?>" <?php if($edit && $editData['room_id']==$r['id']) echo "selected"; ?>>
   <?php echo $r['room_type']; ?>
   </option>
  <?php } ?>
  </select>

  <input type="text" name="package_name" placeholder="Package Name" value="<?php echo $edit ? $editData['package_name'] : ''; ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500" required>

  <input type="number" name="price" placeholder="Price" value="<?php echo $edit ? $editData['price'] : ''; ?>" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500" required>

  <textarea name="description" rows="4" placeholder="Description" class="w-full border border-gray-300 rounded p-2 focus:ring-2 focus:ring-blue-500"><?php echo $edit ? $editData['description'] : ''; ?></textarea>

  <button type="submit" name="<?php echo $edit ? 'update_package' : 'add_package'; ?>" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
  <?php echo $edit ? 'Update Package' : 'Add Package'; ?>
  </button>
 </form>
 </div>

 <!-- Packages Table -->
 <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow overflow-x-auto">
 <h2 class="text-2xl font-bold mb-4 text-gray-800">All Packages</h2>
 <table class="min-w-full border-collapse text-center">
  <thead class="bg-gray-200 text-gray-700 uppercase text-sm">
  <tr>
   <th class="px-4 py-2 border">Room</th>
   <th class="px-4 py-2 border">Package</th>
   <th class="px-4 py-2 border">Price</th>
   <th class="px-4 py-2 border">Description</th>
   <th class="px-4 py-2 border">Action</th>
  </tr>
  </thead>
  <tbody>
  <?php if(mysqli_num_rows($packages) == 0): ?>
   <tr>
   <td colspan="5" class="py-4 text-gray-500">No packages found.</td>
   </tr>
  <?php else: ?>
  <?php while($p=mysqli_fetch_assoc($packages)){ ?>
   <tr class="border-b hover:bg-gray-50 transition">
   <td class="px-4 py-2 border font-medium"><?= $p['room_type']; ?></td>
   <td class="px-4 py-2 border"><?= $p['package_name']; ?></td>
   <td class="px-4 py-2 border font-semibold text-success">₹ <?= $p['price']; ?></td>
   <td class="px-4 py-2 border text-gray-700"><?= nl2br($p['description']); ?></td>
   <td class="px-4 py-2 border space-x-2">
    <a href="?edit=<?= $p['id']; ?>" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition">Edit</a>
    <a href="?delete=<?= $p['id']; ?>" onclick="return confirm('Delete this package?')" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">Delete</a>
   </td>
   </tr>
  <?php } ?>
  <?php endif; ?>
  </tbody>
 </table>
 </div>

</div>

<script>
feather.replace();
</script>
</body>
</html>