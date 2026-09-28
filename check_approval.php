<?php
session_start();
include("config.php"); // Database connection

// Redirect if not logged in
if(!isset($_SESSION['user'])){
 header("Location: login.php");
 exit;
}

$message = "";

if(isset($_POST['change_password'])){
 $user = $_SESSION['user'];
 $current_password = $_POST['current_password'];
 $new_password = $_POST['new_password'];
 $confirm_password = $_POST['confirm_password'];

 // Fetch user's current password hash
 $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
 $stmt->bind_param("s", $user);
 $stmt->execute();
 $stmt->bind_result($password_hash);
 $stmt->fetch();
 $stmt->close();

 // Check current password
 if(password_verify($current_password, $password_hash)){
 // Check new password match
 if($new_password === $confirm_password){
  // Hash new password
  $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

  // Update in database
  $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
  $stmt->bind_param("ss", $new_hash, $user);
  if($stmt->execute()){
  $message = "<p class='text-success'>Password changed successfully!</p>";
  } else {
  $message = "<p class='text-error'>Failed to update password. Try again.</p>";
  }
  $stmt->close();
 } else {
  $message = "<p class='text-error'>New password and confirm password do not match.</p>";
 }
 } else {
 $message = "<p class='text-error'>Current password is incorrect.</p>";
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password - Hotel Sphere</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-light">

<div class="max-w-md mx-auto mt-24 bg-white p-8 rounded shadow">
 <h2 class="text-2xl font-bold mb-6 text-primary text-center">Change Password</h2>

 <?php if($message != ""): ?>
 <div class="mb-4">
  <?php echo $message; ?>
 </div>
 <?php endif; ?>

 <form method="POST" class="space-y-4">
 <div>
  <label class="block font-semibold mb-1">Current Password</label>
  <input type="password" name="current_password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 border-focus">
 </div>
 <div>
  <label class="block font-semibold mb-1">New Password</label>
  <input type="password" name="new_password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 border-focus">
 </div>
 <div>
  <label class="block font-semibold mb-1">Confirm New Password</label>
  <input type="password" name="confirm_password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 border-focus">
 </div>
 <button type="submit" name="change_password" class="w-full bg-primary text-white py-2 rounded hover:bg-primary">Update Password</button>
 </form>

 <div class="mt-4 text-center">
 <a href="index.php" class="text-primary hover:underline">Back to Home</a>
 </div>
</div>

</body>
</html>
