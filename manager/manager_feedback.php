<?php
session_start();
include("../config.php");

if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];

// Fetch feedback for hotels managed by this manager
$sql = "
SELECT f.*, h.hotel_name
FROM feedback f
JOIN hotel h ON f.hotel_id = h.id
WHERE h.manager_id='$manager_id'
ORDER BY f.created_at DESC
";
$feedbacks = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hotel Feedback</title>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 min-h-screen flex font-sans">

<!-- Sidebar -->
<div class="w-64 bg-gray-800 text-white flex-shrink-0">
 <?php include("sidebar.php"); ?>
</div>

<!-- Main Content -->
<div class="flex-1 p-6">
 <h1 class="text-3xl font-bold mb-6 text-gray-800">Feedback for Your Hotels</h1>

 <div class="bg-white shadow-lg rounded-lg overflow-x-auto">
 <table class="w-full text-sm text-gray-700 border-collapse">
  <thead class="bg-gray-200 uppercase text-xs text-gray-700">
  <tr>
   <th class="py-3 px-3 text-center">Hotel</th>
   <th class="py-3 px-3 text-center">Name</th>
   <th class="py-3 px-3 text-center">Email</th>
   <th class="py-3 px-3 text-center">Message</th>
   <th class="py-3 px-3 text-center">Rating</th>
   <th class="py-3 px-3 text-center">Date</th>
  </tr>
  </thead>
  <tbody>
  <?php while($row = mysqli_fetch_assoc($feedbacks)): ?>
  <tr class="border-b hover:bg-gray-50 transition">
   <td class="py-2 px-3 text-center font-medium"><?= htmlspecialchars($row['hotel_name']) ?></td>
   <td class="py-2 px-3 text-center"><?= htmlspecialchars($row['name']) ?></td>
   <td class="py-2 px-3 text-center"><?= htmlspecialchars($row['email']) ?></td>
   <td class="py-2 px-3 text-gray-700"><?= htmlspecialchars($row['message']) ?></td>
   <td class="py-2 px-3 text-center">
   <?php for($i=1;$i<=5;$i++): ?>
    <?php if($i <= $row['rating']): ?>
    <span class="text-yellow-500 text-lg">&#9733;</span>
    <?php else: ?>
    <span class="text-gray-300 text-lg">&#9733;</span>
    <?php endif; ?>
   <?php endfor; ?>
   </td>
   <td class="py-2 px-3 text-center text-gray-600"><?= date("d-m-Y", strtotime($row['created_at'])) ?></td>
  </tr>
  <?php endwhile; ?>
  <?php if(mysqli_num_rows($feedbacks) == 0): ?>
  <tr>
   <td colspan="6" class="py-4 text-center text-gray-500">No feedback found.</td>
  </tr>
  <?php endif; ?>
  </tbody>
 </table>
 </div>
</div>

<script>
 // Activate feather icons if needed
 feather.replace()
</script>

</body>
</html>