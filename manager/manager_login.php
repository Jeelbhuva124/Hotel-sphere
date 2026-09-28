<?php
session_start();
include("../config.php"); // DB connection

$message = "";

if(isset($_POST['login'])){
 $email = mysqli_real_escape_string($conn, $_POST['email']);
 $password = $_POST['password'];

 // Fetch manager from DB
 $res = mysqli_query($conn, "SELECT * FROM managers WHERE email='$email'");
 if(!$res){
 die("Query error: ".mysqli_error($conn));
 }

 if(mysqli_num_rows($res) > 0){
 $manager = mysqli_fetch_assoc($res);

 // Verify password
 if(password_verify($password, $manager['password'])){
  // Login success
  $_SESSION['manager_id'] = $manager['id'];
  $_SESSION['manager_name'] = $manager['hotel_name']; // Hotel name for session
  $_SESSION['email'] = $manager['email'];

  header("Location: manager_dashboard.php");
  exit;
 } else {
  $message = "Incorrect Password!";
 }
 } else {
 $message = "Email not found!";
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manager Login</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-md">
<h2 class="text-3xl font-bold mb-6 text-center text-primary">Manager Login</h2>

<?php if($message != ""){ ?>
 <div class="bg-red-100 text-error p-2 mb-4 rounded"><?php echo $message; ?></div>
<?php } ?>

<form method="POST" autocomplete="off" class="space-y-4">

<input type="email" name="email" placeholder="Email"
class="w-full p-3 border rounded-lg" required>

<input type="password" name="password" placeholder="Password"
class="w-full p-3 border rounded-lg" required>

<button type="submit" name="login"
class="w-full bg-primary text-white p-3 rounded-lg hover:bg-primary">Login</button>
</form>

<p class="text-center text-gray-500 mt-4">
Don't have an account?
<a href="manager.php" class="text-primary">Register</a>
</p>
</div>

</body>
</html>
