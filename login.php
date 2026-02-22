<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

$error = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password=?");
    
    if(!$stmt){
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss",$email,$password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        if($user['role']=="user"){
            header("Location: user_home.php");
        } 
        elseif($user['role']=="seller"){
            header("Location: seller_home.php");
        } 
        elseif($user['role']=="bidder"){
            header("Location: bidder_home.php");
        } 
        elseif($user['role']=="admin"){
            header("Location: admin_panel.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<link rel="stylesheet" href="assets/css/style.css">

<style>
body {
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e3a8a);
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* BRAND HEADER */
.brand-header{
    position:absolute;
    top:25px;
    left:40px;
    font-size:20px;
    font-weight:700;
}

.brand-header a{
    color:white;
    text-decoration:none;
}

.brand-header a:hover{
    opacity:0.85;
}

/* CONTAINER */
.login-container {
    display:flex;
    width:900px;
    background:rgba(255,255,255,0.08);
    backdrop-filter: blur(15px);
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 30px 60px rgba(0,0,0,0.4);
}

/* LEFT SIDE */
.login-left {
    flex:1;
    padding:50px;
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
    background: linear-gradient(160deg,#2563eb,#1e40af);
}

.login-left h1 {
    font-size:32px;
    margin-bottom:15px;
}

.login-left p {
    font-size:15px;
    opacity:0.9;
    line-height:1.6;
}

/* RIGHT SIDE */
.login-right {
    flex:1;
    background:white;
    padding:50px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.login-right h2 {
    margin-bottom:25px;
}

.login-form {
    display:flex;
    flex-direction:column;
    gap:15px;
}

.login-form input {
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:14px;
    transition:0.2s;
}

.login-form input:focus {
    border-color:#2563eb;
    outline:none;
    box-shadow:0 0 0 2px rgba(37,99,235,0.2);
}

.login-btn {
    background:#2563eb;
    color:white;
    padding:12px;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.login-btn:hover {
    background:#1d4ed8;
}

.error-box {
    background:#fdecea;
    color:#e74c3c;
    padding:10px;
    border-radius:8px;
    font-size:14px;
    margin-bottom:10px;
}

/* Divider */
.divider {
    margin:25px 0 15px;
    text-align:center;
    font-size:13px;
    color:#777;
    position:relative;
}

.divider span {
    background:white;
    padding:0 10px;
    position:relative;
    z-index:2;
}

.divider:before {
    content:"";
    position:absolute;
    top:50%;
    left:0;
    width:100%;
    height:1px;
    background:#eee;
    z-index:1;
}

/* Register Button */
.register-btn {
    display:block;
    text-align:center;
    padding:11px;
    border-radius:8px;
    font-weight:600;
    text-decoration:none;
    border:2px solid #2563eb;
    color:#2563eb;
    transition:0.3s;
}

.register-btn:hover {
    background:#2563eb;
    color:white;
}
</style>


<!-- CLICKABLE BRAND -->
<div class="brand-header">
    <a href="index.php">♻ CleanCity</a>
</div>


<div class="login-container">

    <!-- LEFT PANEL -->
    <div class="login-left">
        <h1>CleanCity</h1>
        <p>
            Turning waste into opportunity.  
            Connect. Recycle. Earn Points.  
            Marketplace for sustainability innovators.
        </p>
    </div>

    <!-- RIGHT PANEL -->
    <div class="login-right">
        <h2>Welcome Back 👋</h2>

        <?php if($error){ ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php } ?>

        <form method="POST" class="login-form">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button name="login" class="login-btn">Login</button>
        </form>

        <div class="divider">
            <span>New to CleanCity?</span>
        </div>

        <a href="register.php" class="register-btn">
            Create Account
        </a>

    </div>

</div>