<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/auth.php";

if($user['role'] != "user"){
    header("Location: login.php");
    exit();
}

/* Fetch user orders */
$stmt = $conn->prepare("
    SELECT orders.*, addresses.full_name, addresses.city, addresses.state
    FROM orders
    JOIN addresses ON orders.address_id = addresses.id
    WHERE orders.user_id = ?
    ORDER BY orders.id DESC
");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$orders = $stmt->get_result();
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="navbar">
    <div>My Orders</div>
    <div>
        <a href="user_home.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">
<h2>Order History</h2>

<?php if($orders->num_rows == 0){ ?>
    <p>No orders found.</p>
<?php } ?>

<?php while($order = $orders->fetch_assoc()){ ?>

<div class="card">
    <h3>Order #<?php echo $order['id']; ?></h3>

    <p><strong>Total Points:</strong> <?php echo $order['total_points']; ?></p>

    <p>
        <strong>Status:</strong>
        <?php
        if($order['status']=="Pending"){
            echo "<span class='badge badge-yellow'>Pending</span>";
        } elseif($order['status']=="Confirmed"){
            echo "<span class='badge badge-green'>Confirmed</span>";
        } elseif($order['status']=="Shipped"){
            echo "<span class='badge badge-green'>Shipped</span>";
        } else {
            echo "<span class='badge badge-green'>Delivered</span>";
        }
        ?>
    </p>

    <p><strong>Delivery To:</strong>
        <?php echo htmlspecialchars($order['full_name']); ?>,
        <?php echo htmlspecialchars($order['city']); ?>,
        <?php echo htmlspecialchars($order['state']); ?>
    </p>

    <h4>Items:</h4>

    <?php
    $stmt2 = $conn->prepare("
        SELECT order_items.*, products.name
        FROM order_items
        JOIN products ON order_items.product_id = products.id
        WHERE order_items.order_id = ?
    ");
    $stmt2->bind_param("i", $order['id']);
    $stmt2->execute();
    $items = $stmt2->get_result();
    ?>

    <ul>
    <?php while($item = $items->fetch_assoc()){ ?>
        <li>
            <?php echo htmlspecialchars($item['name']); ?>
            (<?php echo $item['points_price']; ?> points)
        </li>
    <?php } ?>
    </ul>

    <p><small>Ordered on: <?php echo $order['created_at']; ?></small></p>

</div>

<?php } ?>

</div>