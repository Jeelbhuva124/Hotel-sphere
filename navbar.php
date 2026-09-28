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
<div class="max-w-7xl mx-auto px-8 py-4 flex items-center justify-between">

<!-- Logo -->
<div class="text-3xl font-bold text-primary tracking-wide">
Hotel Sphere
</div>

<!-- Menu Links -->
<nav class="hidden md:flex items-center space-x-8 text-lg font-medium text-gray-800">

<a href="index.php" class="hover:text-primary transition">Home</a>
<!-- <a href="about.php" class="hover:text-primary transition">About</a> -->
<a href="index.php?page=rooms" class="hover:text-primary transition">Hotels</a>
<a href="contact.php" class="hover:text-primary transition">Contact</a>
<a href="feedback.php" class="hover:text-primary transition">Feedback</a>

<!-- Buttons -->
<!-- <a href="login.php" 
class="bg-gray-800 text-white px-6 py-2 rounded-full hover:bg-gray-900 transition font-medium tracking-wide">
LOGIN
</a>

<a href="register.php" 
class="bg-green-600 text-white px-6 py-2 rounded-full hover:bg-green-700 transition font-medium tracking-wide shadow-md">
REGISTER
</a><!-- Custom Theme Toggle Switch -->
<button id="theme-toggle" class="custom-toggle" aria-label="Toggle Theme">
  <span class="toggle-thumb" id="toggle-thumb">
     <i class="fa-solid fa-moon" id="toggle-icon"></i>
  </span>
</button>

</nav>

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