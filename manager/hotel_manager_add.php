<?php
session_start();
include("../config.php");

// Fetch states
$states = mysqli_query($conn,"SELECT * FROM states WHERE status='active' ORDER BY state_name ASC");

// Add Hotel + Manager
if(isset($_POST['add']))
{
 // HOTEL DATA
 $hotel_name = mysqli_real_escape_string($conn,$_POST['hotel_name']);
 $state_id = $_POST['state_id'];
 $city_id = $_POST['city_id'];
 $pincode = mysqli_real_escape_string($conn,$_POST['pincode']);
 $address = mysqli_real_escape_string($conn,$_POST['address']);

 // MANAGER DATA
 $manager_name = mysqli_real_escape_string($conn,$_POST['manager_name']);
 $email  = mysqli_real_escape_string($conn,$_POST['email']);
 $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
 $phone  = mysqli_real_escape_string($conn,$_POST['phone']);

 // Check email
 $check = mysqli_query($conn,"SELECT * FROM managers WHERE email='$email'");
 if(mysqli_num_rows($check) > 0){
 $error = "Email already exists!";
 } else {
 // Insert manager first
 $insert_manager = mysqli_query($conn,"INSERT INTO managers
  (hotel_name,email,password,phone,state_id,city_id,pincode)
  VALUES
  ('$hotel_name','$email','$password','$phone','$state_id','$city_id','$pincode')");

 if($insert_manager){
  $success = "Hotel & Manager Added Successfully!";
  $_POST=[];
 } else {
  $error = mysqli_error($conn);
 }
 }
}
?>

<!DOCTYPE html>
<html>
<head>
 <title>Add Hotel + Manager</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-2xl">
<h2 class="text-3xl font-bold text-center mb-6 text-primary">Add Hotel + Manager</h2>

<?php if(isset($error)){ ?>
<div class="bg-red-100 text-error p-2 mb-4 rounded">
<?= $error ?>
</div>
<?php } ?>

<?php if(isset($success)){ ?>
<div class="bg-green-100 text-success p-2 mb-4 rounded">
<?= $success ?>
</div>
<?php } ?>

<form method="POST" autocomplete="off" class="space-y-4">

<!-- HOTEL DETAILS -->
<h3 class="text-xl font-semibold text-primary">Hotel Details</h3>

<input type="text" name="hotel_name" placeholder="Hotel Name"
value="<?= $_POST['hotel_name'] ?? '' ?>" class="w-full p-3 border rounded-lg" required>

<input type="text" name="address" placeholder="Hotel Address"
value="<?= $_POST['address'] ?? '' ?>" class="w-full p-3 border rounded-lg" required>

<select name="state_id" id="state_id" required class="w-full p-3 border rounded-lg">
<option value="">Select State</option>
<?php while($state=mysqli_fetch_assoc($states)){ ?>
<option value="<?= $state['id'] ?>"><?= $state['state_name'] ?></option>
<?php } ?>
</select>

<select name="city_id" id="city_id" required class="w-full p-3 border rounded-lg">
<option value="">Select City</option>
</select>

<input type="text" name="pincode" placeholder="Pincode"
value="<?= $_POST['pincode'] ?? '' ?>" class="w-full p-3 border rounded-lg" required>

<!-- MANAGER DETAILS -->
<h3 class="text-xl font-semibold text-primary mt-4">Manager Details</h3>

<input type="text" name="manager_name" placeholder="Manager Name"
value="<?= $_POST['manager_name'] ?? '' ?>" class="w-full p-3 border rounded-lg" required>

<input type="email" name="email" placeholder="Email"
value="<?= $_POST['email'] ?? '' ?>" class="w-full p-3 border rounded-lg" required>

<input type="password" name="password" placeholder="Password"
class="w-full p-3 border rounded-lg" required autocomplete="new-password">

<input type="text" name="phone" placeholder="Phone"
value="<?= $_POST['phone'] ?? '' ?>" class="w-full p-3 border rounded-lg">

<button type="submit" name="add" class="w-full bg-primary text-white p-3 rounded-lg hover:bg-primary">
Add Hotel + Manager
</button>

</form>
</div>

<script>
$(document).ready(function(){
 $("#state_id").change(function(){
 var state_id=$(this).val();
 if(state_id!=""){
  $.ajax({
  url:"fetch_cities.php",
  type:"POST",
  data:{state_id:state_id},
  success:function(data){
   $("#city_id").html(data);
  }
  });
 }
 });
});
</script>

</body>
</html> 