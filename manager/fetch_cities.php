<?php
include("../config.php");

if(isset($_POST['state_id'])){
 $state_id = $_POST['state_id'];

 $cities = mysqli_query($conn,"SELECT * FROM cities WHERE state_id='$state_id'");

 echo "<option value=''>Select City</option>";

 while($row=mysqli_fetch_assoc($cities)){
 echo "<option value='".$row['id']."'>".$row['city_name']."</option>";
 }
}
?>