<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/auth.php";

if($user['role'] != "user"){
    header("Location: login.php");
    exit();
}

$result = $conn->query("
    SELECT 
        id,
        name,
        description,
        points_required AS price,
        stock AS quantity,
        'admin' AS source
    FROM products

    UNION ALL

    SELECT 
        id,
        name,
        description,
        points_price AS price,
        quantity,
        'seller' AS source
    FROM seller_products

    ORDER BY id DESC
");
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
.market-hero {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.market-hero h2 {
    font-size:28px;
}

.points-box {
    background:#eaf2ff;
    padding:10px 18px;
    border-radius:8px;
    font-weight:600;
    color:#1e40af;
}

.product-grid {
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(260px,1fr));
    gap:25px;
}

.product-card {
    background:white;
    border-radius:16px;
    padding:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
    transition:0.3s ease;
    position:relative;
}

.product-card:hover {
    transform:translateY(-5px);
    box-shadow:0 18px 35px rgba(0,0,0,0.1);
}

.product-card h3 {
    margin-bottom:8px;
}

.product-desc {
    font-size:14px;
    color:#555;
    min-height:40px;
}

.price {
    font-size:18px;
    font-weight:bold;
    margin-top:10px;
}

.stock {
    font-size:13px;
    color:#666;
    margin-top:4px;
}

.progress-bar {
    height:6px;
    background:#eee;
    border-radius:5px;
    margin-top:6px;
    overflow:hidden;
}

.progress-fill {
    height:100%;
    background:#3b82f6;
}

.source-badge {
    position:absolute;
    top:15px;
    right:15px;
    padding:5px 10px;
    font-size:12px;
    border-radius:20px;
    font-weight:600;
}

.platform { background:#eafaf1; color:#27ae60; }
.seller { background:#fff6e5; color:#f39c12; }

.buy-btn {
    display:block;
    margin-top:15px;
    text-align:center;
    background:#2563eb;
    color:white;
    padding:10px;
    border-radius:8px;
    text-decoration:none;
    font-weight:600;
    transition:0.2s;
}

.buy-btn:hover {
    background:#1d4ed8;
}

.out-stock {
    margin-top:15px;
    text-align:center;
    padding:8px;
    border-radius:8px;
    background:#fdecea;
    color:#e74c3c;
    font-weight:600;
}
</style>

<div class="navbar">
    <div><strong>Smart Waste Marketplace</strong></div>
    <div>
        <a href="user_home.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<div class="market-hero">
    <h2>🛍️ Redeem Your Points</h2>
    <div class="points-box">
        Your Points: <?php echo $user['points']; ?>
    </div>
</div>

<div class="product-grid">

<?php while($row = $result->fetch_assoc()){ ?>

<div class="product-card">

    <?php if($row['source'] == "admin"){ ?>
        <span class="source-badge platform">Platform</span>
    <?php } else { ?>
        <span class="source-badge seller">Seller</span>
    <?php } ?>

    <h3><?php echo htmlspecialchars($row['name']); ?></h3>

    <div class="product-desc">
        <?php echo htmlspecialchars($row['description']); ?>
    </div>

    <div class="price">💎 <?php echo $row['price']; ?> Points</div>

    <div class="stock">Available: <?php echo $row['quantity']; ?></div>

    <div class="progress-bar">
        <div class="progress-fill" style="width:<?php echo min(100,$row['quantity']*10); ?>%"></div>
    </div>

    <?php if($row['quantity'] > 0){ ?>
        <a href="checkout.php?product_id=<?php echo $row['id']; ?>&source=<?php echo $row['source']; ?>" class="buy-btn">
            Redeem Now
        </a>
    <?php } else { ?>
        <div class="out-stock">Out of Stock</div>
    <?php } ?>

</div>

<?php } ?>

</div>
</div>