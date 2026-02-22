<?php
include "includes/auth.php";
if($user['role'] != "bidder"){
    header("Location: login.php");
    exit();
}

/* PLACE BID */
if(isset($_POST['bid'])){

    $listing_id = intval($_POST['listing_id']);
    $amount = floatval($_POST['amount']);

    // Optional: Prevent bid lower than base price
    $check = $conn->prepare("SELECT base_price FROM waste_listings WHERE id=?");
    $check->bind_param("i",$listing_id);
    $check->execute();
    $base = $check->get_result()->fetch_assoc();

    if($amount < $base['base_price']){
        echo "<p style='color:red;text-align:center;'>Bid must be higher than base price!</p>";
    } else {

        $stmt = $conn->prepare("INSERT INTO bids (listing_id,bidder_id,amount) VALUES (?,?,?)");
        $stmt->bind_param("iid", $listing_id, $user['id'], $amount);
        $stmt->execute();

        echo "<p style='color:green;text-align:center;'>Bid Placed Successfully!</p>";
    }
}

$listings = $conn->query("SELECT * FROM waste_listings WHERE status='Open'");
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="navbar">
    <div><strong>Bidder Dashboard</strong></div>
    <div>
        <a href="bidder_home.php">Dashboard</a>
        <a href="my_bids.php">My Bids</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">
<h2>Available Waste Listings</h2>

<div class="grid">

<?php while($row = $listings->fetch_assoc()){ ?>

<div class="card">

    <h3><?php echo htmlspecialchars($row['title']); ?></h3>

    <p><strong>Type:</strong> <?php echo htmlspecialchars($row['waste_type']); ?></p>
    <p><strong>Weight:</strong> <?php echo $row['weight_kg']; ?> kg</p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
    <p><strong>Base Price:</strong> ₹<?php echo $row['base_price']; ?></p>

    <form method="POST">
        <input type="hidden" name="listing_id" value="<?php echo $row['id']; ?>">
        <input type="number" step="0.01" name="amount" placeholder="Enter Your Bid" required>
        <button name="bid" class="btn">Place Bid</button>
    </form>

</div>

<?php } ?>

</div>
</div>