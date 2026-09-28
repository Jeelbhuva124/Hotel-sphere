<?php
session_start();
include("config.php");

// Ensure user is logged in
if(!isset($_SESSION['user'])){
 header("Location: login.php");
 exit;
}

$message = "";
$message_type = ""; // "success" or "error"

if(isset($_POST['change_password'])){
 $user = $_SESSION['user'];
 $current_password = $_POST['current_password'];
 $new_password = $_POST['new_password'];
 $confirm_password = $_POST['confirm_password'];

 // Fetch current password hash
 $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
 $stmt->bind_param("s", $user);
 $stmt->execute();
 $stmt->bind_result($password_hash);
 $stmt->fetch();
 $stmt->close();

 if(password_verify($current_password, $password_hash)){
 if($new_password === $confirm_password){
  $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

  $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
  $stmt->bind_param("ss", $new_hash, $user);
  if($stmt->execute()){
  $message = "Password changed successfully!";
  $message_type = "success";
  } else {
  $message = "Failed to update password. Try again.";
  $message_type = "error";
  }
  $stmt->close();
 } else {
  $message = "New password and confirm password do not match.";
  $message_type = "error";
 }
 } else {
 $message = "Current password is incorrect.";
 $message_type = "error";
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password - Hotel Sphere</title>
<link rel="stylesheet" href="style.css">
<script src="https://cdn.tailwindcss.com"></script>
<style>
/* Optional subtle fade effect for messages */
.fade-message {
 animation: fadeIn 0.5s ease forwards;
}
@keyframes fadeIn {
 from { opacity: 0; transform: translateY(-5px);}
 to { opacity: 1; transform: translateY(0);}
}
</style>
</head>
<body>

<div class="max-w-md mx-auto mt-24 bg-white p-8 rounded shadow">
 <h2 class="text-2xl font-bold mb-6 text-primary text-center">Change Password</h2>

 <?php if($message != ""): ?>
 <div class="mb-4 fade-message <?php echo $message_type === 'success' ? 'text-success' : 'text-error'; ?> font-semibold">
  <?php echo $message; ?>
 </div>
 <?php endif; ?>

 <form method="POST" class="space-y-4">
 <div>
  <label class="block font-semibold mb-1">Current Password</label>
  <input type="password" name="current_password" required class="w-full border px-3 py-2 rounded border-focus">
 </div>
 <div>
  <label class="block font-semibold mb-1">New Password</label>
  <input type="password" name="new_password" required class="w-full border px-3 py-2 rounded border-focus">
 </div>
 <div>
  <label class="block font-semibold mb-1">Confirm New Password</label>
  <input type="password" name="confirm_password" required class="w-full border px-3 py-2 rounded border-focus">
 </div>
 <button type="submit" name="change_password" class="w-full bg-primary text-white py-2 rounded bg-primary-hover transition-colors">Update Password</button>
 </form>

 <div class="mt-4 text-center">
 <a href="index.php" class="text-primary hover:underline">Back to Home</a>
 </div>
</div>

</body>
</html>