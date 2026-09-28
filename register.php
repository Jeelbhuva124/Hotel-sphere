<?php 
include "config.php";

$errors = [];

if(isset($_POST['reg'])){

 $name = trim($_POST['name']);
 $email = trim($_POST['email']);
 $role = $_POST['role'];
 $password = $_POST['password'];

 // Check if email already exists
 $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
 $stmt->bind_param("s", $email);
 $stmt->execute();
 $stmt->store_result();
 if($stmt->num_rows > 0){
 $errors[] = "Email is already registered!";
 }
 $stmt->close();

 if(empty($errors)){
 $pass_hash = password_hash($password, PASSWORD_DEFAULT);

 $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
 $stmt->bind_param("ssss", $name, $email, $pass_hash, $role);
 if($stmt->execute()){
  // Registration successful, redirect to login
  header("Location: login.php");
  exit();
 } else {
  $errors[] = "Database error: " . $stmt->error;
 }
 $stmt->close();
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - LuxeStay</title>
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
</script>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="min-h-screen flex items-center justify-center bg-light">

<div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-10 relative overflow-hidden">

 <h2 class="text-4xl font-bold mb-6 text-center text-primary">Register Account</h2>

 <?php if(!empty($errors)): ?>
 <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-error">
  <?php foreach($errors as $err) echo "<p>$err</p>"; ?>
 </div>
 <?php endif; ?>

 <form method="POST" class="space-y-5">

 <!-- Name -->
 <div class="relative">
  <span class="absolute left-3 top-3 text-gray-400"><i class="fa fa-user"></i></span>
  <input type="text" name="name" required placeholder="Full Name"
  class="w-full border border-gray-300 rounded-lg p-3 pl-10 focus:outline-none focus:ring-2 border-focus transition"
  value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" autocomplete="off">
 </div>

 <!-- Email -->
 <div class="relative">
  <span class="absolute left-3 top-3 text-gray-400"><i class="fa fa-envelope"></i></span>
  <input type="email" name="email" required placeholder="Email"
  class="w-full border border-gray-300 rounded-lg p-3 pl-10 focus:outline-none focus:ring-2 border-focus transition"
  value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="off">
 </div>

 <!-- Password -->
 <div class="relative">
  <span class="absolute left-3 top-3 text-gray-400"><i class="fa fa-lock"></i></span>
  <input type="password" name="password" required placeholder="Password"
  class="w-full border border-gray-300 rounded-lg p-3 pl-10 focus:outline-none focus:ring-2 border-focus transition"
  autocomplete="new-password">
 </div>

 <!-- Role Select -->
 <select name="role" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 border-focus transition">
  
  <option value="user" <?= (($_POST['role'] ?? '') === 'user' || empty($_POST['role'])) ? 'selected' : '' ?>>User</option>
 </select>

 <!-- Register Button -->
 <button name="reg"
 class="w-full bg-primary hover:bg-primary text-white font-semibold py-3 rounded-lg transition transform hover:scale-105">
  Register
 </button>

 <!-- Login Link -->
 <p class="text-center text-gray-600">
  Already have an account? 
  <a href="login.php" class="text-primary font-semibold hover:underline">Login</a>
 </p>

 <!-- Manager Register Link -->
 <p class="text-center text-gray-600 mt-2">
  Are you a Manager? 
  <a href="manager/manager.php" class="text-primary font-semibold hover:underline">
  Register as Manager
  </a>
 </p>
 </form>
</div>

</body>
</html>
