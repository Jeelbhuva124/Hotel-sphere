<?php
session_start();
include("../config.php");

if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];

$query = "
SELECT m.*, s.state_name, c.city_name
FROM managers m
LEFT JOIN states s ON m.state_id = s.id
LEFT JOIN cities c ON m.city_id = c.id
WHERE m.id='$manager_id' LIMIT 1
";

$result = mysqli_query($conn,$query);
$manager = mysqli_fetch_assoc($result);

if(!$manager){
 echo "<div class='p-6 text-error font-semibold'>No data found for your account.</div>";
 exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manager Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 flex min-h-screen font-sans">

<!-- Sidebar -->
<div class="w-64 bg-gray-800 text-white flex-shrink-0">
 <?php include("sidebar.php"); ?>
</div>

<!-- Main Content -->
<div class="flex-1 p-6">

 <h1 class="text-3xl font-bold mb-6 text-primary">My Profile</h1>

 <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-xl p-8">

 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <div class="flex flex-col">
  <span class="text-gray-500 font-medium">Hotel Name</span>
  <span class="text-gray-800 font-semibold"><?= htmlspecialchars($manager['hotel_name']) ?></span>
  </div>
  <div class="flex flex-col">
  <span class="text-gray-500 font-medium">Email</span>
  <span class="text-gray-800 font-semibold"><?= htmlspecialchars($manager['email']) ?></span>
  </div>
  <div class="flex flex-col">
  <span class="text-gray-500 font-medium">Phone</span>
  <span class="text-gray-800 font-semibold"><?= htmlspecialchars($manager['phone']) ?></span>
  </div>
  <div class="flex flex-col">
  <span class="text-gray-500 font-medium">State</span>
  <span class="text-gray-800 font-semibold"><?= htmlspecialchars($manager['state_name'] ?? '-') ?></span>
  </div>
  <div class="flex flex-col">
  <span class="text-gray-500 font-medium">City</span>
  <span class="text-gray-800 font-semibold"><?= htmlspecialchars($manager['city_name'] ?? '-') ?></span>
  </div>
  <div class="flex flex-col">
  <span class="text-gray-500 font-medium">Pincode</span>
  <span class="text-gray-800 font-semibold"><?= htmlspecialchars($manager['pincode']) ?></span>
  </div>
  <div class="flex flex-col md:col-span-2">
  <span class="text-gray-500 font-medium">Created At</span>
  <span class="text-gray-800 font-semibold"><?= $manager['created_at'] ?></span>
  </div>
 </div>

 <a href="edit_manager_profile.php" 
  class="mt-8 inline-block w-full text-center bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary transition-all">
  Edit Profile
 </a>

 </div>

</div>

<script>
feather.replace();
</script>

</body>
</html>