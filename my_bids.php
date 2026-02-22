<?php
include "includes/auth.php";

if($user['role'] != "bidder"){
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT bids.*, waste_listings.title 
    FROM bids
    JOIN waste_listings ON bids.listing_id = waste_listings.id
    WHERE bidder_id=?
    ORDER BY bids.id DESC
");

$stmt->bind_param("i",$user['id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
.badge{
    display:inline-block;
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.badge-green{
    background:#16a34a;
    color:white;
}

.badge-red{
    background:#dc2626;
    color:white;
}

.badge-yellow{
    background:#f59e0b;
    color:white;
}
</style>

<div class="navbar">
    <div><strong>Bidder Dashboard</strong></div>
    <div>
        <a href="bidder_home.php">Dashboard</a>
        <a href="bid.php">Available Listings</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<h2>My Bids</h2>

<div class="grid">

<?php if($result->num_rows > 0){ ?>

<?php while($row = $result->fetch_assoc()){ ?>

<div class="card">

    <h3><?php echo htmlspecialchars($row['title']); ?></h3>

    <p><strong>Your Bid:</strong> ₹<?php echo number_format($row['amount'],2); ?></p>

    <?php if($row['status'] == "Selected"){ ?>
        <p class="badge badge-green">You Won 🎉</p>
    <?php } elseif($row['status'] == "Rejected"){ ?>
        <p class="badge badge-red">Rejected</p>
    <?php } else { ?>
        <p class="badge badge-yellow">Pending</p>
    <?php } ?>

</div>

<?php } ?>

<?php } else { ?>

<div class="card">
    <p>No bids placed yet.</p>
    <a href="bid.php" class="btn">Start Bidding</a>
</div>

<?php } ?>

</div>
</div>