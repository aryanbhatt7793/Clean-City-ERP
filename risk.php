<?php
include "includes/auth.php";

$selected_area = "";
$risk_level = "";
$complaint_count = 0;

if(isset($_POST['check_area'])){

    $selected_area = $_POST['area'];

    $stmt = $conn->prepare("
        SELECT COUNT(*) as total 
        FROM complaints 
        WHERE location=?
    ");
    $stmt->bind_param("s", $selected_area);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $complaint_count = $data['total'];

    if($complaint_count <= 2){
        $risk_level = "Low";
    }
    elseif($complaint_count <= 5){
        $risk_level = "Moderate";
    }
    else{
        $risk_level = "High";
    }
}

/* Get unique areas */
$areas = $conn->query("SELECT DISTINCT location FROM complaints ORDER BY location ASC");
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
.result-box{
    margin-top:25px;
    padding:20px;
    border-radius:12px;
    text-align:center;
    font-size:18px;
}

.low{ background:#eafaf1; color:#27ae60; }
.moderate{ background:#fff6e5; color:#f39c12; }
.high{ background:#fdecea; color:#e74c3c; }
</style>

<div class="navbar">
    <div><strong>Smart Waste Awareness</strong></div>
    <div>
        <a href="user_home.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

<h2>🔎 Check Risk in Your Area</h2>
<p style="color:#666;">
Select your area to see predicted dumping risk based on complaint frequency.
</p>

<div class="card" style="max-width:500px;margin:auto;">

<form method="POST" style="display:flex;flex-direction:column;gap:15px;">

<select name="area" required style="padding:10px;border-radius:8px;border:1px solid #ccc;">
    <option value="">-- Select Area --</option>
    <?php while($row = $areas->fetch_assoc()){ ?>
        <option value="<?php echo htmlspecialchars($row['location']); ?>">
            <?php echo htmlspecialchars($row['location']); ?>
        </option>
    <?php } ?>
</select>

<button name="check_area" class="btn">Check Risk</button>

</form>

<?php if($risk_level){ ?>

<div class="result-box <?php echo strtolower($risk_level); ?>">

    <h3><?php echo htmlspecialchars($selected_area); ?></h3>

    <p>Total Complaints: <strong><?php echo $complaint_count; ?></strong></p>

    <p>
    <?php if($risk_level=="Low"){ ?>
        🟢 This area currently shows low dumping activity.
    <?php } elseif($risk_level=="Moderate"){ ?>
        🟡 Moderate dumping pattern observed. Monitoring advised.
    <?php } else { ?>
        🔴 High dumping frequency detected. Preventive action recommended.
    <?php } ?>
    </p>

</div>

<?php } ?>

</div>

</div>