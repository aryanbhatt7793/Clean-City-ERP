<?php
include "includes/auth.php";
if($user['role']!="seller"){
    header("Location: login.php");
    exit();
}

/* ===== Fetch Seller Stats ===== */

$totalProducts = $conn->query("
    SELECT COUNT(*) as c
    FROM seller_products
    WHERE seller_id=".$user['id']
)->fetch_assoc()['c'];

$totalStock = $conn->query("
    SELECT SUM(quantity) as q
    FROM seller_products
    WHERE seller_id=".$user['id']
)->fetch_assoc()['q'];

$totalValue = $conn->query("
    SELECT SUM(points_price * quantity) as v
    FROM seller_products
    WHERE seller_id=".$user['id']
)->fetch_assoc()['v'];

if(!$totalStock) $totalStock = 0;
if(!$totalValue) $totalValue = 0;
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
    <div><strong>Seller Dashboard</strong></div>
    <div>
        <a href="seller_products.php">My Products</a>
        <a href="add_product.php">Add Product</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<div class="dashboard-header">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
    <span style="color:#555;">Manage your recycled products business</span>
</div>

<!-- ===== STATS ===== -->
<div class="stats-grid">

    <div class="stat-card">
        📦
        <div class="stat-number"><?php echo $totalProducts; ?></div>
        <div>Total Products</div>
    </div>

    <div class="stat-card">
        📊
        <div class="stat-number"><?php echo $totalStock; ?></div>
        <div>Total Stock Available</div>
    </div>

    <div class="stat-card">
        💎
        <div class="stat-number"><?php echo $totalValue; ?></div>
        <div>Potential Points Value</div>
    </div>

</div>

<!-- ===== ACTIONS ===== -->
<div class="action-grid">

    <div class="action-card">
        <h3>➕ Add New Product</h3>
        <p>Upload recycled items and make them available in the marketplace.</p>
        <a href="add_product.php" class="action-btn">Add Product</a>
    </div>

    <div class="action-card">
        <h3>🛒 Manage My Products</h3>
        <p>Edit pricing, update stock, or monitor your listed products.</p>
        <a href="seller_products.php" class="action-btn">View Products</a>
    </div>

</div>

</div>