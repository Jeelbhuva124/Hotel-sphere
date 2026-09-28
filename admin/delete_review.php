<?php
include("config.php");
$id = intval($_GET['id']);
$conn->query("DELETE FROM haridwar_reviews WHERE id=$id");
header("Location: admin_reviews.php");
?>