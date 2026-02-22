<?php
include "includes/auth.php";
if($user['role'] != "admin"){
    header("Location: login.php");
    exit();
}

/* CREATE LISTING */
if(isset($_POST['create'])){

    $stmt = $conn->prepare("INSERT INTO waste_listings 
    (title,waste_type,weight_kg,location,base_price) 
    VALUES (?,?,?,?,?)");

    $stmt->bind_param("ssdss",
        $_POST['title'],
        $_POST['waste_type'],
        $_POST['weight_kg'],
        $_POST['location'],
        $_POST['base_price']
    );

    $stmt->execute();
    echo "<p style='color:green;'>Listing Created Successfully!</p>";
}

$listings = $conn->query("SELECT * FROM waste_listings ORDER BY id DESC");
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="navbar">
    <div><strong>Admin Panel</strong></div>
    <div>
        <a href="admin_panel.php">Dashboard</a>
        <a href="admin_listings.php">Listings</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<h2>Create Waste Listing</h2>

<form method="POST">
<input type="text" name="title" placeholder="Listing Title" required><br><br>
<input type="text" name="waste_type" placeholder="Waste Type" required><br><br>
<input type="number" step="0.01" name="weight_kg" placeholder="Weight (kg)" required><br><br>
<input type="text" name="location" placeholder="Location" required><br><br>
<input type="number" step="0.01" name="base_price" placeholder="Base Price" required><br><br>
<button name="create" class="btn">Create Listing</button>
</form>

<hr>

<h3>All Listings</h3>

<?php while($row = $listings->fetch_assoc()){ ?>
<div class="card">
    <strong><?php echo $row['title']; ?></strong><br>
    Type: <?php echo $row['waste_type']; ?><br>
    Weight: <?php echo $row['weight_kg']; ?> kg<br>
    Base Price: ₹<?php echo $row['base_price']; ?><br>
    Status: <?php echo $row['status']; ?><br><br>

    <a href="admin_select_bid.php?listing=<?php echo $row['id']; ?>" class="btn">
        View Bids
    </a>
</div>
<?php } ?>

</div>