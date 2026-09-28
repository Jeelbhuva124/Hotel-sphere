<?php
session_start();
include("../config.php");

if(!isset($_SESSION['admin'])){
 header("Location: login.php");
 exit();
}

/*DELETE ROOM*/
if(isset($_GET['delete'])){
 $id = intval($_GET['delete']);

 $stmt = $conn->prepare("SELECT image FROM rooms WHERE id=?");
 $stmt->bind_param("i",$id);
 $stmt->execute();
 $resultImg = $stmt->get_result();
 $img = $resultImg->fetch_assoc();

 if($img && $img['image'] != "" && file_exists("../uploads/".$img['image'])){
 unlink("../uploads/".$img['image']);
 }

 $stmt = $conn->prepare("DELETE FROM rooms WHERE id=?");
 $stmt->bind_param("i",$id);
 $stmt->execute();

 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/*ADD ROOM*/
if(isset($_POST['add_room'])){
 $imageName = "";

 if(!empty($_FILES['image']['name'])){
 $imageName = time() . "_" . $_FILES['image']['name'];
 move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$imageName);
 }

 $stmt = $conn->prepare("INSERT INTO rooms (room_no, category_id, price, location, status, image) VALUES (?, ?, ?, ?, ?, ?)");
 $stmt->bind_param("siisss",
 $_POST['room_no'],
 $_POST['category_id'],
 $_POST['price'],
 $_POST['location'],
 $_POST['status'],
 $imageName
 );
 $stmt->execute();

 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/*UPDATE ROOM*/
if(isset($_POST['update_room'])){
 $id = intval($_POST['id']);
 $imageName = $_POST['old_image'];

 if(!empty($_FILES['image']['name'])){
 $imageName = time() . "_" . $_FILES['image']['name'];
 move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$imageName);
 }

 $stmt = $conn->prepare("UPDATE rooms SET room_no=?, category_id=?, price=?, location=?, status=?, image=? WHERE id=?");
 $stmt->bind_param("siisssi",
 $_POST['room_no'],
 $_POST['category_id'],
 $_POST['price'],
 $_POST['location'],
 $_POST['status'],
 $imageName,
 $id
 );
 $stmt->execute();

 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/*FETCH ROOMS*/
$result = $conn->query("
SELECT rooms.*, categories.category_name 
FROM rooms
LEFT JOIN categories ON rooms.category_id = categories.id
ORDER BY rooms.id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
 <title>Manage Rooms</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>
<body class="bg-gray-100 flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 ml-64 p-8">
 <div class="flex justify-between mb-8">
 <h1 class="text-3xl font-bold">Manage Rooms</h1>
 <button onclick="openAddModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg">
  Add Room
 </button>
 </div>

 <div class="bg-white rounded-xl shadow overflow-hidden">
 <table class="w-full text-left">
  <thead class="bg-gray-200">
  <tr>
   <th class="p-4">Room No</th>
   <th class="p-4">Image</th>
   <th class="p-4">Category</th>
   <th class="p-4">Price</th>
   <th class="p-4">Status</th>
   <th class="p-4 text-center">Action</th>
  </tr>
  </thead>
  <tbody class="divide-y">
  <?php while($row = $result->fetch_assoc()): ?>
  <tr>
   <td class="p-4"><?= $row['room_no'] ?></td>
   <td class="p-4">
   <?php if($row['image']): ?>
    <img src="../uploads/<?= $row['image'] ?>" class="w-16 h-16 object-cover rounded">
   <?php endif; ?>
   </td>
   <td class="p-4"><?= $row['category_name'] ?></td>
   <td class="p-4">₹ <?= $row['price'] ?></td>
   <td class="p-4"><?= $row['status'] ?></td>
   <td class="p-4 text-center">
   <div class="flex justify-center gap-2">
    <!-- Edit Button with data attributes -->
    <button
    class="editBtn bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm shadow transition duration-200"
    data-id="<?= $row['id'] ?>"
    data-room_no="<?= htmlspecialchars($row['room_no']) ?>"
    data-category_id="<?= $row['category_id'] ?>"
    data-price="<?= $row['price'] ?>"
    data-location="<?= htmlspecialchars($row['location']) ?>"
    data-status="<?= $row['status'] ?>"
    data-image="<?= $row['image'] ?>"
    >
    Edit
    </button>

    <!-- Delete Button -->
    <a href="?delete=<?= $row['id'] ?>"
    onclick="return confirm('Are you sure you want to delete this room?')"
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
<div id="roomModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
 <div class="bg-white p-6 rounded-xl w-full max-w-md">
 <h2 id="modalTitle" class="text-xl font-bold mb-4"></h2>

 <form method="POST" enctype="multipart/form-data" class="space-y-3">
  <input type="hidden" name="id" id="room_id">
  <input type="hidden" name="old_image" id="old_image">

  <input type="text" name="room_no" id="room_no" placeholder="Room No" class="w-full p-2 border rounded" required>

  <select name="category_id" id="category_id" class="w-full p-2 border rounded" required>
  <option value="">Select Category</option>
  <?php
  $cat = $conn->query("SELECT * FROM categories");
  while($c = $cat->fetch_assoc()):
  ?>
   <option value="<?= $c['id'] ?>"><?= $c['category_name'] ?></option>
  <?php endwhile; ?>
  </select>

  <input type="number" name="price" id="price" placeholder="Price" class="w-full p-2 border rounded" required>
  <input type="text" name="location" id="location" placeholder="Location" class="w-full p-2 border rounded" required>

  <select name="status" id="status" class="w-full p-2 border rounded">
  <option value="Available">Available</option>
  <option value="Booked">Booked</option>
  </select>

  <input type="file" name="image" class="w-full">

  <div class="flex justify-end gap-3 pt-3">
  <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-500">Cancel</button>
  <button type="submit" name="add_room" id="addBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
  <button type="submit" name="update_room" id="updateBtn" class="hidden bg-green-600 text-white px-4 py-2 rounded">Update</button>
  </div>
 </form>
 </div>
</div>

<script>
// Open Add Modal
function openAddModal(){
 document.getElementById('modalTitle').innerText="Add Room";
 document.getElementById('roomModal').classList.remove('hidden');
 document.getElementById('addBtn').classList.remove('hidden');
 document.getElementById('updateBtn').classList.add('hidden');
}

// Close Modal
function closeModal(){
 document.getElementById('roomModal').classList.add('hidden');
}

// Open Edit Modal using data attributes
document.querySelectorAll('.editBtn').forEach(btn => {
 btn.addEventListener('click', function() {
 document.getElementById('modalTitle').innerText = "Edit Room";
 document.getElementById('roomModal').classList.remove('hidden');
 document.getElementById('addBtn').classList.add('hidden');
 document.getElementById('updateBtn').classList.remove('hidden');

 document.getElementById('room_id').value = this.dataset.id;
 document.getElementById('room_no').value = this.dataset.room_no;
 document.getElementById('category_id').value = this.dataset.category_id;
 document.getElementById('price').value = this.dataset.price;
 document.getElementById('location').value = this.dataset.location;
 document.getElementById('status').value = this.dataset.status;
 document.getElementById('old_image').value = this.dataset.image;
 });
});
</script>

</body>
</html>
