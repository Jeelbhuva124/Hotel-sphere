<?php
session_start();
include("../config.php");

// Admin check
if(!isset($_SESSION['admin'])){
 header("Location: login.php");
 exit();
}

/* DELETE PACKAGE */
if(isset($_GET['delete'])){
 $id = intval($_GET['delete']);
 $stmt = $conn->prepare("DELETE FROM packages WHERE id=?");
 $stmt->bind_param("i", $id);
 $stmt->execute();
 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/* ADD PACKAGE */
if(isset($_POST['add_package'])){
 $name = $conn->real_escape_string($_POST['name']);
 $description = $conn->real_escape_string($_POST['description']);
 $price = floatval($_POST['price']);
 $duration_days = intval($_POST['duration_days']);
 $status = $_POST['status'];

 $stmt = $conn->prepare("INSERT INTO packages (name, description, price, duration_days, status) VALUES (?,?,?,?,?)");
 $stmt->bind_param("ssdii", $name, $description, $price, $duration_days, $status);
 $stmt->execute();
 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/* UPDATE PACKAGE */
if(isset($_POST['update_package'])){
 $id = intval($_POST['id']);
 $name = $conn->real_escape_string($_POST['name']);
 $description = $conn->real_escape_string($_POST['description']);
 $price = floatval($_POST['price']);
 $duration_days = intval($_POST['duration_days']);
 $status = $_POST['status'];

 $stmt = $conn->prepare("UPDATE packages SET name=?, description=?, price=?, duration_days=?, status=? WHERE id=?");
 $stmt->bind_param("ssdiii", $name, $description, $price, $duration_days, $status, $id);
 $stmt->execute();
 header("Location: ".$_SERVER['PHP_SELF']);
 exit();
}

/* FETCH PACKAGES */
$result = $conn->query("SELECT * FROM packages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
 <title>Manage Packages</title>
 <script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/HotelManagement/style.css">
</head>
<body class="bg-gray-100 flex">

<?php include("sidebar.php"); ?>

<div class="flex-1 ml-64 p-8">
 <div class="flex justify-between mb-8">
 <h1 class="text-3xl font-bold">Manage Packages</h1>
 <button onclick="openAddModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg">
  Add Package
 </button>
 </div>

 <div class="bg-white rounded-xl shadow overflow-x-auto">
 <table class="w-full text-left">
  <thead class="bg-gray-200">
  <tr>
   <th class="p-4">Name</th>
   <th class="p-4">Description</th>
   <th class="p-4">Price</th>
   <th class="p-4">Duration (Days)</th>
   <th class="p-4">Status</th>
   <th class="p-4 text-center">Action</th>
  </tr>
  </thead>
  <tbody class="divide-y">
  <?php while($row = $result->fetch_assoc()): ?>
  <tr>
   <td class="p-4"><?= htmlspecialchars($row['name']) ?></td>
   <td class="p-4"><?= htmlspecialchars($row['description']) ?></td>
   <td class="p-4">₹ <?= $row['price'] ?></td>
   <td class="p-4"><?= $row['duration_days'] ?></td>
   <td class="p-4">
   <?php
   if($row['status']=="Active") echo "<span class='bg-green-200 text-success px-2 py-1 rounded'>Active</span>";
   else echo "<span class='bg-gray-200 text-gray-800 px-2 py-1 rounded'>Inactive</span>";
   ?>
   </td>
   <td class="p-4 text-center">
   <div class="flex justify-center gap-2">
    <button
    class="editBtn bg-blue-500 hover:bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm shadow transition duration-200"
    data-id="<?= $row['id'] ?>"
    data-name="<?= htmlspecialchars($row['name']) ?>"
    data-description="<?= htmlspecialchars($row['description']) ?>"
    data-price="<?= $row['price'] ?>"
    data-duration_days="<?= $row['duration_days'] ?>"
    data-status="<?= $row['status'] ?>"
    >Edit</button>
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
<div id="packageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
 <div class="bg-white p-6 rounded-xl w-full max-w-lg">
 <h2 id="modalTitle" class="text-xl font-bold mb-4"></h2>
 <form method="POST" class="space-y-3">
  <input type="hidden" name="id" id="package_id">

  <input type="text" name="name" id="name" placeholder="Package Name" class="w-full p-2 border rounded" required>
  <textarea name="description" id="description" placeholder="Description" class="w-full p-2 border rounded" required></textarea>
  <input type="number" step="0.01" name="price" id="price" placeholder="Price" class="w-full p-2 border rounded" required>
  <input type="number" name="duration_days" id="duration_days" placeholder="Duration (Days)" class="w-full p-2 border rounded" min="1" required>

  <select name="status" id="status" class="w-full p-2 border rounded">
  <option value="Active">Active</option>
  <option value="Inactive">Inactive</option>
  </select>

  <div class="flex justify-end gap-3 pt-3">
  <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-500">Cancel</button>
  <button type="submit" name="add_package" id="addBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
  <button type="submit" name="update_package" id="updateBtn" class="hidden bg-green-600 text-white px-4 py-2 rounded">Update</button>
  </div>
 </form>
 </div>
</div>

<script>
function openAddModal(){
 document.getElementById('modalTitle').innerText="Add Package";
 document.getElementById('packageModal').classList.remove('hidden');
 document.getElementById('addBtn').classList.remove('hidden');
 document.getElementById('updateBtn').classList.add('hidden');
}

function closeModal(){
 document.getElementById('packageModal').classList.add('hidden');
}

document.querySelectorAll('.editBtn').forEach(btn=>{
 btn.addEventListener('click',function(){
 document.getElementById('modalTitle').innerText="Edit Package";
 document.getElementById('packageModal').classList.remove('hidden');
 document.getElementById('addBtn').classList.add('hidden');
 document.getElementById('updateBtn').classList.remove('hidden');

 document.getElementById('package_id').value = this.dataset.id;
 document.getElementById('name').value = this.dataset.name;
 document.getElementById('description').value = this.dataset.description;
 document.getElementById('price').value = this.dataset.price;
 document.getElementById('duration_days').value = this.dataset.duration_days;
 document.getElementById('status').value = this.dataset.status;
 });
});
</script>

</body>
</html>