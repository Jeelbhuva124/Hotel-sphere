<?php
include("../config.php");

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM room WHERE id='$id'"));

if(isset($_POST['update'])){

$room_type = $_POST['room_type'];
$price = $_POST['price'];

mysqli_query($conn,"UPDATE room SET room_type='$room_type', price='$price' WHERE id='$id'");

echo "<script>alert('Updated');window.location='manage_rooms.php';</script>";
}
?>

<form method="POST">
<input type="text" name="room_type" value="<?php echo $data['room_type']; ?>">
<input type="number" name="price" value="<?php echo $data['price']; ?>">
<button name="update">Update</button>
</form>
