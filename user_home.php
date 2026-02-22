<?php include "includes/auth.php"; ?>
<?php if($user['role']!="user"){ header("Location: login.php"); exit(); } ?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
.hero {
    background: linear-gradient(135deg,#2c3e50,#4ca1af);
    color:white;
    padding:40px;
    border-radius:15px;
    margin-bottom:30px;
}

.stats {
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    margin-top:20px;
}

.stat-card {
    flex:1;
    min-width:180px;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.06);
    text-align:center;
}

.dashboard-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.card:hover {
    transform:translateY(-5px);
    transition:0.3s;
}
</style>

<div class="navbar">
    <div><strong>Smart Waste Platform</strong></div>
    <div>
        Points: <strong><?php echo $user['points']; ?></strong>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<!-- HERO SECTION -->
<div class="hero">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
    <p>
        Contribute to a cleaner city. Report waste, earn points,
        and help prevent illegal dumping in your area.
    </p>
</div>

<!-- QUICK STATS -->
<div class="stats">

<?php
/* Count user complaints */
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM complaints WHERE user_id=?");
$stmt->bind_param("i",$user['id']);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$complaint_count = $res['total'];
?>

<div class="stat-card">
    <h3><?php echo $complaint_count; ?></h3>
    <p>Total Complaints</p>
</div>

<div class="stat-card">
    <h3><?php echo $user['points']; ?></h3>
    <p>Reward Points</p>
</div>

<div class="stat-card">
    <h3>Active</h3>
    <p>Account Status</p>
</div>

</div>

<h3 style="margin-top:40px;">Quick Actions</h3>

<!-- DASHBOARD CARDS -->
<div class="dashboard-grid">

    <div class="card">
        <h3>📤 Upload Complaint</h3>
        <p>Report illegal dumping in your locality.</p>
        <a href="upload_complaint.php" class="btn">Report Now</a>
    </div>

    <div class="card">
        <h3>📦 My Orders</h3>
        <p>Track your redeemed products.</p>
        <a href="order_history.php" class="btn">View Orders</a>
    </div>

    <div class="card">
        <h3>📍 Track Complaints</h3>
        <p>Monitor status of your reports.</p>
        <a href="my_complaints.php" class="btn">Track</a>
    </div>

    <div class="card">
        <h3>🛒 Marketplace</h3>
        <p>Redeem eco-points for rewards.</p>
        <a href="marketplace.php" class="btn">Explore</a>
    </div>

    <div class="card">
        <h3>🔮 Risk Predictor</h3>
        <p>Check future dumping risk in your area.</p>
        <a href="risk.php" class="btn">Check Risk</a>
    </div>

</div>

</div>