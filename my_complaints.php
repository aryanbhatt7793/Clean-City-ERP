<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/auth.php";

if($user['role'] != "user"){
    header("Location: login.php");
    exit();
}

$user_id = $user['id'];

$stmt = $conn->prepare("SELECT * FROM complaints WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="navbar">
    <div>Smart Waste Platform</div>
    <div>
        <a href="user_home.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">
<h2>My Complaints</h2>

<table border="1" width="100%">
<tr>
<th>Image</th>
<th>Issue</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php while($row = $result->fetch_assoc()){ ?>

<tr>
<td>
<?php if(!empty($row['image'])){ ?>
<img src="uploads/<?php echo $row['image']; ?>" width="80">
<?php } ?>
</td>

<td><?php echo htmlspecialchars($row['issue']); ?></td>

<td>
<?php 
if($row['status']=="Approved"){
    echo "<span class='badge badge-green'>Approved</span>";
}
elseif($row['status']=="Rejected"){
    echo "<span class='badge badge-red'>Rejected</span>";
}
else{
    echo "<span class='badge badge-yellow'>Pending</span>";
}
?>
</td>

<td><?php echo $row['date_reported']; ?></td>
</tr>

<?php } ?>

</table>

</div>