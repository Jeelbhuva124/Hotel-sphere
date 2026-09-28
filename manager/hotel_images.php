<?php
include("../config.php");

$state_id = $_POST['state_id'];

$query = mysqli_query($conn,"SELECT * FROM cities WHERE state_id='$state_id' ORDER BY city_name ASC");

echo "<option value=''>Select City</option>";

while($row = mysqli_fetch_assoc($query)){
 echo "<option value='".$row['id']."'>".$row['city_name']."</option>";
}
?>
