<?php
include("config.php"); // database connection

$success = "";

// Fetch hotels for dropdown
$hotels = mysqli_query($conn, "SELECT id, hotel_name FROM hotel ORDER BY hotel_name ASC");

if(isset($_POST['submit'])){
 $hotel_id = (int)$_POST['hotel_id'];
 $user_id = $_SESSION['user_id'] ?? NULL; // if logged-in user
 $name = $conn->real_escape_string($_POST['name']);
 $email = $conn->real_escape_string($_POST['email']);
 $message = $conn->real_escape_string($_POST['message']);
 $rating = (int)$_POST['rating'];

 $sql = "INSERT INTO feedback(hotel_id, user_id, name, email, message, rating, created_at)
  VALUES ('$hotel_id', ".($user_id ? $user_id : "NULL").", '$name', '$email', '$message', '$rating', NOW())";

 if($conn->query($sql) === TRUE){
 $success = "Thank you for your feedback!";
 } else {
 $success = "Error: " . $conn->error;
 }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feedback Form</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100">

<div class="max-w-5xl mx-auto mt-10 p-6 bg-white rounded shadow">

 <h1 class="text-4xl font-bold text-center text-primary mb-6">Give Us Your Feedback</h1>

 <?php if($success): ?>
 <div class="bg-green-100 text-success p-3 mb-4 rounded text-center"><?= $success ?></div>
 <?php endif; ?>

 <form method="POST" class="space-y-4">

 <div>
  <label class="block font-semibold mb-1">Select Hotel</label>
  <select name="hotel_id" class="border p-2 w-full rounded focus:ring-2 border-focus" required>
  <option value="">-- Choose Hotel --</option>
  <?php while($hotel = mysqli_fetch_assoc($hotels)): ?>
   <option value="<?= $hotel['id'] ?>"><?= htmlspecialchars($hotel['hotel_name']) ?></option>
  <?php endwhile; ?>
  </select>
 </div>

 <div>
  <label class="block font-semibold mb-1">Name</label>
  <input type="text" name="name" class="border p-2 w-full rounded focus:ring-2 border-focus" placeholder="Your Name" required>
 </div>

 <div>
  <label class="block font-semibold mb-1">Email</label>
  <input type="email" name="email" class="border p-2 w-full rounded focus:ring-2 border-focus" placeholder="you@example.com" required>
 </div>

 <div>
  <label class="block font-semibold mb-1">Message</label>
  <textarea name="message" rows="5" class="border p-2 w-full rounded focus:ring-2 border-focus" placeholder="Your feedback..." required></textarea>
 </div>

 <div>
  <label class="block font-semibold mb-1">Rating</label>
  <select name="rating" class="border p-2 w-full rounded focus:ring-2 border-focus" required>
  <option value="">Select Rating</option>
  <option value="1">1 - Poor</option>
  <option value="2">2 - Fair</option>
  <option value="3">3 - Good</option>
  <option value="4">4 - Very Good</option>
  <option value="5">5 - Excellent</option>
  </select>
 </div>

 <button type="submit" name="submit" class="w-full bg-primary hover:bg-primary text-white font-bold py-3 rounded transition">
  Submit Feedback
 </button>

 </form>
</div>

</body>
</html>
