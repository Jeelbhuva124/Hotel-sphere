<?php
include("config.php");

$statusFilter = "";

if(isset($_GET['status'])){
 $status = $_GET['status'];
 $statusFilter = "WHERE status='$status'";
}

$countAll = $conn->query("SELECT COUNT(*) as total FROM haridwar_reviews")->fetch_assoc()['total'];
$countPending = $conn->query("SELECT COUNT(*) as total FROM haridwar_reviews WHERE status='pending'")->fetch_assoc()['total'];
$countApproved = $conn->query("SELECT COUNT(*) as total FROM haridwar_reviews WHERE status='approved'")->fetch_assoc()['total'];

$result = $conn->query("SELECT * FROM haridwar_reviews $statusFilter ORDER BY id DESC");
?>

<h2>Review Management</h2>

<a href="admin_reviews.php">All (<?php echo $countAll; ?>)</a> |
<a href="admin_reviews.php?status=pending">Pending (<?php echo $countPending; ?>)</a> |
<a href="admin_reviews.php?status=approved">Approved (<?php echo $countApproved; ?>)</a>

<hr><br>

<?php while($row = $result->fetch_assoc()){ ?>

<div style="border:1px solid #ccc;padding:10px;margin-bottom:10px;">

<p><b>Name:</b> <?php echo $row['name']; ?></p>
<p><b>Rating:</b> <?php echo $row['rating']; ?>/5</p>
<p><b>Review:</b> <?php echo $row['review']; ?></p>
<p><b>Status:</b> <?php echo $row['status']; ?></p>

<?php if($row['status']=='pending'){ ?>
<a href="approve_review.php?id=<?php echo $row['id']; ?>">Approve</a>
<?php } ?>

<a href="delete_review.php?id=<?php echo $row['id']; ?>">Delete</a>

</div>

<?php } ?>
