<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/auth.php";

if($user['role'] != "seller"){
    header("Location: login.php");
    exit();
}

/* Delete product */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM seller_products WHERE id=? AND seller_id=?");
    $stmt->bind_param("ii",$id,$user['id']);
    $stmt->execute();
}

/* Fetch seller products */
$stmt = $conn->prepare("SELECT * FROM seller_products WHERE seller_id=? ORDER BY id DESC");
$stmt->bind_param("i",$user['id']);
$stmt->execute();
$products = $stmt->get_result();
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
.product-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:25px;
}

.product-card {
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 15px 35px rgba(0,0,0,0.06);
    transition:0.3s;
    position:relative;
}

.product-card:hover {
    transform:translateY(-6px);
    box-shadow:0 25px 45px rgba(0,0,0,0.1);
}

.product-card h3 {
    margin-bottom:6px;
}

.product-desc {
    color:#666;
    font-size:14px;
    margin-bottom:12px;
    min-height:40px;
}

.price {
    font-weight:bold;
    font-size:16px;
    margin:8px 0;
}

.badge {
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    display:inline-block;
}

.in-stock {
    background:#eafaf1;
    color:#27ae60;
}

.low-stock {
    background:#fff8e1;
    color:#f39c12;
}

.out-stock {
    background:#fdecea;
    color:#e74c3c;
}

.action-buttons {
    margin-top:15px;
    display:flex;
    gap:10px;
}

.delete-btn {
    background:#ef4444;
    color:white;
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:13px;
}

.delete-btn:hover {
    background:#dc2626;
}
</style>

<div class="navbar">
    <div><strong>My Products</strong></div>
    <div>
        <a href="seller_home.php">Dashboard</a>
        <a href="add_product.php">Add New</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<h2 style="margin-bottom:30px;">📦 Your Listed Products</h2>

<?php if($products->num_rows == 0){ ?>
    <div style="background:white;padding:30px;border-radius:15px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,0.05);">
        <h3>No products added yet</h3>
        <p style="color:#666;">Start selling your recycled products today.</p>
        <a href="add_product.php" class="btn">Add Product</a>
    </div>
<?php } ?>

<div class="product-grid">

<?php while($row = $products->fetch_assoc()){ 

    $quantity = $row['quantity'];

    if($quantity == 0){
        $stockClass = "out-stock";
        $stockText = "Out of Stock";
    } elseif($quantity < 5){
        $stockClass = "low-stock";
        $stockText = "Low Stock";
    } else {
        $stockClass = "in-stock";
        $stockText = "In Stock";
    }

?>

<div class="product-card">

    <h3><?php echo htmlspecialchars($row['name']); ?></h3>

    <div class="product-desc">
        <?php echo htmlspecialchars($row['description']); ?>
    </div>

    <p><strong>Waste Type:</strong> <?php echo htmlspecialchars($row['waste_type']); ?></p>

    <div class="price">💎 <?php echo $row['points_price']; ?> Points</div>

    <p><strong>Quantity:</strong> <?php echo $quantity; ?></p>

    <p><strong>Delivery:</strong> <?php echo htmlspecialchars($row['delivery_estimate']); ?></p>

    <span class="badge <?php echo $stockClass; ?>">
        <?php echo $stockText; ?>
    </span>

    <div class="action-buttons">
        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn"
           onclick="return confirm('Are you sure you want to delete this product?')">
           Delete
        </a>
    </div>

</div>

<?php } ?>

</div>
</div>