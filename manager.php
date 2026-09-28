<?php
session_start();
include("config.php");

if(!isset($_SESSION['user_id']))
{
 header("Location:Login.php");
 exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

if(isset($_POST['register']))
{
 $state = $_POST['state'];
 $city = $_POST['city'];
 $pincode = $_POST['pincode'];
 $hotel_name = $_POST['hotel_name'];
 $hotel_address = $_POST['hotel_address'];

 $sql = "INSERT INTO managers(user_id,state,city,pincode,hotel_name,hotel_address)
  VALUES('$user_id','$state','$city','$pincode','$hotel_name','$hotel_address')";

 if($conn->query($sql))
 {
 echo "<script>alert('Manager Registered Successfully');</script>";
 }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Manager Register</title>

<style>

body{
font-family:Arial;
background:#f4f6f9;
}

.container{
width:500px;
margin:50px auto;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0px 0px 10px rgba(0,0,0,0.1);
}

h2{
text-align:center;
margin-bottom:20px;
}

input,textarea{
width:100%;
padding:10px;
margin:8px 0;
border:1px solid #ccc;
border-radius:5px;
}

button{
width:100%;
padding:12px;
background:#007bff;
color:white;
border:none;
border-radius:5px;
font-size:16px;
cursor:pointer;
}

button:hover{
background:#0056b3;
}

</style>

 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>

<body>

<div class="container">

<h2>Register Your Hotel</h2>

<form method="POST">

<label>Email</label>
<input type="email" value="<?php echo $email; ?>" readonly>

<label>Hotel Name</label>
<input type="text" name="hotel_name" placeholder="Enter Hotel Name" required>

<label>Hotel Address</label>
<textarea name="hotel_address" placeholder="Enter Hotel Address" required></textarea>

<label>State</label>
<input type="text" name="state" placeholder="Enter State" required>

<label>City</label>
<input type="text" name="city" placeholder="Enter City" required>

<label>Pincode</label>
<input type="text" name="pincode" placeholder="Enter Pincode" maxlength="6" required>

<button type="submit" name="register">Register Hotel</button>

</form>

</div>

</body>
</html>
