<?php
include('config.php');
session_start();

/* ================= GET HOTEL ID ================= */

if(isset($_GET['id']) && is_numeric($_GET['id'])){
 $hotel_id = intval($_GET['id']);
} else {
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
$res = $conn->query("SELECT image_name FROM fab_images WHERE hotel_id=$hotel_id");
if($res){
 while($row = $res->fetch_assoc()){
 $images[] = $row['image_name'];
 }
}

/* ================= FETCH AMENITIES ================= */

$amenities = [];
$amenityQuery = $conn->query("SELECT amenity_name FROM haridwar_fab WHERE hotel_id=$hotel_id");
if($amenityQuery){
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
 $name = $conn->real_escape_string($_POST['name']);
 $rating = intval($_POST['rating']);
 $review = $conn->real_escape_string($_POST['review']);

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
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
</script>
<script src="https://cdn.tailwindcss.com"></script>
<?php include("navbar.php"); ?>
 <link rel="stylesheet" href="/HotelManagement/style.css?v=<?php echo time(); ?>">
</head>

<body class="bg-white-100">

<!-- ================= HEADER ================= -->


<!-- ================= BREADCRUMB ================= -->
<!-- ================= BREADCRUMB ================= -->

<div class="bg-white-100 border-b">
<div class="max-w-6xl mx-auto px-6 py-4 text-sm text-gray-600">

<a href="index.php" class="hover:text-blue-600 hover:underline">
Home
</a>

<span class="mx-2">›</span>

<a href="index.php?page=rooms" class="hover:text-blue-600 hover:underline">
Hotels
</a>

<span class="mx-2">›</span>

<!-- <a href="country.php?name=India" class="hover:text-blue-600 hover:underline">
India
</a>
<span class="mx-2">›</span> -->

<!-- 🔥 Ahiya dynamic city link add karyu che -->

<a href=".php?name=<?php echo urlencode($hotel['city']); ?>">
<?php echo $hotel['city']; ?>
</a>

<span class="mx-2">›</span>

<span class="font-medium text-gray-900">
<?php echo $hotel['name']; ?> deals
</span>

</div>
</div>
<!-- ================= TABS ================= -->

<div class="bg-white border-b sticky top-0 z-50">
<div class="max-w-6xl mx-auto px-6">
<div class="flex space-x-10 text-gray-700 font-medium">
<a href="#overview" class="py-4 border-b-2 border-blue-600 text-blue-600">Overview</a>
<a href="#prices" class="py-4 hover:text-blue-600 border-b-2 border-transparent">prices</a>
<a href="#facilities" class="py-4 hover:text-blue-600 border-b-2 border-transparent">Facilities</a>
<a href="#rules" class="py-4 hover:text-blue-600 border-b-2 border-transparent">House rules</a>
<!-- <a href="#reviews" class="py-4 hover:text-blue-600 border-b-2 border-transparent">
Guest reviews (<?php echo $totalReviews; ?>)
</a> -->
</div>
</div>
</div>

<div class="max-w-6xl mx-auto bg-white mt-6 p-8 shadow-xl rounded-2xl">

<!-- ================= TITLE + RATING ================= -->

<div class="flex justify-between items-center">
<div>
<h1 class="text-4xl font-bold text-gray-800"><?php echo $hotel['name']; ?></h1>
<p class="text-gray-500 mt-1"><?php echo $hotel['city']; ?></p>
</div>

<div class="bg-blue-600 text-white px-4 py-3 rounded-xl text-center shadow-lg">
<div class="text-2xl font-bold"><?php echo $avgRating ?: "New"; ?></div>
<div class="text-sm">Guest Rating</div>
</div>
</div>

<!-- ================= OVERVIEW ================= -->

<div id="overview" class="mt-10">

<div class="grid grid-cols-4 gap-4 mt-6">
<?php if(count($images)>0){ ?>
<div class="col-span-2 row-span-2">
<img src="img/<?php echo $images[0]; ?>" 
class="w-full h-full object-cover rounded-2xl shadow-lg hover:scale-105 transition duration-300">
</div>
<?php for($i=1;$i<count($images)&&$i<=4;$i++){ ?>
<div>
<img src="img/<?php echo $images[$i]; ?>" 
class="w-full h-44 object-cover rounded-2xl shadow hover:scale-105 transition duration-300">
</div>
<?php } } ?>
</div>

<div class="mt-8 flex justify-between items-center bg-gray-50 p-6 rounded-xl shadow">
<div>
<p class="text-gray-600">Starting from</p>
<p class="text-3xl font-bold text-success">
₹ <?php echo $hotel['price']; ?>
<span class="text-base text-gray-500">/ night</span>
</p>
</div>

<a href="booking.php?hotel_id=<?= $hotel['id'] ?>"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-lg shadow-lg transition">
Book Now
</a>
</div>

<p class="mt-6 text-gray-700">
<?php echo $hotel['description'] ?? "Comfortable stay with modern amenities and prime location."; ?>
</p>

</div>

<!-- ================= FACILITIES ================= -->

<!-- <div id="facilities" class="mt-16"> -->
 <div class="mt-12">
 <h2 class="text-2xl font-bold mb-6">Facilities & Services</h2>

 <div class="flex flex-wrap gap-4 text-gray-700 text-lg">
 <?php
 // List of facilities with corresponding emoji icons
 $icons = [
 "Outdoor swimming pool" => "🏊 Outdoor swimming pool",
 "Airport shuttle" => "🚌 Airport shuttle",
 "Spa and wellness centre" => "💆 Spa and wellness centre",
 "Room service" => "🛎 Room service",
 "10 restaurants" => "🍽 10 restaurants",
 "Fitness centre" => "🏋 Fitness centre",
 "Facilities for disabled guests" => "♿ Facilities for disabled guests",
 "Tea/coffee maker in all rooms" => "☕ Tea/coffee maker in all rooms",
 "Bar" => "🍹 Bar",
 "Fabulous breakfast" => "🥐 Fabulous breakfast"
 ];

 foreach($icons as $name => $label){
 echo "<span class='bg-gray-100 px-4 py-2 rounded-xl shadow-sm hover:shadow-md transition'>$label</span>";
 }
 ?>
 </div>
</div>

<!-- ================= HOUSE RULES ================= -->

<!-- ================= HOUSE RULES ================= -->
<!-- House Rules Section -->
<div id="rules" class="mt-16 max-w-7xl mx-auto px-6">

 <!-- Section Heading -->
 <h2 class="text-3xl font-bold text-primary mb-6">House Rules</h2>

 <div class="overflow-x-auto">
 <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
  <tbody class="divide-y divide-gray-200">
  
  <!-- Special requests -->
  <tr class="bg-gray-50">
   <td class="px-6 py-4 font-semibold text-gray-700">Special requests</td>
   <td class="px-6 py-4 text-gray-600">
   The Taj Mahal Palace, Mumbai takes special requests – add in the next step!
   </td>
  </tr>

  <!-- Check-in / Check-out -->
  <tr>
   <td class="px-6 py-4 font-semibold text-gray-700">Check-in</td>
   <td class="px-6 py-4 text-gray-600">
   From 14:00<br>
   Guests are required to show a photo ID and credit card upon check-in.<br>
   Please let the property know in advance what time you'll arrive.
   </td>
  </tr>
  <tr class="bg-gray-50">
   <td class="px-6 py-4 font-semibold text-gray-700">Check-out</td>
   <td class="px-6 py-4 text-gray-600">
   Until 12:00
   </td>
  </tr>

  <!-- Cancellation / Prepayment -->
  <tr>
   <td class="px-6 py-4 font-semibold text-gray-700">Cancellation / prepayment</td>
   <td class="px-6 py-4 text-gray-600">
   Cancellation and prepayment policies vary according to accommodation type. Please check what conditions may apply to each option when making your selection.
   </td>
  </tr>

  <!-- Children and beds -->
  <tr class="bg-gray-50">
   <td class="px-6 py-4 font-semibold text-gray-700">Children and beds</td>
   <td class="px-6 py-4 text-gray-600">
   Children of any age are welcome.<br>
   Children 12 years and above will be charged as adults at this property.<br>
   Add the number of children in your group and their ages to see correct prices and occupancy.
   </td>
  </tr>
  <tr>
   <td class="px-6 py-4 font-semibold text-gray-700">Cot and extra bed policies</td>
   <td class="px-6 py-4 text-gray-600">
   <strong>0 - 2 years:</strong> Cot upon request – Free<br>
   <strong>3+ years:</strong> Extra bed upon request – ₹5,000 per person, per night<br>
   Prices for cots and extra beds are not included in the total price and must be paid separately during your stay.<br>
   All cots and extra beds are subject to availability.
   </td>
  </tr>

  <!-- Age restriction -->
  <tr class="bg-gray-50">
   <td class="px-6 py-4 font-semibold text-gray-700">Age restriction</td>
   <td class="px-6 py-4 text-gray-600">
   The minimum age for check-in is 18
   </td>
  </tr>

  <!-- Pets -->
  <tr>
   <td class="px-6 py-4 font-semibold text-gray-700">Pets</td>
   <td class="px-6 py-4 text-gray-600">
   Pets are not allowed
   </td>
  </tr>

  <!-- Groups -->
  <tr class="bg-gray-50">
   <td class="px-6 py-4 font-semibold text-gray-700">Groups</td>
   <td class="px-6 py-4 text-gray-600">
   When booking more than 7 rooms, different policies and additional supplements may apply.
   </td>
  </tr>

  <!-- Accepted payment methods -->
  <tr>
   <td class="px-6 py-4 font-semibold text-gray-700">Accepted payment methods</td>
   <td class="px-6 py-4 text-gray-600">
   Visa, Mastercard, Cash
   </td>
  </tr>

  </tbody>
 </table>
 </div>
</div>

 <!-- Facilities Section -->
<!-- ================= ALL FACILITIES ================= -->
<!-- <div class="mt-12"> -->
 <!-- Facilities Section -->
<div id="facilities" class="mt-16 max-w-7xl mx-auto px-6">

 <!-- Main Heading -->
 <div class="flex items-center mb-8">
 <h2 class="text-3xl font-bold text-primary">All Facilities</h2>
 </div>

 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-16 gap-y-10 text-gray-800">

 <!-- Column 1 -->
 <div class="space-y-8">

  <!-- Bathroom -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Bathroom</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Toilet paper</li>
   <li>Towels</li>
   <li>Bidet</li>
   <li>Slippers</li>
   <li>Private bathroom</li>
   <li>Toilet</li>
   <li>Free toiletries</li>
   <li>Hairdryer</li>
   <li>Shower</li>
  </ul>
  </div>

  <!-- Bedroom -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Bedroom</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Linen</li>
  </ul>
  </div>

  <!-- Outdoors -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Outdoors</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Outdoor furniture</li>
   <li>Sun terrace</li>
   <li>BBQ facilities <span class="text-xs bg-gray-200 px-2 py-0.5 rounded">Additional charge</span></li>
   <li>Terrace</li>
  </ul>
  </div>

  <!-- Kitchen -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Kitchen</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Electric kettle</li>
  </ul>
  </div>

 </div>

 <!-- Column 2 -->
 <div class="space-y-8">

  <!-- Activities -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Activities</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Live sport events (broadcast)</li>
   <li>Live music/performance</li>
   <li>Tour or class about local culture</li>
   <li>Happy hour</li>
   <li>Themed dinner nights</li>
   <li>Walking tours</li>
   <li>Movie nights</li>
   <li>Stand-up comedy</li>
   <li>Temporary art galleries</li>
   <li>Evening entertainment</li>
   <li>Bowling</li>
  </ul>
  </div>

  <!-- Food & Drink -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Food & Drink</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Coffee house on site</li>
   <li>Fruits</li>
   <li>Wine/champagne</li>
   <li>Kids' meals</li>
   <li>Special diet menus</li>
   <li>Snack bar</li>
   <li>Breakfast in the room</li>
   <li>Bar</li>
   <li>Minibar</li>
   <li>Restaurant</li>
  </ul>
  </div>

  <!-- Internet -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Internet</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>WiFi is available in all areas and is free of charge</li>
  </ul>
  </div>

 </div>

 <!-- Column 3 -->
 <div class="space-y-8">

  <!-- Reception services -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Reception services</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Private check-in/check-out</li>
   <li>Concierge service</li>
   <li>ATM/cash machine on site</li>
   <li>Luggage storage</li>
   <li>Tour desk</li>
   <li>Currency exchange</li>
   <li>Express check-in/check-out</li>
   <li>24-hour front desk</li>
  </ul>
  </div>

  <!-- Cleaning services -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Cleaning services</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Daily housekeeping</li>
   <li>Trouser press</li>
   <li>Ironing service</li>
   <li>Dry cleaning</li>
   <li>Laundry</li>
  </ul>
  </div>

  <!-- Business facilities -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Business facilities</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Fax/photocopying</li>
   <li>Business centre</li>
   <li>Meeting/banquet facilities</li>
  </ul>
  </div>

  <!-- Safety & Security -->
  <div>
  <h3 class="font-semibold text-lg mb-3">Safety & security</h3>
  <ul class="space-y-1 text-sm list-inside list-disc">
   <li>Fire extinguishers</li>
   <li>CCTV outside property</li>
   <li>CCTV in common areas</li>
   <li>Smoke alarms</li>
   <li>Security alarm</li>
   <li>Key card access</li>
   <li>24-hour security</li>
   <li>Safety deposit box</li>
  </ul>
  </div>

 </div>

 </div>
</div>

</div>
<!-- ================= FOOTER ================= -->
<footer class="bg-slate-900 text-white mt-16">
 <div class="container mx-auto px-6 py-10 text-center">
 <h2 class="text-xl font-bold text-primary">LuxeStay</h2>
 <p class="text-slate-400 mt-2">Premium Hotel Booking Experience</p>
 <p class="text-xs text-slate-500 mt-4">© 2024 LuxeStay. All rights reserved.</p>
 </div>
</footer>
</body>
</html>