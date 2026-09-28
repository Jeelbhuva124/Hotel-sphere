<?php
include "config.php";

$result = $conn->query("SELECT * FROM contact_messages ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact Messages</title>
<script src="https://cdn.tailwindcss.com"></script>
 <link rel="stylesheet" href="/Hotel-sphere/style.css">
</head>

<body class="bg-gray-100 p-10">

<h1 class="text-3xl font-bold text-center mb-8">
 Contact Messages
</h1>

<div class="bg-white shadow rounded p-6">

<table class="w-full border">

<tr class="bg-primary text-white">
 <th class="p-3">ID</th>
 <th>Name</th>
 <th>Email</th>
 <th>Message</th>
 <th>Date</th>
 <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr class="border">
 <td class="p-3"><?= $row['id'] ?></td>
 <td><?= $row['name'] ?></td>
 <td><?= $row['email'] ?></td>
 <td><?= $row['message'] ?></td>
 <td><?= $row['created_at'] ?></td>

 <td>
 <a href="delete_message.php?id=<?= $row['id'] ?>"
  class="bg-red-500 text-white px-3 py-1 rounded">
  Delete
 </a>
 </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>
