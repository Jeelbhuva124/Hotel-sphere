<?php
session_start();
include("config.php");

/* ✅ CAPTCHA */
if(!isset($_SESSION['captcha']) || !isset($_SESSION['captcha_time']) || time()-($_SESSION['captcha_time'])>300){
 $_SESSION['captcha'] = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"),0,6);
 $_SESSION['captcha_time'] = time();
}

$error = "";

if(isset($_POST['login'])){
 
 $role_selected = $_POST['role'] ?? "";
 $email = mysqli_real_escape_string($conn,$_POST['email']);
 $password = $_POST['password'];
 $captcha_input = $_POST['captcha'];

 /* ✅ CAPTCHA CHECK */
 if(strtoupper($captcha_input) !== $_SESSION['captcha']){
 $error = "Incorrect captcha!";
 } else {

 $user = null;
 $role = "";

 /* 🔥 ADMIN (HARDCODE LOGIN) */
 if($role_selected == "admin"){

  if($email == "admin@gmail.com" && $password == "Admin@123"){
  
  $_SESSION['user'] = "Admin";
  $_SESSION['role'] = "admin";
  $_SESSION['id'] = 1;
  $_SESSION['user_id'] = 1;
  $_SESSION['user_email'] = "admin@gmail.com"; // added

  header("Location: admin/dashboard.php");
  exit();

  } else {
  $error = "Invalid Admin Credentials!";
  }
 }

 /* ✅ USER LOGIN */
 elseif($role_selected == "user"){

  $result = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

  if($result && mysqli_num_rows($result)>0){
  $user = mysqli_fetch_assoc($result);
  $role = "user";
  }

  if($user && password_verify($password,$user['password'])){

  $_SESSION['user'] = $user['name'];
  $_SESSION['role'] = $role;
  $_SESSION['id'] = $user['id'];
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['user_email'] = $user['email']; // added

  header("Location: index.php");
  exit();

  } else {
  $error = "Invalid User Email or Password!";
  }
 }

 /* ✅ MANAGER LOGIN */
 elseif($role_selected == "manager"){

  $result = mysqli_query($conn,"SELECT * FROM managers WHERE email='$email'");

  if($result && mysqli_num_rows($result)>0){
  $user = mysqli_fetch_assoc($result);
  $role = "manager";
  }

  if($user && password_verify($password,$user['password'])){

  $_SESSION['user'] = $user['hotel_name'];
  $_SESSION['role'] = $role;
  $_SESSION['id'] = $user['id'];
  $_SESSION['user_id'] = $user['id'];
  $_SESSION['user_email'] = $user['email']; // added

  header("Location: manager/manager_dashboard.php");
  exit();

  } else {
  $error = "Invalid Manager Email or Password!";
  }
 }

 }

 /* 🔄 NEW CAPTCHA */
 $_SESSION['captcha'] = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"),0,6);
 $_SESSION['captcha_time'] = time();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }
</script>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body class="bg-gradient-to-r from-indigo-200 to-indigo-50 flex justify-center items-center min-h-screen">

<form method="POST" class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md">

<h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Login</h2>

<?php if($error!=""){ ?>
<p class="text-error text-center mb-4"><?php echo $error; ?></p>
<?php } ?>

<!-- Role -->
<select name="role" class="w-full p-3 border rounded-lg mb-4" required>
<option value="">-- Select Role --</option>
<option value="admin">Admin</option>
<option value="manager">Manager</option>
<option value="user">User</option>
</select>

<!-- Email -->
<input type="email" name="email" placeholder="Email" class="w-full p-3 border rounded-lg mb-4" required>

<!-- Password -->
<input type="password" name="password" placeholder="Password" class="w-full p-3 border rounded-lg mb-4" required>

<!-- Captcha -->
<div class="mb-4">
<div class="flex items-center mb-2">
<span class="bg-gray-200 px-4 py-2 rounded-lg font-bold text-xl tracking-widest">
<?php echo $_SESSION['captcha']; ?>
</span>
</div>

<input type="text" name="captcha" placeholder="Enter captcha" class="w-full p-3 border rounded-lg" required>
</div>

<!-- Button -->
<button name="login" class="w-full bg-primary text-white p-3 rounded-lg hover:bg-primary">
Login
</button>

<!-- Links -->
<p class="text-center mt-4 text-gray-600">
Don't have an account? <br>
<a href="manager/manager.php" class="text-primary font-semibold">Manager</a> | 
<a href="register.php" class="text-primary font-semibold">User</a>
</p>

</form>

</body>
</html>