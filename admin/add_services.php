<?php
session_start();
include("../config.php");

if(!isset($_SESSION['admin'])){
 header("Location: login.php");
 exit();
}

// INSERT SERVICE
if(isset($_POST['add'])){
 $name = $_POST['name'];
 $icon = $_POST['icon'];
 $status = $_POST['status'];

 mysqli_query($conn,"INSERT INTO services(name,icon,status)
 VALUES('$name','$icon','$status')");

 header("Location: add_services.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Service</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body class="bg-gray-100">

<!-- Sidebar Include -->
<?php include("sidebar.php"); ?>

<!-- Main Content -->
<div class="ml-64 p-8">

<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow">

<h2 class="text-2xl font-bold mb-6 text-gray-700">
<i class="fa fa-concierge-bell mr-2 text-blue-600"></i>
Add New Service
</h2>

<form method="POST" class="space-y-6">

<!-- Service Name -->
<div>
<label class="block text-gray-600 mb-2">Service Name</label>
<input type="text" name="name" required
class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400">
</div>

<!-- Icon -->
<div>
<label class="block text-gray-600 mb-2">
Icon (FontAwesome Class)
</label>
<input type="text" name="icon" placeholder="fa-solid fa-wifi"
class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400">
<p class="text-sm text-gray-400 mt-1">

</p>
</div>

<!-- Status -->
<div>
<label class="block text-gray-600 mb-2">Status</label>
<select name="status"
class="w-full border p-3 rounded-lg focus:ring-2 focus:ring-blue-400">
<option value="Active">Active</option>
<option value="Inactive">Inactive</option>
</select>
</div>

<!-- Buttons -->
<div class="flex gap-4">
<button type="submit" name="add"
class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
<i class="fa fa-plus mr-1"></i> Add Service
</button>

<a href="manage_services.php"
class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
Cancel
</a>
</div>

</form>

</div>
</div>

</body>
</html>