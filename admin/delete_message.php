<?php
include "config.php";

$id = $_GET['id'];

$conn->query("DELETE FROM contact_messages WHERE id=$id");

header("Location: contact_view.php");
?>
