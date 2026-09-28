<?php
session_start();
include("../config.php");

// Admin check
if(!isset($_SESSION['role']) || strtolower(trim($_SESSION['role']))!="admin"){
 header("Location: ../login.php");
 exit();
}

// ---------- DASHBOARD STATS ----------
$hotels = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM hotel"))['total'] ?? 0;
$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'] ?? 0;


// ================= PAID BOOKINGS =================
$confirmed = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM booking WHERE LOWER(status)='paid'"
))['total'] ?? 0;


// ================= PENDING =================
$pending = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM booking WHERE LOWER(status)='pending'"
))['total'] ?? 0;


// ================= CANCELLED =================
$cancelled = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM booking WHERE LOWER(status)='cancelled'"
))['total'] ?? 0;


$totalBookings = $confirmed + $pending + $cancelled;


// ---------- CITY WISE BOOKINGS ----------
$cityQuery = mysqli_query($conn,"
SELECT cities.city_name, COUNT(booking.id) as total
FROM booking
JOIN hotel ON booking.hotel_id = hotel.id
JOIN cities ON hotel.city_id = cities.id
GROUP BY hotel.city_id
");

$cities = [];
$cityTotals = [];

if($cityQuery){
 while($row = mysqli_fetch_assoc($cityQuery)){
 $cities[] = $row['city_name'];
 $cityTotals[] = $row['total'];
 }
}

$current = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.sidebar a{transition:0.3s;}
.active{background:#0d6efd;color:white !important;}

.chart-container {
 width: 100%;
 max-width: 600px;
 height: 400px;
 margin: 0 auto;
}

/* only city chart small */
.city-chart{
 width:100%;
 max-width:400px;
 height:400px;
 margin:20px auto;
}
</style>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100">

<!-- SIDEBAR -->
<div class="fixed h-screen w-64 bg-gray-900 text-gray-200 p-6 sidebar">
<h2 class="text-2xl font-bold mb-6 text-white">
<i class="fa fa-hotel"></i> Admin Panel
</h2>

<nav class="space-y-2">
<a href="dashboard.php" class="flex items-center px-4 py-3 rounded-lg <?= $current=='dashboard.php'?'bg-blue-600 text-white':'hover:bg-gray-700' ?>">
<i class="fa fa-chart-line mr-3"></i> Dashboard</a>
<a href="manage_managers.php" class="flex items-center px-4 py-3 rounded-lg <?= $current=='manage_managers.php'?'bg-blue-600 text-white':'hover:bg-gray-700' ?>">
<i class="fa fa-user-tie mr-3"></i> Manage Managers</a>
<a href="view_hotels.php" class="flex items-center px-4 py-3 rounded-lg <?= $current=='view_hotels.php'?'bg-blue-600 text-white':'hover:bg-gray-700' ?>">
<i class="fa fa-hotel mr-3"></i> View Hotels</a>
<a href="view_rooms.php" class="flex items-center px-4 py-3 rounded-lg <?= $current=='view_rooms.php'?'bg-blue-600 text-white':'hover:bg-gray-700' ?>">
<i class="fa fa-bed mr-3"></i> View Rooms</a>
<a href="manage_bookings.php" class="flex items-center px-4 py-3 rounded-lg <?= $current=='view_bookings.php'?'bg-blue-600 text-white':'hover:bg-gray-700' ?>">
<i class="fa fa-calendar mr-3"></i> View Bookings</a>
<a href="manage_users.php" class="flex items-center px-4 py-3 rounded-lg <?= $current=='manage_users.php'?'bg-blue-600 text-white':'hover:bg-gray-700' ?>">
<i class="fa fa-users mr-3"></i> View Users</a>
<a href="view_Contact.php" class="flex items-center px-4 py-3 rounded-lg"><i class="fa fa-star mr-3"></i> View Contact</a>
<a href="admin_feedback.php" class="flex items-center px-4 py-3 rounded-lg"><i class="fa fa-star mr-3"></i> View Feedback</a>
<a href="logout.php" class="flex items-center px-4 py-3 rounded-lg hover:bg-red-600 mt-6"><i class="fa fa-sign-out-alt mr-3"></i> Logout</a>
</nav>
</div>

<!-- MAIN CONTENT -->
<div class="ml-64 p-8">
<h1 class="text-3xl font-bold mb-6">Dashboard Overview</h1>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6">

<div class="bg-blue-600 text-white p-6 rounded-xl shadow flex justify-between items-center">
<div>
<h2 class="font-semibold">Total Hotels</h2>
<p class="text-2xl font-bold"><?= $hotels ?></p>
</div>
<i class="fa fa-hotel text-5xl opacity-20"></i>
</div>

<div class="bg-green-600 text-white p-6 rounded-xl shadow flex justify-between items-center">
<div>
<h2 class="font-semibold">Paid Bookings</h2>
<p class="text-2xl font-bold"><?= $confirmed ?></p>
</div>
<i class="fa fa-check-circle text-5xl opacity-20"></i>
</div>

<div class="bg-yellow-400 text-white p-6 rounded-xl shadow flex justify-between items-center">
<div>
<h2 class="font-semibold">Pending Bookings</h2>
<p class="text-2xl font-bold"><?= $pending ?></p>
</div>
<i class="fa fa-hourglass-half text-5xl opacity-20"></i>
</div>

<div class="bg-red-500 text-white p-6 rounded-xl shadow flex justify-between items-center">
<div>
<h2 class="font-semibold">Cancelled Bookings</h2>
<p class="text-2xl font-bold"><?= $cancelled ?></p>
</div>
<i class="fa fa-times-circle text-5xl opacity-20"></i>
</div>

<div class="bg-primary text-white p-6 rounded-xl shadow flex justify-between items-center">
<div>
<h2 class="font-semibold">Total Bookings</h2>
<p class="text-2xl font-bold"><?= $totalBookings ?></p>
</div>
<i class="fa fa-calendar-check text-5xl opacity-20"></i>
</div>

</div>

<!-- BOOKING STATUS CHART -->
<div class="mt-10 bg-white p-6 rounded-xl shadow chart-container">
<h2 class="text-xl font-bold mb-4">Booking Status Overview</h2>
<canvas id="bookingChart"></canvas>
</div>

<!-- CITY WISE CHART (SMALL) -->
<div class="mt-7 bg-white p-3 rounded-xl shadow city-chart">
<h2 class="text-xl font-bold mb-2">City Wise Bookings</h2>
<canvas id="cityChart"></canvas>
</div>

</div>

<script>
// Booking Status Chart
new Chart(document.getElementById('bookingChart'), {
type: 'bar',
data: {
labels: ['Paid','Pending','Cancelled'],
datasets: [{
data: [<?= $confirmed ?>, <?= $pending ?>, <?= $cancelled ?>],
backgroundColor:['#22c55e','#eab308','#ef4444']
}]
},
options: {
plugins: {
legend: { display:false },
title: { display: true, text: 'Booking Status' }
},
scales: { y: { beginAtZero:true } }
}
});

// CITY DOUGHNUT CHART
new Chart(document.getElementById('cityChart'), {
type: 'doughnut',
data: {
labels: <?= json_encode($cities) ?>,
datasets: [{
data: <?= json_encode($cityTotals) ?>,
backgroundColor:[
'#3b82f6',
'#22c55e',
'#eab308',
'#ef4444',
'#8b5cf6',
'#06b6d4',
'#f97316'
]
}]
},
options:{
plugins:{
legend:{position:'right'},
title:{display:true,text:'City Wise Bookings'}
}
}
});
</script>

</body>
</html>