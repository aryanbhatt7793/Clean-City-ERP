<?php
include "includes/auth.php";

if($user['role'] != "admin"){
    header("Location: login.php");
    exit();
}

$bid_id = intval($_GET['bid'] ?? 0);
$listing_id = intval($_GET['listing'] ?? 0);

if(!$bid_id || !$listing_id){
    die("Invalid request.");
}

$conn->begin_transaction();

try {

    /* Check if listing already closed */
    $check = $conn->prepare("SELECT status FROM waste_listings WHERE id=?");
    $check->bind_param("i",$listing_id);
    $check->execute();
    $listingResult = $check->get_result();
    $listing = $listingResult->fetch_assoc();

    if($listing && $listing['status'] == 'closed'){
        throw new Exception("Already closed.");
    }

    /* Mark selected bid as Selected */
    $stmt = $conn->prepare("UPDATE bids SET status='Selected' WHERE id=?");
    $stmt->bind_param("i",$bid_id);
    $stmt->execute();

    if($stmt->affected_rows == 0){
        throw new Exception("Winner update failed.");
    }

    /* Mark other bids as Rejected */
    $stmt = $conn->prepare("
        UPDATE bids 
        SET status='Rejected' 
        WHERE listing_id=? AND id != ?
    ");
    $stmt->bind_param("ii",$listing_id,$bid_id);
    $stmt->execute();

    /* Mark listing as closed */
    $stmt = $conn->prepare("UPDATE waste_listings SET status='closed' WHERE id=?");
    $stmt->bind_param("i",$listing_id);
    $stmt->execute();

    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
    die("Error selecting winner.");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Bidding Completed</title>

<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#0f172a,#1e3a8a);
}

.success-box{
    background:white;
    color:#1e40af;
    padding:40px 60px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 25px 50px rgba(0,0,0,0.3);
}
</style>

<script>
setTimeout(function(){
    window.location.href = "admin_panel.php";
}, 3000);
</script>

</head>
<body>

<div class="success-box">
    <h2>🏆 Bidding Closed Successfully!</h2>
    <p>The winner has been selected.</p>
    <p>Redirecting to Admin Panel...</p>
</div>

</body>
</html>