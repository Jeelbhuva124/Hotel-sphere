<?php
session_start();
include("../config.php");

// Fetch states
$states = mysqli_query($conn,"SELECT * FROM states WHERE status='active' ORDER BY state_name ASC");

// Register Manager
if(isset($_POST['register']))
{
 $hotel_name = mysqli_real_escape_string($conn,$_POST['hotel_name']);
 $email = mysqli_real_escape_string($conn,$_POST['email']);
 $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
 $phone = $_POST['phone']; // Keep raw to validate digits only
 $state_id = $_POST['state_id'];
 $city_id = $_POST['city_id'];
 $pincode = mysqli_real_escape_string($conn,$_POST['pincode']);

 // Server-side phone number validation
 if(!preg_match('/^[0-9]{10}$/', $phone)) {
 $errorPhone = "Phone number must be exactly 10 digits!";
 }
 else
 {
 // Check email uniqueness
 $check = mysqli_query($conn,"SELECT * FROM managers WHERE email='$email'");
 if(mysqli_num_rows($check) > 0)
 {
  $errorEmail = "Email already exists!";
 }
 else
 {
  $insert = mysqli_query($conn,"INSERT INTO managers
  (hotel_name,email,password,phone,state_id,city_id,pincode)
  VALUES
  ('$hotel_name','$email','$password','$phone','$state_id','$city_id','$pincode')");

  if($insert)
  {
  // Redirect to login page after successful registration
  header("Location: manager_login.php");
  exit();
  }
  else
  {
  $errorDB = "Database error: ".mysqli_error($conn);
  }
 }
 }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manager Register</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-lg">

<h2 class="text-3xl font-bold text-center mb-6 text-primary">Manager Register</h2>

<form method="POST" autocomplete="off" class="space-y-4" id="registerForm">

<input type="text" name="hotel_name" placeholder="Hotel Name"
value="<?= $_POST['hotel_name'] ?? '' ?>"
class="w-full p-3 border rounded-lg" required>

<?php if(isset($errorDB)) { ?>
<p class="text-error text-sm"><?= $errorDB ?></p>
<?php } ?>

<input type="email" name="email" placeholder="Email"
value="<?= $_POST['email'] ?? '' ?>"
class="w-full p-3 border rounded-lg" required>
<?php if(isset($errorEmail)) { ?>
<p class="text-error text-sm"><?= $errorEmail ?></p>
<?php } ?>

<input type="password" name="password" placeholder="Password"
class="w-full p-3 border rounded-lg" required autocomplete="new-password">

<input type="text" name="phone" id="phone" placeholder="Phone"
value="<?= $_POST['phone'] ?? '' ?>"
class="w-full p-3 border rounded-lg" required>
<p id="phoneError" class="text-error text-sm hidden">Phone number must be exactly 10 digits!</p>

<select name="state_id" id="state_id" required class="w-full p-3 border rounded-lg">
<option value="">Select State</option>
<?php while($state=mysqli_fetch_assoc($states)){ ?>
<option value="<?= $state['id'] ?>" <?= (isset($_POST['state_id']) && $_POST['state_id']==$state['id'])?'selected':'' ?>>
<?= $state['state_name'] ?>
</option>
<?php } ?>
</select>

<select name="city_id" id="city_id" required class="w-full p-3 border rounded-lg">
<option value="">Select City</option>
</select>

<input type="text" name="pincode" placeholder="Pincode"
value="<?= $_POST['pincode'] ?? '' ?>"
class="w-full p-3 border rounded-lg" required>

<button type="submit" name="register"
class="w-full bg-primary text-white p-3 rounded-lg hover:bg-primary">
Register
</button>

</form>

<p class="text-center text-gray-500 mt-4">
Already have account?
<a href="manager_login.php" class="text-primary">Login</a>
</p>

</div>

<script>
$(document).ready(function(){

 // Load cities when state changes
 $("#state_id").change(function(){
 var state_id=$(this).val();
 if(state_id!="")
 {
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

 // Only allow digits in phone field
 $("#phone").on("input", function() {
 this.value = this.value.replace(/[^0-9]/g,''); // Remove non-digit characters
 if(this.value.length > 10) this.value = this.value.slice(0,10); // Max 10 digits
 if(this.value.length === 10){
  $("#phoneError").hide();
 }
 });

 // Validate phone on submit
 $("#registerForm").submit(function(e){
 var phone = $("#phone").val();
 if(phone.length !== 10){
  $("#phoneError").show();
  $("#phone").focus();
  e.preventDefault();
 }
 });

});
</script>

</body>
</html>