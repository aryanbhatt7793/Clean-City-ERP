<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "includes/auth.php";

if($user['role'] != "user"){
    header("Location: login.php");
    exit();
}

$success = "";

if(isset($_POST['submit'])){

    $user_id = $user['id'];
    $issue = $_POST['issue'];
    $location = $_POST['location'];
    $severity = $_POST['severity'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp, "uploads/" . $image);

    $stmt = $conn->prepare("INSERT INTO complaints (user_id,image,issue,location,severity) VALUES (?,?,?,?,?)");
    $stmt->bind_param("issss",$user_id,$image,$issue,$location,$severity);
    $stmt->execute();

    $success = "Complaint Submitted Successfully!";
}
?>

<link rel="stylesheet" href="assets/css/style.css">

<div class="navbar">
    <div><strong>Smart Waste Platform</strong></div>
    <div>
        <a href="user_home.php">Dashboard</a>
        <a href="my_complaints.php">My Complaints</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="section">

    <div class="card" style="max-width:600px;margin:auto;">

        <h2 style="text-align:center;">📸 Report Waste Issue</h2>
        <p style="text-align:center;color:#666;">
            Help us keep the city clean by reporting unmanaged waste.
        </p>

        <?php if($success){ ?>
            <div style="background:#e6ffed;color:#0a7a33;padding:10px;border-radius:8px;margin-bottom:15px;text-align:center;">
                <?php echo $success; ?>
            </div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">

            <label><strong>Describe the Issue</strong></label>
            <textarea name="issue" placeholder="Explain what you are seeing..." required 
            style="width:100%;padding:10px;margin-top:5px;margin-bottom:15px;border-radius:8px;border:1px solid #ccc;"></textarea>

            <label><strong>Location</strong></label>
            <input type="text" name="location" placeholder="Enter location" required
            style="width:100%;padding:10px;margin-top:5px;margin-bottom:15px;border-radius:8px;border:1px solid #ccc;">

            <label><strong>Severity Level</strong></label>
            <select name="severity"
            style="width:100%;padding:10px;margin-top:5px;margin-bottom:15px;border-radius:8px;border:1px solid #ccc;">
                <option value="LESS PRONE">🟢 Less Prone</option>
                <option value="AVERAGE">🟡 Average</option>
                <option value="HIGH">🔴 High</option>
            </select>

            <label><strong>Upload Image</strong></label>
            <input type="file" name="image" required
            style="width:100%;padding:10px;margin-top:5px;margin-bottom:20px;">

            <button name="submit" class="btn" style="width:100%;">
                Submit Complaint
            </button>

        </form>

    </div>

</div>