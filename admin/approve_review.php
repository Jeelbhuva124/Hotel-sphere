<?php
include("config.php");
$id = intval($_GET['id']);
$conn->query("UPDATE haridwar_reviews SET status='approved' WHERE id=$id");
header("Location: admin_reviews.php");
?>