<?php
include('config.php');
session_start();

/* ================= GET HOTEL ID ================= */

if(isset($_GET['id']) && is_numeric($_GET['id'])){
 $hotel_id = intval($_GET['id']);
} else {
 // fallback: first hotel
 $getFirst = $conn->query("SELECT id FROM hotels ORDER BY id ASC LIMIT 1");
 if($getFirst && $getFirst->num_rows > 0){
 $first = $getFirst->fetch_assoc();
 $hotel_id = $first['id'];
 } else {
 die("No hotels found");
 }
}

/* ================= FETCH HOTEL ================= */

$stmt = $conn->prepare("SELECT * FROM hotels WHERE id=?");
$stmt->bind_param("i",$hotel_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
 die("Hotel not found");
}

$hotel = $result->fetch_assoc();

/* ================= FETCH IMAGES ================= */

$images = [];
$res = $conn->query("SELECT image_name FROM ashy_images WHERE hotel_id=$hotel_id");

if($res && $res->num_rows > 0){
 while($row = $res->fetch_assoc()){
 $images[] = $row['image_name'];
 }
}

/* ================= FETCH AMENITIES ================= */

$amenities = [];
$amenityQuery = $conn->query("SELECT amenity_name FROM haridwar_fab WHERE hotel_id=$hotel_id");

if($amenityQuery && $amenityQuery->num_rows > 0){
 while($row = $amenityQuery->fetch_assoc()){
 $amenities[] = $row['amenity_name'];
 }
}

/* ================= REVIEW SUMMARY ================= */

$totalReviews = 0;
$avgRating = 0;

$countQuery = $conn->query("
SELECT COUNT(*) as total, AVG(rating) as avg_rating 
FROM haridwar_reviews 
WHERE hotel_id=$hotel_id AND status='approved'
");

if($countQuery){
 $countData = $countQuery->fetch_assoc();
 $totalReviews = $countData['total'] ?? 0;
 $avgRating = $countData['avg_rating'] ? round($countData['avg_rating'],1) : 0;
}

/* ================= HANDLE REVIEW ================= */

if(isset($_POST['submit_review'])){
 $name = $_POST['name'];
 $rating = intval($_POST['rating']);
 $review = $_POST['review'];

 $conn->query("
 INSERT INTO haridwar_reviews (hotel_id,name,rating,review,status)
 VALUES ($hotel_id,'$name',$rating,'$review','pending')
 ");
}
?>

<!DOCTYPE html>
<html class="scroll-smooth">
<head>
<title><?php echo $hotel['name']; ?></title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>

<body class="bg-gray-100">

<div class="max-w-6xl mx-auto p-6 bg-white mt-6 shadow-lg rounded">

<h1 class="text-3xl font-bold"><?php echo $hotel['name']; ?></h1>
<p class="text-gray-600"><?php echo $hotel['city']; ?></p>

<!-- ================= GALLERY ================= -->

<h2 class="text-2xl font-bold mt-6 mb-4">Gallery</h2>

<div class="grid grid-cols-4 gap-3">

<?php if(count($images) > 0){ ?>

 <div class="col-span-2 row-span-2">
 <img src="img/<?php echo $images[0]; ?>"
 class="w-full h-full object-cover rounded-xl shadow-lg">
 </div>

 <?php for($i=1; $i<count($images) && $i<=4; $i++){ ?>
 <div>
  <img src="img/<?php echo $images[$i]; ?>"
  class="w-full h-40 object-cover rounded-xl shadow-md">
 </div>
 <?php } ?>

<?php } else { ?>

 <div class="col-span-4 bg-gray-200 h-64 flex items-center justify-center rounded-xl">
 <p class="text-gray-600 text-lg">Images Not Found</p>
 </div>

<?php } ?>

</div>

<!-- ================= PRICE ================= -->

<div class="mt-6">
<p class="text-xl font-semibold text-success">
₹ <?php echo $hotel['price']; ?> 
</p>
</div>
<br>

<h2 class="text-2xl font-bold mb-4"> About this property</h2>
 <p class="font-semibold"><b>Comfortable Accommodations:</b> Jaswinder Bhawan - By The Ganges in Haridwār offers family rooms with air-conditioning, private bathrooms, and river or inner courtyard views. Each room includes a work desk, seating area, and free toiletries.</p><br>
 <p class="font-semibold"><b>Essential Facilities:</b> Guests enjoy free WiFi, a 24-hour front desk, paid shuttle service, and room service. Additional amenities include streaming services, a tea and coffee maker, and a sofa bed.
</p><br>
<p class="font-semibold"><b>Prime Location:</b> Located 4.6 km from Mansa Devi Temple and 3.4 km from Har Ki Pauri, the hotel is 36 km from Dehradun Airport. Nearby attractions include Haridwar Railway Station (3.8 km) and Rishikesh Railway Station (27 km).</p><br>
<p class="font-semibold"><b>Guest Satisfaction:</b>Highly rated for its convenient location and room cleanliness, the hotel ensures a pleasant stay for all visitors.
</p><br>
<!-- ================= TOP AMENITIES ================= -->

<h2 class="text-2xl font-bold mb-4">Amenities</h2>

<?php
$icons = [
 "wifi" => "📶 Free WiFi",
 "pool" => "🏊 Swimming Pool",
 "parking" => "🅿 Parking",
 "restaurant" => "🍽 Restaurant",
 "frontdesk" => "🛎 24h Front Desk",
 "ac" => "❄ A/C",
 "gym" => "🏋 Gym",
 "spa" => "💆 Spa"
];
?>

<div class="flex flex-wrap gap-6 text-gray-700">

<?php
$count = 0;
foreach($amenities as $a){
 echo "<span>".($icons[$a] ?? $a)."</span>";
 $count++;
 if($count == 4) break;
}
?>

</div>

<a href="#allAmenities"
class="text-blue-600 font-semibold mt-4 inline-block">
Show more amenities →
</a>

<!-- ================= ALL AMENITIES ================= -->

<div id="allAmenities" class="mt-16">

<h2 class="text-2xl font-bold mb-4">All Amenities</h2>

<div class="grid grid-cols-3 gap-6 text-gray-700">

<?php
foreach($amenities as $a){
 echo "<div>".($icons[$a] ?? $a)."</div>";
}
?>

</div>
</div>

<!-- ================= REVIEW SUMMARY ================= -->

<div class="mt-12 p-4 bg-gray-50 rounded">
<h2 class="text-xl font-bold">
⭐ <?php echo $avgRating; ?>
(<?php echo $totalReviews; ?> Reviews)
</h2>
</div>

<!-- ================= LATEST 5 REVIEWS ================= -->

<h2 class="text-2xl font-bold mt-8 mb-4">Guest Reviews</h2>

<?php
$reviewQuery = $conn->query("
SELECT name, rating, review 
FROM haridwar_reviews 
WHERE hotel_id=$hotel_id AND status='approved'
ORDER BY id DESC 
LIMIT 5
");

if($reviewQuery && $reviewQuery->num_rows > 0){
 while($r = $reviewQuery->fetch_assoc()){
?>
 <div class="border-b py-4">
  <h4 class="font-semibold"><?php echo $r['name']; ?></h4>
  <p class="text-yellow-500">⭐ <?php echo $r['rating']; ?>/5</p>
  <p class="text-gray-600"><?php echo $r['review']; ?></p>
 </div>
<?php
 }
} else {
 echo "<p>No reviews yet.</p>";
}
?>

<!-- ================= REVIEW FORM ================= -->

<h2 class="text-2xl font-bold mt-10 mb-4">Write a Review</h2>

<form method="POST" class="space-y-4 bg-gray-50 p-6 rounded shadow">

<input type="text" name="name" placeholder="Your Name"
class="w-full border p-2 rounded" required>

<select name="rating" class="w-full border p-2 rounded" required>
<option value="">Select Rating</option>
<option value="5">5 - Excellent</option>
<option value="4">4 - Very Good</option>
<option value="3">3 - Good</option>
<option value="2">2 - Average</option>
<option value="1">1 - Poor</option>
</select>

<textarea name="review" placeholder="Write your review"
class="w-full border p-2 rounded" required></textarea>

<button type="submit" name="submit_review"
class="bg-blue-600 text-white px-4 py-2 rounded">
Submit Review
</button>

</form>

</div>
</body>
</html>
