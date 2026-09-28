<?php include('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LuxeStay | Home</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
.hero-gradient {
background: linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)),
url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2000&auto=format&fit=crop');
background-size: cover;
background-position: center;
}
</style>

 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-50">

<!-- HERO -->
<section class="hero-gradient h-[70vh] flex items-center justify-center text-white text-center">
<div>
<h1 class="text-5xl font-bold mb-3">Explore India's Best Cities</h1>
<p class="text-lg text-gray-200">Top Hotels & Resorts Booking</p>
</div>
</section>


<!-- UNIQUE CITY SECTION -->
<section class="py-20">
<div class="max-w-7xl mx-auto px-6">

<div class="text-center mb-14">
<h2 class="text-4xl font-bold">Popular Destinations</h2>
<p class="text-gray-500 mt-2">Choose your perfect city stay</p>
</div>

<div class="grid md:grid-cols-3 gap-10">

<!-- CITY 1 -->
<div class="relative group cursor-pointer">

<div class="overflow-hidden rounded-3xl shadow-xl">
<img src="https://images.unsplash.com/photo-1566073771259-6a8506099945"
class="h-80 w-full object-cover group-hover:scale-110 transition duration-700">
</div>

<div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent rounded-3xl"></div>

<div class="absolute bottom-6 left-6 text-white">
<h3 class="text-3xl font-semibold">Surat</h3>
<div class="w-12 h-1 bg-white mt-2 group-hover:w-24 transition-all"></div>
</div>

</div>


<!-- CITY 2 -->
<div class="relative group cursor-pointer">

<div class="overflow-hidden rounded-3xl shadow-xl">
<img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa"
class="h-80 w-full object-cover group-hover:scale-110 transition duration-700">
</div>

<div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent rounded-3xl"></div>

<div class="absolute bottom-6 left-6 text-white">
<h3 class="text-3xl font-semibold">Ahmedabad</h3>
<div class="w-12 h-1 bg-white mt-2 group-hover:w-24 transition-all"></div>
</div>

</div>


<!-- CITY 3 -->
<div class="relative group cursor-pointer">

<div class="overflow-hidden rounded-3xl shadow-xl">
<img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b"
class="h-80 w-full object-cover group-hover:scale-110 transition duration-700">
</div>

<div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent rounded-3xl"></div>

<div class="absolute bottom-6 left-6 text-white">
<h3 class="text-3xl font-semibold">Mumbai</h3>
<div class="w-12 h-1 bg-white mt-2 group-hover:w-24 transition-all"></div>
</div>

</div>

</div>
</div>
</section>


<?php include("footer.php"); ?>

</body>
</html>
