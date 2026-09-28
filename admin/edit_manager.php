<?php
session_start();
include("../config.php");

if(!isset($_SESSION['role']) || strtolower($_SESSION['role'])!="admin"){
 header("Location: ../login.php");
 exit();
}

if(!isset($_GET['id'])){
 header("Location: manage_managers.php");
 exit();
}

$id = intval($_GET['id']);

// Fetch manager
$manager = $conn->query("SELECT * FROM managers WHERE id='$id'")->fetch_assoc();

// Fetch states and cities
$states = $conn->query("SELECT * FROM states ORDER BY state_name ASC");
$cities = $conn->query("SELECT * FROM cities ORDER BY city_name ASC");

// Update manager
if(isset($_POST['update_manager'])){
 $hotel_name = mysqli_real_escape_string($conn,$_POST['hotel_name']);
 $email = mysqli_real_escape_string($conn,$_POST['email']);
 $phone = mysqli_real_escape_string($conn,$_POST['phone']);
 $state_id = intval($_POST['state_id']);
 $city_id = intval($_POST['city_id']);
 $pincode = mysqli_real_escape_string($conn,$_POST['pincode']);

 $conn->query("UPDATE managers SET 
 hotel_name='$hotel_name',
 email='$email',
 phone='$phone',
 state_id='$state_id',
 city_id='$city_id',
 pincode='$pincode'
 WHERE id='$id'");

 header("Location: manage_managers.php");
 exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Manager</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 min-h-screen">
<div class="max-w-3xl mx-auto p-6">
<h1 class="text-3xl font-bold mb-6">Edit Manager</h1>

<form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-6 rounded-xl shadow">
<input type="text" name="hotel_name" placeholder="Hotel Name" value="<?= htmlspecialchars($manager['hotel_name']) ?>" required class="border px-3 py-2 rounded">
<input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($manager['email']) ?>" required class="border px-3 py-2 rounded">
<input type="text" name="phone" placeholder="Phone" value="<?= htmlspecialchars($manager['phone']) ?>" class="border px-3 py-2 rounded">

<select name="state_id" required class="border px-3 py-2 rounded">
<option value="">--Select State--</option>
<?php while($s = $states->fetch_assoc()): ?>
<option value="<?= $s['id'] ?>" <?= $s['id']==$manager['state_id']?'selected':'' ?>><?= $s['state_name'] ?></option>
<?php endwhile; ?>
</select>

<select name="city_id" required class="border px-3 py-2 rounded">
<option value="">--Select City--</option>
<?php while($c = $cities->fetch_assoc()): ?>
<option value="<?= $c['id'] ?>" <?= $c['id']==$manager['city_id']?'selected':'' ?>><?= $c['city_name'] ?></option>
<?php endwhile; ?>
</select>

<input type="text" name="pincode" placeholder="Pincode" value="<?= htmlspecialchars($manager['pincode']) ?>" class="border px-3 py-2 rounded">

<button type="submit" name="update_manager" class="bg-blue-600 text-white px-4 py-2 rounded col-span-2">Update Manager</button>
</form>
</div>
</body>
</html>