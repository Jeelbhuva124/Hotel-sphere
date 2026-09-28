<?php
session_start(); // Start session
include("config.php"); // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LuxeStay Hotel Booking</title>

<!-- Prevent FOUC (Flash of Unstyled Content) by setting theme instantly before page renders -->
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
</script>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.hero-gradient{
 background: linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),
 url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=2000');
 background-size: cover;
 background-position: center;
}
</style>

 <link rel="stylesheet" href="/HotelManagement/style.css?v=<?php echo time(); ?>">
</head>
<body class="bg-light">

<nav class="bg-white shadow fixed w-full z-50">
 <div class="container mx-auto px-6 py-4 flex justify-between items-center">
 
 <h1 class="text-2xl font-bold text-gray-900">Hotel Sphere</h1>

 <div class="space-x-6 font-semibold flex items-center">

  <a href="index.php" class="hover:text-primary">Home</a>
  <a href="index.php?page=rooms" class="hover:text-primary">Hotels</a>
  <a href="index.php?page=contact" class="hover:text-primary">Contact</a>
  <a href="index.php?page=feedback" class="hover:text-primary">Feedback</a>

  <?php if(isset($_SESSION['user'])): ?>

  <!-- Welcome User -->
  <div class="flex items-center gap-3">
   <span class="text-gray-600 font-medium">
   Welcome, 
   <span class="text-primary font-bold">
    <?php echo $_SESSION['user']; ?>
   </span>
   </span>

   <!-- MY BOOKING BUTTON -->
   <a href="my_booking.php"
   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
   My Booking
   </a>

   <a href="logout.php"
   class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
   Logout
   </a>
  </div>

  <!-- Admin Panel -->
  <?php if($_SESSION['role'] == "admin"): ?>
   <a href="admin/dashboard.php"
   class="bg-purple-600 text-white px-4 py-2 rounded ml-2">
   Admin Panel
   </a>
  <?php endif; ?>

  <?php else: ?>

  <!-- Guest -->
  <a href="login.php"
   class="bg-gray-800 text-white px-6 py-2 rounded-full hover:bg-gray-900 transition font-medium tracking-wide">
   LOGIN
  </a>

  <a href="register.php"
   class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 transition font-medium tracking-wide shadow-md">
   REGISTER
  </a>

  <?php endif; ?>

  <!-- Custom Theme Toggle Switch -->
  <button id="theme-toggle" class="custom-toggle" aria-label="Toggle Theme">
    <span class="toggle-thumb" id="toggle-thumb">
       <i class="fa-solid fa-moon" id="toggle-icon"></i>
    </span>
  </button>

 </div>
 </div>
</nav>

<div class="pt-20">

<?php
$page = isset($_GET['page']) ? $_GET['page'] : "home";

switch($page){
 case "about":
 include("hotels.php");
 break;
 case "rooms":
 include("rooms.php");
 break;
 case "contact":
 include("contact.php");
 break;
 case "feedback":
 include("feedback.php");
 break;
 default:
 include("home.php");
}
?>

</div>

<script>
    // Theme Toggle Logic (Custom Switch)
    const themeToggle = document.getElementById('theme-toggle');
    const toggleIcon = document.getElementById('toggle-icon');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Initial Load
    if (currentTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        if (toggleIcon) {
            toggleIcon.classList.remove('fa-moon');
            toggleIcon.classList.add('fa-sun');
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'dark') {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                if (toggleIcon) {
                    toggleIcon.classList.remove('fa-sun');
                    toggleIcon.classList.add('fa-moon');
                }
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                if (toggleIcon) {
                    toggleIcon.classList.remove('fa-moon');
                    toggleIcon.classList.add('fa-sun');
                }
            }
        });
    }
</script>

</body>
</html>