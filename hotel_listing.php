<?php
include('config.php');

// Fetch hotels
$sql = "SELECT * FROM hotels ORDER BY city,name";
$result = $conn->query($sql);

// Fetch unique cities
$cityResult = $conn->query("SELECT DISTINCT city FROM hotels ORDER BY city ASC");
$cities = [];
while($c = $cityResult->fetch_assoc()){
 $cities[] = $c['city'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hotel Listings</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
.hotelCard{background:white;border-radius:18px;box-shadow:0 10px 25px rgba(0,0,0,.08);overflow:hidden;transition:.3s;}
.hotelCard:hover{transform:translateY(-5px);}
.hotelImg{height:200px;width:100%;object-fit:cover;}
.price{font-size:22px;font-weight:700;}
</style>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-6">
<h1 class="text-3xl font-bold mb-6">Hotel Listings</h1>

<div class="grid grid-cols-4 gap-6">

<!-- Filter Panel -->
<div class="bg-white p-6 rounded-xl shadow col-span-1">
<h2 class="font-bold mb-3">City</h2>
<select id="city" class="border p-2 w-full">
<option value="">All Cities</option>
<?php foreach($cities as $city): ?>
<option value="<?= $city ?>"><?= $city ?></option>
<?php endforeach; ?>
</select>
</div>

<!-- Hotel Grid -->
<div id="hotelGrid" class="col-span-3 grid md:grid-cols-2 gap-6">

<?php while($hotel = $result->fetch_assoc()): ?>
<?php
// Get first image from fab_images
$imgRes = $conn->query("SELECT image_name FROM fab_images WHERE hotel_id=".$hotel['id']." LIMIT 1");
$img = ($imgRes && $imgRes->num_rows>0) ? $imgRes->fetch_assoc()['image_name'] : 'placeholder.jpg';

// Get amenities as space-separated string
$amenRes = $conn->query("SELECT amenity_name FROM haridwar_fab WHERE hotel_id=".$hotel['id']);
$facilities = [];
while($f = $amenRes->fetch_assoc()) $facilities[] = $f['amenity_name'];
$facilityStr = implode(" ", $facilities);
?>

<div class="hotelCard"
 data-price="<?= $hotel['price'] ?>"
 data-city="<?= $hotel['city'] ?>"
 data-facility="<?= $facilityStr ?>"
 data-rating="<?= $hotel['rating'] ?? 0 ?>">

<a href="hotel_details.php?id=<?= $hotel['id'] ?>">
 <img src="img/<?= $img ?>" class="hotelImg rounded-t-xl" alt="<?= $hotel['name'] ?>">
</a>

<div class="p-4">
<h2 class="font-bold text-lg"><?= $hotel['name'] ?></h2>
<p class="price">₹<?= $hotel['price'] ?></p>
<p>⭐ <?= $hotel['rating'] ?? 'N/A' ?></p>
<a href="hotel_details.php?id=<?= $hotel['id'] ?>" class="mt-2 px-4 py-2 rounded bg-blue-600 text-white inline-block">
View Details
</a>
</div>
</div>

<?php endwhile; ?>

</div>
</div>

<script>
const cityInput = document.getElementById("city");

function filterHotels(){
 const city = cityInput.value;
 document.querySelectorAll(".hotelCard").forEach(card=>{
 card.style.display = (city=="" || card.dataset.city==city) ? "block":"none";
 });
}
cityInput.addEventListener("change",filterHotels);
</script>

</body>
</html>