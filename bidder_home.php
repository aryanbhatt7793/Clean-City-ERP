<?php
include "includes/auth.php";
if($user['role']!="bidder"){
    header("Location: login.php");
    exit();
}

/* ===== Fetch Stats ===== */

$totalBids = $conn->query("SELECT COUNT(*) as c FROM bids WHERE bidder_id=".$user['id'])->fetch_assoc()['c'];

$wonBids = $conn->query("SELECT COUNT(*) as c FROM bids WHERE bidder_id=".$user['id']." AND status='Selected'")->fetch_assoc()['c'];

$activeListings = $conn->query("SELECT COUNT(*) as c FROM waste_listings WHERE status='Open'")->fetch_assoc()['c'];
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
.dashboard-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.dashboard-header h2 {
    font-size:28px;
}

.stats-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:40px;
}

.stat-card {
    background:white;
    padding:25px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.06);
    transition:0.3s;
}

.stat-card:hover {
    transform:translateY(-5px);
    box-shadow:0 20px 40px rgba(0,0,0,0.08);
}

.stat-number {
    font-size:30px;
    font-weight:bold;
    margin-top:10px;
}

.action-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:25px;
}

.action-card {
    background:white;
    padding:30px;
    border-radius:18px;
    box-shadow:0 15px 35px rgba(0,0,0,0.06);
    transition:0.3s ease;
}

.action-card:hover {
    transform:translateY(-6px);
    box-shadow:0 25px 45px rgba(0,0,0,0.1);
}

.action-card h3 {
    margin-bottom:10px;
}

.action-card p {
    color:#555;
    font-size:14px;
    margin-bottom:20px;
}

.action-btn {
    display:inline-block;
    padding:10px 20px;
    background:#2563eb;
    color:white;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
    transition:0.2s;
}

.action-btn:hover {
    background:#1d4ed8;
}
</style>

<div class="navbar">
    <div><strong>Bidder Dashboard</strong></div>
    <div>
        <a href="bid.php">Live Auctions</a>
        <a href="my_bids.php">My Bids</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<div class="dashboard-header">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
    <span style="color:#555;">Participate in active waste auctions</span>
</div>

<!-- ===== STATS ===== -->
<div class="stats-grid">

    <div class="stat-card">
        📦
        <div class="stat-number"><?php echo $activeListings; ?></div>
        <div>Active Auctions</div>
    </div>

    <div class="stat-card">
        🔨
        <div class="stat-number"><?php echo $totalBids; ?></div>
        <div>Total Bids Placed</div>
    </div>

    <div class="stat-card">
        🏆
        <div class="stat-number"><?php echo $wonBids; ?></div>
        <div>Auctions Won</div>
    </div>

</div>

<!-- ===== ACTION CARDS ===== -->
<div class="action-grid">

    <div class="action-card">
        <h3>🔎 Browse Live Auctions</h3>
        <p>View available waste listings posted by admin and place competitive bids.</p>
        <a href="bid.php" class="action-btn">Start Bidding</a>
    </div>

    <div class="action-card">
        <h3>📋 Track Your Bids</h3>
        <p>Monitor your bid status and see if you've been selected as the winner.</p>
        <a href="my_bids.php" class="action-btn">View My Bids</a>
    </div>

</div>

</div>