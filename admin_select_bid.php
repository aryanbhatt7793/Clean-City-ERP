<?php
include "includes/auth.php";

if($user['role'] != "admin"){
    header("Location: login.php");
    exit();
}

$listing_id = intval($_GET['listing'] ?? 0);

if(!$listing_id){
    die("Invalid listing.");
}

/* Fetch Bids */
$stmt = $conn->prepare("
    SELECT bids.*, users.name 
    FROM bids
    JOIN users ON bids.bidder_id = users.id
    WHERE listing_id = ?
    ORDER BY amount ASC
");
$stmt->bind_param("i",$listing_id);
$stmt->execute();
$bids = $stmt->get_result();

$success = $_GET['success'] ?? "";
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
body{
    font-family:'Segoe UI',sans-serif;
    background:#f1f5f9;
    margin:0;
}

.section{
    padding:50px 10%;
}

h2{
    margin-bottom:30px;
}

.bid-card{
    background:white;
    padding:25px;
    border-radius:15px;
    margin-bottom:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:0.3s;
}

.bid-card:hover{
    transform:translateY(-5px);
}

.bid-info{
    font-size:15px;
}

.amount{
    font-size:18px;
    font-weight:600;
    color:#2563eb;
}

.select-btn{
    background:#2563eb;
    color:white;
    padding:10px 18px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.select-btn:hover{
    background:#1e40af;
}

.success-box{
    background:#e6ffed;
    color:#0a7a33;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
}
.lowest-badge{
    background:#dbeafe;
    color:#1d4ed8;
    padding:4px 8px;
    font-size:12px;
    border-radius:6px;
    margin-left:10px;
}
</style>

<div class="section">

<h2>🏆 Select Winning Bid</h2>

<?php if($success){ ?>
<div class="success-box">
    Winner selected successfully!
</div>
<?php } ?>

<?php 
$first = true;
while($row = $bids->fetch_assoc()){ 
?>

<div class="bid-card">

    <div class="bid-info">
        <strong><?php echo htmlspecialchars($row['name']); ?></strong>
        <?php if($first){ ?>
            <span class="lowest-badge">Lowest Bid</span>
        <?php } ?>
        <br>
        <span class="amount">₹<?php echo number_format($row['amount'],2); ?></span>
    </div>

    <a class="select-btn"
       href="select.php?bid=<?php echo $row['id']; ?>&listing=<?php echo $listing_id; ?>"
       onclick="return confirm('Are you sure you want to select this bid as winner?');">
       Select Winner
    </a>

</div>

<?php 
$first = false;
} 
?>

<?php if($bids->num_rows == 0){ ?>
<p>No bids placed yet.</p>
<?php } ?>

</div>