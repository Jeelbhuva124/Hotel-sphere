<?php
session_start();

if(isset($_POST['login'])){
 $user = trim($_POST['username']);
 $pass = trim($_POST['password']);

 if($user=="admin@gmail.com" && $pass=="Admin@123"){
 $_SESSION['role'] = "admin"; // use role to match dashboard.php
 $_SESSION['admin'] = $user;
 header("Location: dashboard.php");
 exit();
 } else {
 $error = "Invalid Login";
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center p-4">

<div class="w-full max-w-sm bg-white rounded-3xl shadow-2xl p-8 relative">

 <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Admin Login</h2>

 <!-- Error Message -->
 <?php if(isset($error)) { ?>
 <div class="bg-red-100 text-error border border-red-400 p-3 rounded mb-4 text-center">
  <?= $error ?>
 </div>
 <?php } ?>

 <!-- Login Form -->
 <form method="POST" class="space-y-6" autocomplete="off">

 <!-- Username with Floating Label -->
 <div class="relative">
  <input type="text" name="username" id="username" placeholder=" " 
  class="peer w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-transparent" required>
  <label for="username" 
  class="absolute left-3 top-3 text-gray-400 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base peer-focus:top-1 peer-focus:text-gray-700 peer-focus:text-sm">
  
  </label>
 </div>

 <!-- Password with Floating Label -->
 <div class="relative">
  <input type="password" name="password" id="password" placeholder=" " 
  class="peer w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-400 focus:outline-none placeholder-transparent" required>
  <label for="password" 
  class="absolute left-3 top-3 text-gray-400 text-sm transition-all peer-placeholder-shown:top-3 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:text-base peer-focus:top-1 peer-focus:text-gray-700 peer-focus:text-sm">
  
  </label>
 </div>

 <!-- Login Button -->
 <button type="submit" name="login" 
 class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-lg transition transform hover:-translate-y-1">
  Login
 </button>

 </form>

 <!-- Footer -->
 <p class="text-sm text-gray-500 mt-6 text-center">© 2026 HotelBook Admin Panel</p>
</div>

</body>
</html>
