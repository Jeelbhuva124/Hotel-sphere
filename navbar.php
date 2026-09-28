<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Luxestay Navbar</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

<!-- Navbar -->
<header class="bg-gray-100 shadow-sm">
<div class="w-full px-8 md:px-12 py-3 flex items-center justify-between relative">

<!-- Left: Logo & Welcome -->
  <div class="flex flex-col">
   <h1 class="text-3xl font-serif font-bold tracking-widest" style="color: #60a5fa; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">Hotel Sphere</h1>
   <?php if(isset($_SESSION['user'])): ?>
    <span class="text-xs text-gray-500 font-medium mt-1">
     Welcome, <span class="text-primary font-bold"><?php echo $_SESSION['user']; ?></span>
    </span>
   <?php endif; ?>
  </div>

  <!-- Center: Links -->
  <div class="hidden md:flex space-x-8 font-semibold items-center text-lg absolute left-1/2 transform -translate-x-1/2">
   <a href="index.php" class="nav-link transition">Home</a>
   <a href="index.php?page=rooms" class="nav-link transition">Hotels</a>
   <a href="contact.php" class="nav-link transition">Contact</a>
   <a href="feedback.php" class="nav-link transition">Feedback</a>
  </div>

  <!-- Right: Buttons & Theme Toggle -->
  <div class="flex items-center space-x-4">
   <?php if(isset($_SESSION['user'])): ?>
    <a href="my_booking.php" class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition font-medium text-sm">My Booking</a>
    <a href="logout.php" class="bg-red-500 text-white px-5 py-2 rounded-full hover:bg-red-600 transition font-medium text-sm">Logout</a>
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin"): ?>
     <a href="admin/dashboard.php" class="bg-purple-600 text-white px-5 py-2 rounded-full ml-2 transition font-medium text-sm">Admin Panel</a>
    <?php endif; ?>
   <?php else: ?>
    <a href="login.php" class="bg-gray-800 text-white px-5 py-2 rounded-full hover:bg-gray-900 transition font-medium text-sm">LOGIN</a>
    <a href="register.php" class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition font-medium text-sm shadow">REGISTER</a>
   <?php endif; ?>

   <!-- Theme Toggle Switch -->
   <button id="theme-toggle" class="custom-toggle" aria-label="Toggle Theme">
     <span class="toggle-thumb" id="toggle-thumb"></span>
     <svg class="toggle-sun" style="position: absolute; left: 8px; z-index: 2; pointer-events: none; width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
     <svg class="toggle-moon" style="position: absolute; right: 8px; z-index: 2; pointer-events: none; width: 16px; height: 16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
   </button>
  </div>

</div>
</header>

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
