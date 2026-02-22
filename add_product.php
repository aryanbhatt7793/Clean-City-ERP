<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/auth.php";

if($user['role'] != "seller"){
    header("Location: login.php");
    exit();
}

$success = "";

if(isset($_POST['add_product'])){

    $name = $_POST['name'];
    $description = $_POST['description'];
    $waste_type = $_POST['waste_type'];
    $points_price = intval($_POST['points_price']);
    $quantity = intval($_POST['quantity']);
    $delivery_estimate = $_POST['delivery_estimate'];

    $stmt = $conn->prepare("
        INSERT INTO seller_products 
        (seller_id,name,description,waste_type,points_price,quantity,delivery_estimate) 
        VALUES (?,?,?,?,?,?,?)
    ");
    $stmt->bind_param(
        "isssiis",
        $user['id'],
        $name,
        $description,
        $waste_type,
        $points_price,
        $quantity,
        $delivery_estimate
    );
    $stmt->execute();

    $success = "Product Added Successfully!";
}
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
.form-container {
    max-width:900px;
    margin:auto;
}

.form-card {
    background:white;
    padding:40px;
    border-radius:20px;
    box-shadow:0 20px 50px rgba(0,0,0,0.08);
}

.form-grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.form-group {
    display:flex;
    flex-direction:column;
}

.form-group label {
    font-weight:600;
    margin-bottom:6px;
    font-size:14px;
}

.form-group input,
.form-group textarea,
.form-group select {
    padding:12px;
    border-radius:10px;
    border:1px solid #ddd;
    font-size:14px;
    transition:0.2s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    border-color:#2563eb;
    outline:none;
    box-shadow:0 0 0 3px rgba(37,99,235,0.15);
}

.form-group.full {
    grid-column:1 / -1;
}

.submit-btn {
    margin-top:25px;
    padding:12px 20px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    transition:0.2s;
}

.submit-btn:hover {
    background:#1d4ed8;
}

.success-box {
    background:#e6ffed;
    color:#0a7a33;
    padding:12px;
    border-radius:8px;
    margin-bottom:20px;
    text-align:center;
}
</style>

<div class="navbar">
    <div><strong>Add Product</strong></div>
    <div>
        <a href="seller_home.php">Dashboard</a>
        <a href="seller_products.php">My Products</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section form-container">

<h2 style="margin-bottom:25px;">📦 Add Recycled Product</h2>

<?php if($success){ ?>
    <div class="success-box"><?php echo $success; ?></div>
<?php } ?>

<div class="form-card">

<form method="POST">

<div class="form-grid">

    <div class="form-group full">
        <label>Product Name</label>
        <input type="text" name="name" required>
    </div>

    <div class="form-group full">
        <label>Description</label>
        <textarea name="description" rows="4"></textarea>
    </div>

    <div class="form-group">
        <label>Waste Type</label>
        <select name="waste_type" required>
            <option value="">Select Type</option>
            <option value="Plastic">Plastic</option>
            <option value="Metal">Metal</option>
            <option value="Paper">Paper</option>
            <option value="Glass">Glass</option>
            <option value="Organic">Organic</option>
        </select>
    </div>

    <div class="form-group">
        <label>Points Price</label>
        <input type="number" name="points_price" required>
    </div>

    <div class="form-group">
        <label>Quantity</label>
        <input type="number" name="quantity" required>
    </div>

    <div class="form-group">
        <label>Delivery Estimate</label>
        <input type="text" name="delivery_estimate" placeholder="3-5 days" required>
    </div>

</div>

<button name="add_product" class="submit-btn">Add Product</button>

</form>

</div>
</div>