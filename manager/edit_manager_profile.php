<?php
session_start();
include("../config.php");

if(!isset($_SESSION['manager_id'])){
 header("Location: manager_login.php");
 exit();
}

$manager_id = $_SESSION['manager_id'];

// Fetch manager info
$manager_q = mysqli_query($conn, "SELECT * FROM managers WHERE id='$manager_id' LIMIT 1");
$manager = mysqli_fetch_assoc($manager_q);

// Fetch states
$states = mysqli_query($conn,"SELECT * FROM states WHERE status='active' ORDER BY state_name ASC");

// Handle form submission
if(isset($_POST['update'])){
 $hotel_name = mysqli_real_escape_string($conn,$_POST['hotel_name']);
 $email = mysqli_real_escape_string($conn,$_POST['email']);
 $phone = mysqli_real_escape_string($conn,$_POST['phone']);
 $state_id = $_POST['state_id'];
 $city_id = $_POST['city_id'];
 $pincode = mysqli_real_escape_string($conn,$_POST['pincode']);

 $update = mysqli_query($conn, "UPDATE managers SET 
 hotel_name='$hotel_name',
 email='$email',
 phone='$phone',
 state_id='$state_id',
 city_id='$city_id',
 pincode='$pincode'
 WHERE id='$manager_id'
 ");

 if($update){
 // Redirect to profile page after update
 header("Location: manage_users.php");
 exit();
 } else {
 $error = mysqli_error($conn);
 }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="bg-white p-10 rounded-xl shadow-xl w-full max-w-lg">

<h2 class="text-3xl font-bold text-center mb-6 text-primary">Edit Profile</h2>

<?php if(isset($error)){ ?>
<div class="bg-red-100 text-error p-3 mb-4 rounded-lg text-center font-semibold"><?= $error ?></div>
<?php } ?>

<form method="POST" class="space-y-4">

<input type="text" name="hotel_name" placeholder="Hotel Name" value="<?= htmlspecialchars($manager['hotel_name']) ?>" class="w-full p-3 border rounded-lg" required>
<input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($manager['email']) ?>" class="w-full p-3 border rounded-lg" required>
<input type="text" name="phone" placeholder="Phone" value="<?= htmlspecialchars($manager['phone']) ?>" class="w-full p-3 border rounded-lg">

<select name="state_id" id="state_id" class="w-full p-3 border rounded-lg" required>
<option value="">Select State</option>
<?php while($state=mysqli_fetch_assoc($states)){ ?>
<option value="<?= $state['id'] ?>" <?= $state['id']==$manager['state_id']?'selected':'' ?>><?= $state['state_name'] ?></option>
<?php } ?>
</select>

<select name="city_id" id="city_id" class="w-full p-3 border rounded-lg" required>
<option value="">Select City</option>
<?php
$city_q = mysqli_query($conn,"SELECT * FROM cities WHERE state_id='".$manager['state_id']."' ORDER BY city_name ASC");
while($city=mysqli_fetch_assoc($city_q)){
?>
<option value="<?= $city['id'] ?>" <?= $city['id']==$manager['city_id']?'selected':'' ?>><?= $city['city_name'] ?></option>
<?php } ?>
</select>

<input type="text" name="pincode" placeholder="Pincode" value="<?= htmlspecialchars($manager['pincode']) ?>" class="w-full p-3 border rounded-lg" required>

<button type="submit" name="update" class="w-full bg-primary text-white p-3 rounded-lg hover:bg-primary font-semibold">Update Profile</button>

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
