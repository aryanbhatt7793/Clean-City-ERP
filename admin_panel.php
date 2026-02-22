<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/auth.php";

if($user['role']!="admin"){
    header("Location: login.php");
    exit();
}

/* ============================= */
/* ADD PRODUCT */
/* ============================= */
if(isset($_POST['add_product'])){
    $n = $_POST['pname'];
    $d = $_POST['pdesc'];
    $p = $_POST['ppoints'];
    $s = $_POST['pstock'];

    $stmt = $conn->prepare("INSERT INTO products (name,description,points_required,stock) VALUES (?,?,?,?)");
    $stmt->bind_param("ssii",$n,$d,$p,$s);
    $stmt->execute();
}

/* ============================= */
/* APPROVE COMPLAINT */
/* ============================= */
if(isset($_GET['approve'])){
    $id = $_GET['approve'];

    $stmt = $conn->prepare("UPDATE complaints SET status='Approved' WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();

    $stmt = $conn->prepare("SELECT user_id FROM complaints WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];

    $stmt = $conn->prepare("UPDATE users SET points = points + 10 WHERE id=?");
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
}

/* ============================= */
/* REJECT COMPLAINT */
/* ============================= */
if(isset($_GET['reject'])){
    $id = $_GET['reject'];
    $stmt = $conn->prepare("UPDATE complaints SET status='Rejected' WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
}

/* ============================= */
/* FETCH COMPLAINTS */
/* ============================= */
$result = $conn->query("
SELECT complaints.*, users.name 
FROM complaints 
JOIN users ON complaints.user_id=users.id
ORDER BY complaints.id DESC
");

/* Dashboard Stats */
$totalComplaints = $conn->query("SELECT COUNT(*) as total FROM complaints")->fetch_assoc()['total'];
$pendingComplaints = $conn->query("SELECT COUNT(*) as total FROM complaints WHERE status='Pending'")->fetch_assoc()['total'];
$totalProducts = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
.admin-stats {
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:30px;
}

.stat-box {
    flex:1;
    min-width:200px;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 6px 18px rgba(0,0,0,0.06);
    text-align:center;
}

.table-modern {
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

.table-modern th, .table-modern td {
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:left;
}

.table-modern th {
    background:#f4f6f9;
}

.badge {
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.badge-green { background:#eafaf1; color:#27ae60; }
.badge-yellow { background:#fff6e5; color:#f39c12; }
.badge-red { background:#fdecea; color:#e74c3c; }

.action-btn {
    padding:6px 10px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
}

.approve { background:#27ae60; color:white; }
.reject { background:#e74c3c; color:white; }
</style>

<div class="navbar">
    <div><strong>Admin Dashboard</strong></div>
    <div>
        <a href="admin_listings.php">Waste Listings</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<h2>📊 Admin Overview</h2>

<div class="admin-stats">

    <div class="stat-box">
        <h3><?php echo $totalComplaints; ?></h3>
        <p>Total Complaints</p>
    </div>

    <div class="stat-box">
        <h3><?php echo $pendingComplaints; ?></h3>
        <p>Pending Complaints</p>
    </div>

    <div class="stat-box">
        <h3><?php echo $totalProducts; ?></h3>
        <p>Marketplace Products</p>
    </div>

</div>

<h2>📝 Manage Complaints</h2>

<table class="table-modern">
<tr>
    <th>User</th>
    <th>Issue</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>
<tr>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['issue']); ?></td>
    <td>
        <?php if($row['status']=="Approved"){ ?>
            <span class="badge badge-green">Approved</span>
        <?php } elseif($row['status']=="Rejected"){ ?>
            <span class="badge badge-red">Rejected</span>
        <?php } else { ?>
            <span class="badge badge-yellow">Pending</span>
        <?php } ?>
    </td>
    <td>
        <?php if($row['status']=="Pending"){ ?>
            <a href="?approve=<?php echo $row['id']; ?>" class="action-btn approve">Approve</a>
            <a href="?reject=<?php echo $row['id']; ?>" class="action-btn reject">Reject</a>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</table>

<h2 style="margin-top:40px;">➕ Add Marketplace Product</h2>

<div class="card" style="max-width:600px;">
<form method="POST" style="display:flex;flex-direction:column;gap:12px;">
    <input type="text" name="pname" placeholder="Product Name" required>
    <textarea name="pdesc" placeholder="Description"></textarea>
    <input type="number" name="ppoints" placeholder="Points Required" required>
    <input type="number" name="pstock" placeholder="Stock" required>
    <button name="add_product" class="btn">Add Product</button>
</form>
</div>

</div>