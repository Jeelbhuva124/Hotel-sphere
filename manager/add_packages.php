<?php
include("../config.php");

if(isset($_POST['add_package'])){

$room_id=$_POST['room_id'];
$package_name=$_POST['package_name'];
$extra_price=$_POST['extra_price'];

mysqli_query($conn,"INSERT INTO room_packages(room_id,package_name,extra_price)
VALUES('$room_id','$package_name','$extra_price')");

echo "Package Added";
}
?>

<form method="POST">

Room

<select name="room_id">

<?php
$rooms=mysqli_query($conn,"SELECT * FROM rooms");

while($r=mysqli_fetch_assoc($rooms)){
?>

<option value="<?php echo $r['id']; ?>">
<?php echo $r['room_type']; ?>
</option>

<?php } ?>

</select>

<br><br>

Package Name
<input type="text" name="package_name">

<br><br>

Extra Price
<input type="number" name="extra_price">

<br><br>

<button name="add_package">Add Package</button>

</form>