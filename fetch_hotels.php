<?php
include("config.php");

// Fetch all active hotels with city and state names
$hotels_query = mysqli_query($conn,"
 SELECT hotels.*, cities.city_name, states.state_name
 FROM hotels
 JOIN cities ON hotels.city_id = cities.id
 JOIN states ON cities.state_id = states.id
 WHERE hotels.status='active'
 ORDER BY states.state_name, cities.city_name, hotels.hotel_name
");
?>