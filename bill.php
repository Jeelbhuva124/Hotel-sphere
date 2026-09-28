<?php
include("config.php");

$bill_id = $_GET['bill_id'] ?? 0;

$result = mysqli_query($conn,"SELECT * FROM bill WHERE id='$bill_id'");

if(!$result){
 die("Query Error: " . mysqli_error($conn));
}

$data = mysqli_fetch_assoc($result);

if(!$data){
 die("No Bill Found!");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>

<style>
body{font-family:Arial;background:#f5f5f5;}
.bill{
width:600px;
margin:40px auto;
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 0 10px #ccc;
}
h2{text-align:center;}
.row{display:flex;justify-content:space-between;margin:10px 0;}
.total{font-size:20px;font-weight:bold;}
.print{text-align:center;margin-top:20px;}
button{padding:10px 20px;background:#0071c2;color:white;border:none;border-radius:5px;}
</style>

 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>

<body>

<div class="bill">

<h2>Hotel Invoice</h2>

<div class="row">
<span>Customer:</span>
<span><?php echo $data['user_name'] ?? 'N/A'; ?></span>
</div>

<div class="row">
<span>Hotel:</span>
<span><?php echo $data['hotel_name'] ?? 'N/A'; ?></span>
</div>

<div class="row">
<span>Payment:</span>
<span><?php echo $data['payment_method'] ?? 'N/A'; ?></span>
</div>

<div class="row total">
<span>Total:</span>
<span>₹ <?php echo $data['total_amount'] ?? '0'; ?></span>
</div>

<div class="row">
<span>Date:</span>
<span><?php echo $data['created_at'] ?? 'N/A'; ?></span>
</div>

<div class="print">
<button onclick="window.print()">Print Bill</button>
</div>

</div>
<?php include("footer.php"); ?>
</body>
</html>