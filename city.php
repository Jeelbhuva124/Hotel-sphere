<?php
include('config.php');
session_start();

/* ================= GET CITY NAME ================= */

if(isset($_GET['name'])){
 $city = $conn->real_escape_string($_GET['name']);
} else {
 die("City not found");
}

/* ================= FETCH HOTELS OF CITY ================= */

$stmt = $conn->prepare("SELECT * FROM hotels WHERE city=?");
$stmt->bind_param("s",$city);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
<title>Hotels in <?php echo $city; ?></title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body class="bg-gray-100">

<!-- HEADER -->

<div class="bg-blue-700 text-white">
<div class="max-w-6xl mx-auto px-6 py-4 flex justify-between">
<a href="index.php" class="text-2xl font-bold">MyHotel</a>
<a href="index.php">Home</a>
</div>
</div>

<!-- BREADCRUMB -->

<div class="bg-gray-200">
<div class="max-w-6xl mx-auto px-6 py-3 text-sm text-gray-700">
<a href="index.php">Home</a> › 
<a href="hotels.php">Hotels</a> › 
<?php echo $city; ?>
</div>
</div>

<div class="max-w-6xl mx-auto mt-8">

<h1 class="text-3xl font-bold mb-6">
Hotels in <?php echo $city; ?>
</h1>

<?php
if($result->num_rows > 0){
while($hotel = $result->fetch_assoc()){
?>

<!-- HOTEL CARD -->

<div class="bg-white p-6 rounded-xl shadow mb-6 flex justify-between items-center">

<div>
<h2 class="text-xl font-bold">
<?php echo $hotel['name']; ?>
</h2>
<p class="text-gray-600">
₹ <?php echo $hotel['price']; ?> / night
</p>
</div>

<a href="hotel_details.php?id=<?php echo $hotel['id']; ?>"
class="bg-blue-600 text-white px-4 py-2 rounded">
View Details
</a>

</div>

<?php }} else { ?>

<p class="text-gray-600">No hotels found in this city.</p>

<?php } ?>

</div>

</body>
</html>