<?php
include("config.php");

$search = "";

// Build query
$query = "SELECT h.*, c.city_name 
FROM hotel h
LEFT JOIN cities c ON c.id = h.city_id
WHERE h.status='approved'";

if (isset($_GET['search']) && trim($_GET['search']) != '') {
 $search = mysqli_real_escape_string($conn, $_GET['search']);
 $query .= " AND (
 c.city_name LIKE '%$search%' 
 OR h.hotel_name LIKE '%$search%'
 OR h.hotel_address LIKE '%$search%'
 )";
}

$hotels = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Rooms</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
.hotel-card{
 transition:0.3s;
}
.hotel-card:hover{
 transform:translateY(-6px);
 box-shadow:0 10px 20px rgba(0,0,0,0.1);
}

/* popup */
.popup{
 display:none;
 position:fixed;
 top:0;
 left:0;
 width:100%;
 height:100%;
 background:rgba(0,0,0,0.9);
 justify-content:center;
 align-items:center;
 z-index:999;
}

.popup img{
 max-width:80%;
 max-height:80%;
 border-radius:10px;
}

.close{
 position:absolute;
 top:20px;
 right:40px;
 font-size:30px;
 color:white;
 cursor:pointer;
}
</style>

 <link rel="stylesheet" href="/HotelManagement/style.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-transparent">

<!-- SEARCH BAR -->
<div class="max-w-5xl mx-auto px-6 mt-8 mb-4">
    <div class="bg-white rounded-2xl shadow-lg p-2 border flex justify-center">
        <form method="GET" action="" class="flex flex-col sm:flex-row gap-2 w-full">
            <!-- Hidden input to keep user on the rooms page when searching via index.php -->
            <input type="hidden" name="page" value="rooms">
            
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                    name="search"
                    placeholder="Search by city, hotel name or address..."
                    value="<?php echo htmlspecialchars($search); ?>"
                    class="w-full pl-12 pr-4 py-3 rounded-xl border-none bg-transparent focus:ring-0 shadow-none text-lg outline-none" style="box-shadow: none !important;">
            </div>
            
            <button type="submit"
                class="bg-primary text-white px-8 py-3 rounded-xl hover:bg-primary-hover transition-all font-semibold shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto p-6">

<!-- TITLE -->
<h2 class="text-xl font-bold mb-6">
<?php 
if($search != ""){
 echo "Showing results for : <span class='text-primary'>".htmlspecialchars($search)."</span>";
}
?>
</h2>

<!-- HOTELS GRID -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

<?php while($hotel = mysqli_fetch_assoc($hotels)){ ?>

<div class="hotel-card bg-white rounded-2xl overflow-hidden border flex flex-col h-full shadow-lg">

    <div class="relative overflow-hidden group">
        <img 
        src="uploads/<?php echo htmlspecialchars($hotel['image']); ?>"
        class="w-full h-56 object-cover cursor-pointer group-hover:scale-110 transition-transform duration-500"
        onclick="openPopup(this.src)">
        
        <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full shadow-md border border-white/20 text-white">
            <span class="text-yellow-400 font-bold text-sm">
                <i class="fas fa-star mr-1"></i><?php echo $hotel['rating'] ?? 0; ?>
            </span>
        </div>
    </div>

    <div class="p-6 flex flex-col flex-1">

        <h2 class="font-bold text-xl text-gray-800 mb-3">
            <?php echo htmlspecialchars($hotel['hotel_name']); ?>
        </h2>

        <p class="text-gray-500 text-sm mb-4 flex items-start">
            <i class="fas fa-map-marker-alt mt-1 mr-2 text-primary"></i>
            <span><?php echo htmlspecialchars($hotel['hotel_address']); ?>, <span class="font-semibold"><?php echo htmlspecialchars($hotel['city_name']); ?></span></span>
        </p>

        <div class="flex justify-between items-center mb-6 mt-auto">
            <span class="text-success text-sm font-semibold flex items-center bg-green-100/10 px-3 py-1 rounded-full border border-green-200/20">
                <i class="fas fa-check-circle mr-1.5"></i> Available
            </span>
        </div>

        <!-- ✅ View Rooms button always at bottom -->
        <a href="hotel_rooms.php?hotel_id=<?php echo $hotel['id']; ?>"
         class="mt-auto block text-center bg-primary text-white py-3 rounded-xl hover:bg-primary-hover transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 font-medium">
            View Rooms <i class="fas fa-arrow-right text-sm"></i>
        </a>
    </div>
</div>

<?php } ?>

</div>

</div>

<!-- IMAGE POPUP -->
<div class="popup" id="popup">
<span class="close" onclick="closePopup()">×</span>
<img id="popupImg">
</div>

<script>
function openPopup(src){
 document.getElementById("popup").style.display="flex";
 document.getElementById("popupImg").src = src;
}

function closePopup(){
 document.getElementById("popup").style.display="none";
}
</script>

</body>
</html>