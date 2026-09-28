<?php include "config.php";

$uid=$_SESSION['user']['id'];

$res=$conn->query("
SELECT hotels.name FROM bookings
JOIN hotels ON bookings.hotel_id=hotels.id
WHERE user_id=$uid
");

echo "<h2>My Bookings</h2>";

while($b=$res->fetch_assoc()){
echo "<p>".$b['name']."</p>";
}
?>
