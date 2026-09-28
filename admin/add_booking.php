<?php
session_start();
include("../config.php");

if(!isset($_SESSION['admin'])){
 header("Location: login.php");
 exit();
}

/* DELETE BOOKING */
if(isset($_GET['delete'])){
 $id = intval($_GET['delete']);
 $stmt = $conn->prepare("DELETE FROM bookings WHERE id=?");
 $stmt->bind_param("i", $id);
 $stmt->execute();
 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/* ADD BOOKING */
if(isset($_POST['add_booking'])){
 $hotel_id = intval($_POST['hotel_id']);
 $user_name = $conn->real_escape_string($_POST['user_name']);
 $user_email = $conn->real_escape_string($_POST['user_email']);
 $checkin_date = $_POST['checkin_date'];
 $checkout_date = $_POST['checkout_date'];
 $rooms = intval($_POST['rooms']);
 $room_no = $conn->real_escape_string($_POST['room_no']);
 $room_type = $conn->real_escape_string($_POST['room_type']);
 $price = floatval($_POST['price']);
 $status = $_POST['status'];

 $stmt = $conn->prepare("INSERT INTO bookings 
 (hotel_id, user_name, user_email, checkin_date, checkout_date, rooms, room_no, room_type, price, status) 
 VALUES (?,?,?,?,?,?,?,?,?,?)");
 $stmt->bind_param("isssiissds",
 $hotel_id, $user_name, $user_email, $checkin_date, $checkout_date,
 $rooms, $room_no, $room_type, $price, $status
 );
 $stmt->execute();
 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/* UPDATE BOOKING */
if(isset($_POST['update_booking'])){
 $id = intval($_POST['id']);
 $hotel_id = intval($_POST['hotel_id']);
 $user_name = $conn->real_escape_string($_POST['user_name']);
 $user_email = $conn->real_escape_string($_POST['user_email']);
 $checkin_date = $_POST['checkin_date'];
 $checkout_date = $_POST['checkout_date'];
 $rooms = intval($_POST['rooms']);
 $room_no = $conn->real_escape_string($_POST['room_no']);
 $room_type = $conn->real_escape_string($_POST['room_type']);
 $price = floatval($_POST['price']);
 $status = $_POST['status'];

 $stmt = $conn->prepare("UPDATE bookings SET 
 hotel_id=?, user_name=?, user_email=?, checkin_date=?, checkout_date=?, rooms=?, room_no=?, room_type=?, price=?, status=? 
 WHERE id=?");
 $stmt->bind_param("isssiissdsi",
 $hotel_id, $user_name, $user_email, $checkin_date, $checkout_date,
 $rooms, $room_no, $room_type, $price, $status, $id
 );
 $stmt->execute();
 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/* FETCH BOOKINGS */
$result = $conn->query("
 SELECT b.*, h.name AS hotel_name 
 FROM bookings b
 LEFT JOIN hotels h ON b.hotel_id = h.id
 ORDER BY b.id DESC
");

/* FETCH HOTELS FOR FORM */
$hotels = $conn->query("SELECT id, name FROM hotels ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
 <title>Manage Bookings</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 ml-64 p-8">
 <div class="flex justify-between mb-8">
 <h1 class="text-3xl font-bold">Manage Bookings</h1>
 <button onclick="openAddModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg">
  Add Booking
 </button>
 </div>

 <div class="bg-white rounded-xl shadow overflow-hidden">
 <table class="w-full text-left">
  <thead class="bg-gray-200">
  <tr>
   <th class="p-4">Hotel</th>
   <th class="p-4">User Name</th>
   <th class="p-4">Email</th>
   <th class="p-4">Check-in</th>
   <th class="p-4">Check-out</th>
   <th class="p-4">Rooms</th>
   <th class="p-4">Room No</th>
   <th class="p-4">Room Type</th>
   <th class="p-4">Price</th>
   <th class="p-4">Status</th>
   <th class="p-4 text-center">Action</th>
  </tr>
  </thead>
  <tbody class="divide-y">
  <?php while($row = $result->fetch_assoc()): ?>
  <tr>
   <td class="p-4"><?= htmlspecialchars($row['hotel_name']) ?></td>
   <td class="p-4"><?= htmlspecialchars($row['user_name']) ?></td>
   <td class="p-4"><?= htmlspecialchars($row['user_email']) ?></td>
   <td class="p-4"><?= $row['checkin_date'] ?></td>
   <td class="p-4"><?= $row['checkout_date'] ?></td>
   <td class="p-4"><?= $row['rooms'] ?></td>
   <td class="p-4"><?= $row['room_no'] ?></td>
   <td class="p-4"><?= $row['room_type'] ?></td>
   <td class="p-4">₹ <?= $row['price'] ?></td>
   <td class="p-4"><?= $row['status'] ?></td>
   <td class="p-4 text-center">
   <div class="flex justify-center gap-2">
    <button
    class="editBtn bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm shadow transition duration-200"
    data-id="<?= $row['id'] ?>"
    data-hotel_id="<?= $row['hotel_id'] ?>"
    data-user_name="<?= htmlspecialchars($row['user_name']) ?>"
    data-user_email="<?= htmlspecialchars($row['user_email']) ?>"
    data-checkin_date="<?= $row['checkin_date'] ?>"
    data-checkout_date="<?= $row['checkout_date'] ?>"
    data-rooms="<?= $row['rooms'] ?>"
    data-room_no="<?= htmlspecialchars($row['room_no']) ?>"
    data-room_type="<?= $row['room_type'] ?>"
    data-price="<?= $row['price'] ?>"
    data-status="<?= $row['status'] ?>"
    >
    Edit
    </button>
    <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')"
    class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded-lg text-sm shadow transition duration-200">
    Delete
    </a>
   </div>
   </td>
  </tr>
  <?php endwhile; ?>
  </tbody>
 </table>
 </div>
</div>

<!-- Modal -->
<div id="bookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
 <div class="bg-white p-6 rounded-xl w-full max-w-lg">
 <h2 id="modalTitle" class="text-xl font-bold mb-4"></h2>

 <form method="POST" class="space-y-3">
  <input type="hidden" name="id" id="booking_id">

  <!-- Hotel -->
  <select name="hotel_id" id="hotel_id" class="w-full p-2 border rounded" required>
  <option value="">Select Hotel</option>
  <?php while($h = $hotels->fetch_assoc()): ?>
   <option value="<?= $h['id'] ?>"><?= htmlspecialchars($h['name']) ?></option>
  <?php endwhile; ?>
  </select>

  <input type="text" name="user_name" id="user_name" placeholder="User Name" class="w-full p-2 border rounded" required>
  <input type="email" name="user_email" id="user_email" placeholder="User Email" class="w-full p-2 border rounded" required>

  <div class="flex gap-2">
  <input type="date" name="checkin_date" id="checkin_date" class="w-full p-2 border rounded" required>
  <input type="date" name="checkout_date" id="checkout_date" class="w-full p-2 border rounded" required>
  </div>

  <div class="flex gap-2">
  <input type="number" name="rooms" id="rooms" placeholder="Number of Rooms" class="w-full p-2 border rounded" min="1" required>
  <input type="text" name="room_no" id="room_no" placeholder="Room No" class="w-full p-2 border rounded" required>
  </div>

  <select name="room_type" id="room_type" class="w-full p-2 border rounded" required>
  <option value="Standard">Standard</option>
  <option value="Deluxe">Deluxe</option>
  <option value="Suite">Suite</option>
  </select>

  <div class="flex gap-2">
  <input type="number" step="0.01" name="price" id="price" placeholder="Price" class="w-full p-2 border rounded" required>
  <select name="status" id="status" class="w-full p-2 border rounded">
   <option value="Pending">Pending</option>
   <option value="Confirmed">Confirmed</option>
   <option value="Cancelled">Cancelled</option>
  </select>
  </div>

  <div class="flex justify-end gap-3 pt-3">
  <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-500">Cancel</button>
  <button type="submit" name="add_booking" id="addBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
  <button type="submit" name="update_booking" id="updateBtn" class="hidden bg-green-600 text-white px-4 py-2 rounded">Update</button>
  </div>
 </form>
 </div>
</div>

<script>
// Modal functions
function openAddModal(){
 document.getElementById('modalTitle').innerText="Add Booking";
 document.getElementById('bookingModal').classList.remove('hidden');
 document.getElementById('addBtn').classList.remove('hidden');
 document.getElementById('updateBtn').classList.add('hidden');
}

function closeModal(){
 document.getElementById('bookingModal').classList.add('hidden');
}

// Edit booking
document.querySelectorAll('.editBtn').forEach(btn=>{
 btn.addEventListener('click',function(){
 document.getElementById('modalTitle').innerText="Edit Booking";
 document.getElementById('bookingModal').classList.remove('hidden');
 document.getElementById('addBtn').classList.add('hidden');
 document.getElementById('updateBtn').classList.remove('hidden');

 document.getElementById('booking_id').value = this.dataset.id;
 document.getElementById('hotel_id').value = this.dataset.hotel_id;
 document.getElementById('user_name').value = this.dataset.user_name;
 document.getElementById('user_email').value = this.dataset.user_email;
 document.getElementById('checkin_date').value = this.dataset.checkin_date;
 document.getElementById('checkout_date').value = this.dataset.checkout_date;
 document.getElementById('rooms').value = this.dataset.rooms;
 document.getElementById('room_no').value = this.dataset.room_no;
 document.getElementById('room_type').value = this.dataset.room_type;
 document.getElementById('price').value = this.dataset.price;
 document.getElementById('status').value = this.dataset.status;
 });
});
</script>

</body>
</html>