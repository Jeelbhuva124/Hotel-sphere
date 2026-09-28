<?php
include '../config.php';

$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM locations WHERE id=$id");
$row = $res->fetch_assoc();

if (!$row) {
 die("Location not found");
}

if (isset($_POST['submit'])) {
 $city = $_POST['city_name'];
 $total = intval($_POST['total_hotels']);
 $conn->query("UPDATE locations SET city_name='$city', total_hotels=$total WHERE id=$id");
 header("Location: locations.php");
 exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Location</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
 <h2 class="text-xl font-bold mb-4">Edit Location</h2>
 <form method="POST">
 <label class="block mb-2">City Name</label>
 <input type="text" name="city_name" value="<?= htmlspecialchars($row['city_name']) ?>" required class="w-full border p-2 mb-4 rounded">
 
 <label class="block mb-2">Total Hotels</label>
 <input type="number" name="total_hotels" value="<?= $row['total_hotels'] ?>" required class="w-full border p-2 mb-4 rounded">

 <button type="submit" name="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
 <a href="locations.php" class="ml-2 text-gray-700">Cancel</a>
 </form>
</div>

</body>
</html>
