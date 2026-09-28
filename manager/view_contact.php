<?php
session_start();
include "../config.php";

if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];
$manager_name = $_SESSION['manager_name'] ?? "Manager";

// Get manager's hotel
$hotel_result = mysqli_query($conn, "SELECT id, hotel_name FROM hotel WHERE manager_id='$manager_id' LIMIT 1");
$hotel = mysqli_fetch_assoc($hotel_result);
$hotel_id = $hotel['id'] ?? 0;

// Fetch messages for this hotel
$messages = mysqli_query($conn, "SELECT * FROM contact WHERE hotel_id='$hotel_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($hotel['hotel_name']) ?> - Messages</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body class="bg-gray-100 flex min-h-screen">

<!-- Sidebar -->
<?php include __DIR__ . "/sidebar.php"; ?>

<!-- Main Content -->
<main class="flex-1 p-6 overflow-x-auto">
 <h1 class="text-3xl font-bold text-gray-700 mb-6"><?= htmlspecialchars($hotel['hotel_name']) ?> - Messages</h1>

 <div class="bg-white shadow rounded overflow-x-auto">
 <table class="min-w-full divide-y divide-gray-200">
  <thead class="bg-gray-50">
  <tr>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
   <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
  </tr>
  </thead>
  <tbody class="bg-white divide-y divide-gray-200">
  <?php if($messages && mysqli_num_rows($messages) > 0): ?>
   <?php while($m = mysqli_fetch_assoc($messages)): ?>
   <tr>
   <td class="px-6 py-4"><?= htmlspecialchars($m['name']) ?></td>
   <td class="px-6 py-4"><?= htmlspecialchars($m['email']) ?></td>
   <td class="px-6 py-4"><?= htmlspecialchars($m['message']) ?></td>
   <td class="px-6 py-4"><?= $m['created_at'] ?></td>
   </tr>
   <?php endwhile; ?>
  <?php else: ?>
   <tr>
   <td colspan="4" class="text-center py-4">No messages yet.</td>
   </tr>
  <?php endif; ?>
  </tbody>
 </table>
 </div>
</main>

<script>
feather.replace();
</script>
</body>
</html>