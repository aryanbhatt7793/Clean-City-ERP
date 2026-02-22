<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/auth.php";

if($user['role'] != "user"){
    header("Location: login.php");
    exit();
}

/* ========================= */
/* VALIDATE INPUT */
/* ========================= */

if(!isset($_GET['product_id']) || !isset($_GET['source'])){
    header("Location: marketplace.php");
    exit();
}

$product_id = intval($_GET['product_id']);
$source = $_GET['source'];

if($source !== "admin" && $source !== "seller"){
    die("Invalid product source.");
}

/* ========================= */
/* FETCH PRODUCT */
/* ========================= */

if($source == "admin"){

    $stmt = $conn->prepare("
        SELECT id, name, description,
        points_required AS price,
        stock AS quantity
        FROM products
        WHERE id=?
    ");

} else {

    $stmt = $conn->prepare("
        SELECT id, name, description,
        points_price AS price,
        quantity
        FROM seller_products
        WHERE id=?
    ");
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if(!$product){
    die("Product not found.");
}

$success = "";

/* ========================= */
/* PLACE ORDER */
/* ========================= */

if(isset($_POST['place_order'])){

    if($user['points'] < $product['price']){
        die("Not enough points.");
    }

    if($product['quantity'] <= 0){
        die("Product out of stock.");
    }

    /* Save address */
    $stmt = $conn->prepare("
        INSERT INTO addresses
        (user_id, full_name, phone, address_line, city, state, pincode)
        VALUES (?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "issssss",
        $user['id'],
        $_POST['full_name'],
        $_POST['phone'],
        $_POST['address_line'],
        $_POST['city'],
        $_POST['state'],
        $_POST['pincode']
    );

    $stmt->execute();
    $address_id = $stmt->insert_id;

    /* Create order */
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, address_id, total_points)
        VALUES (?,?,?)
    ");

    $stmt->bind_param("iii",
        $user['id'],
        $address_id,
        $product['price']
    );

    $stmt->execute();
    $order_id = $stmt->insert_id;

    /* Add order item */
    $quantity = 1;

    $stmt = $conn->prepare("
        INSERT INTO order_items
        (order_id, product_id, quantity, points_price)
        VALUES (?,?,?,?)
    ");

    $stmt->bind_param("iiii",
        $order_id,
        $product_id,
        $quantity,
        $product['price']
    );

    $stmt->execute();

    /* Deduct points */
    $stmt = $conn->prepare("
        UPDATE users
        SET points = points - ?
        WHERE id=?
    ");

    $stmt->bind_param("ii",
        $product['price'],
        $user['id']
    );

    $stmt->execute();

    /* Reduce stock based on source */
    if($source == "admin"){

        $stmt = $conn->prepare("
            UPDATE products
            SET stock = stock - 1
            WHERE id=?
        ");

    } else {

        $stmt = $conn->prepare("
            UPDATE seller_products
            SET quantity = quantity - 1
            WHERE id=?
        ");
    }

    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $success = "Order Placed Successfully!";
}
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="navbar">
    <div><strong>Checkout</strong></div>
    <div>
        Points: <?php echo $user['points']; ?>
        <a href="marketplace.php">Marketplace</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<?php if($success){ ?>
    <div style="background:#e6ffed;color:#0a7a33;padding:12px;border-radius:8px;margin-bottom:20px;text-align:center;">
        <?php echo $success; ?>
    </div>
<?php } ?>

<div style="display:flex;gap:30px;flex-wrap:wrap;">

    <!-- PRODUCT SUMMARY -->
    <div class="card" style="flex:1;min-width:300px;">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <p style="color:#666;"><?php echo htmlspecialchars($product['description']); ?></p>
        <hr>
        <p><strong>Points Required:</strong> <?php echo $product['price']; ?></p>
        <p><strong>Stock Available:</strong> <?php echo $product['quantity']; ?></p>
    </div>

    <!-- DELIVERY FORM -->
    <div class="card" style="flex:1;min-width:300px;">

        <h3>🚚 Delivery Details</h3>

        <form method="POST" style="display:flex;flex-direction:column;gap:12px;">

            <input type="text" name="full_name" placeholder="Full Name" required
            style="padding:10px;border-radius:8px;border:1px solid #ccc;">

            <input type="text" name="phone" placeholder="Phone Number" required
            style="padding:10px;border-radius:8px;border:1px solid #ccc;">

            <textarea name="address_line" placeholder="Address" required
            style="padding:10px;border-radius:8px;border:1px solid #ccc;"></textarea>

            <div style="display:flex;gap:10px;">
                <input type="text" name="city" placeholder="City" required
                style="flex:1;padding:10px;border-radius:8px;border:1px solid #ccc;">

                <input type="text" name="state" placeholder="State" required
                style="flex:1;padding:10px;border-radius:8px;border:1px solid #ccc;">
            </div>

            <input type="text" name="pincode" placeholder="Pincode" required
            style="padding:10px;border-radius:8px;border:1px solid #ccc;">

            <button name="place_order" class="btn" style="margin-top:10px;">
                Place Order
            </button>

        </form>

    </div>

</div>

</div>