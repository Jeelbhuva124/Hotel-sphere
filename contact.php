<?php
// Start session safely
if (session_status() == PHP_SESSION_NONE) {
 session_start();
}

// Include database connection
include "config.php";

// --- Fetch Hotels for Dropdown ---
$hotels_result = mysqli_query($conn, "SELECT id, hotel_name FROM hotel ORDER BY hotel_name ASC");

// Handle form submission
$success = $error = "";

if(isset($_POST['submit'])) {
 $hotel_id = (int)$_POST['hotel_id'];
 $name = trim($_POST['name']);
 $email = trim($_POST['email']);
 $message = trim($_POST['message']);

 if(!empty($hotel_id) && !empty($name) && !empty($email) && !empty($message)) {
 $stmt = $conn->prepare("INSERT INTO contact (hotel_id, name, email, message, created_at) VALUES (?, ?, ?, ?, NOW())");
 if($stmt) {
  $stmt->bind_param("isss", $hotel_id, $name, $email, $message);
  if($stmt->execute()){
  $success = "Message Sent Successfully!";
  } else {
  $error = "Error: " . $stmt->error;
  }
  $stmt->close();
 } else {
  $error = "Database Error: " . $conn->error;
 }
 } else {
 $error = "All fields are required!";
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>Contact Us - Hotel Booking</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">



<main class="flex-1 max-w-5xl mx-auto mt-10 p-6">
 <h2 class="text-4xl font-bold text-center text-primary">Contact Us</h2>

 <?php if($success): ?>
 <div class="bg-green-100 text-success p-4 rounded my-4 text-center"><?= $success ?></div>
 <?php endif; ?>
 <?php if($error): ?>
 <div class="bg-red-100 text-error p-4 rounded my-4 text-center"><?= $error ?></div>
 <?php endif; ?>

 <form method="POST" class="bg-white shadow p-6 rounded mt-8 space-y-4">
 <div>
  <label class="block font-semibold mb-1">Select Hotel</label>
  <select name="hotel_id" class="w-full border p-2 rounded" required>
  <option value="">-- Choose Hotel --</option>
  <?php while($hotel = mysqli_fetch_assoc($hotels_result)): ?>
   <option value="<?= $hotel['id'] ?>"><?= htmlspecialchars($hotel['hotel_name']) ?></option>
  <?php endwhile; ?>
  </select>
 </div>

 <div>
  <label class="block font-semibold mb-1">Name</label>
  <input type="text" name="name" class="w-full border p-2 rounded" required>
 </div>

 <div>
  <label class="block font-semibold mb-1">Email</label>
  <input type="email" name="email" class="w-full border p-2 rounded" required>
 </div>

 <div>
  <label class="block font-semibold mb-1">Message</label>
  <textarea name="message" rows="5" class="w-full border p-2 rounded" required></textarea>
 </div>

 <button type="submit" name="submit" class="bg-primary text-white px-6 py-2 rounded hover:bg-primary">
  Send Message
 </button>
 </form>
</main>

<footer class="bg-gray-200 text-center p-4 mt-10">&copy; <?= date("Y") ?> Hotel Booking</footer>
</body>
</html>
