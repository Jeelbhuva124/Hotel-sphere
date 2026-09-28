<?php
include "config.php"; // database connection

$sql = "SELECT * FROM hotels ORDER BY city, name";
$result = $conn->query($sql);

$current_city = "";
?>

<!DOCTYPE html>
<html>
<head>
 <title>All Hotels</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-6">

<?php while($row = $result->fetch_assoc()) { ?>

 <?php 
 // જો city change થાય તો new heading બતાવવું
 if($current_city != $row['city']) { 
 
 if($current_city != "") {
  echo "</div>"; // previous grid close
 }

 $current_city = $row['city'];
 ?>

 <!-- City Heading -->
 <h2 class="text-2xl font-bold mt-10 mb-6">
  <?php echo $current_city; ?>
 </h2>

 <!-- Grid Start -->
 <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

 <?php } ?>

 <!-- Hotel Card -->
 <div class="bg-white rounded-xl shadow-md overflow-hidden">
 
 <img src="uploads/<?php echo $row['image']; ?>" 
  class="h-56 w-full object-cover">

 <div class="p-4">
  <h3 class="text-lg font-semibold">
  <?php echo $row['name']; ?>
  </h3>

  <p class="text-gray-600 mt-1 font-medium">
  ₹<?php echo $row['price']; ?>
  </p>

  <p class="text-yellow-500 mt-1">
  ⭐ <?php echo $row['rating']; ?>
  </p>

  <a href="hotel-details.php?id=<?php echo $row['id']; ?>"
  class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
  More View
  </a>
 </div>
 </div>

<?php } ?>

</div> <!-- Last grid close -->

</div>

</body>
</html>
