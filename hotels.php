<?php
include("config.php"); // DB connection

// Fetch all active hotels
$hotels = mysqli_query($conn,"SELECT * FROM hotels WHERE status='Active' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
 <title>Hotels</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-3xl font-bold mb-6">Our Hotels</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<?php while($h=mysqli_fetch_assoc($hotels)) { ?>
 <div class="bg-white rounded shadow overflow-hidden">
 <img src="uploads/<?= htmlspecialchars($h['image'] ?? 'default.jpg') ?>" class="w-full h-48 object-cover">
 <div class="p-4">
  <h2 class="text-xl font-bold"><?= htmlspecialchars($h['hotel_name']) ?></h2>
  <p class="text-gray-600"><?= htmlspecialchars($h['hotel_address']) ?></p>
  <p class="text-yellow-500 font-semibold">Rating: <?= number_format($h['rating'],1) ?>/5</p>
  <a href="user_rooms.php?hotel_id=<?= $h['id'] ?>" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded">View Rooms</a>
 </div>
 </div>
<?php } ?>
</div>

</body>
</html>