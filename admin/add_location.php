<?php
include '../config.php';

if(isset($_POST['submit'])){
 $city = mysqli_real_escape_string($conn, $_POST['city_name']);
 $conn->query("INSERT INTO locations (name) VALUES ('$city')");
 header("Location: manage_location.php");
 exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Location</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
 <h2 class="text-xl font-bold mb-4">Add Location</h2>
 <form method="POST">
 <label class="block mb-2">City Name</label>
 <input type="text" name="city_name" required class="w-full border p-2 mb-4 rounded">
 
 <button type="submit" name="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Location</button>
 <a href="manage_location.php" class="ml-2 text-gray-700">Cancel</a>
 </form>
</div>

</body>
</html>