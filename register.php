<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config.php";

$success = "";
$error = "";

if(isset($_POST['register'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));
    $role = $_POST['role'];

    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s",$email);
    $check->execute();
    $check->store_result();

    if($check->num_rows > 0){
        $error = "Email already registered.";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO users (name,email,password,role)
            VALUES (?,?,?,?)
        ");

        if(!$stmt){
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssss",$name,$email,$password,$role);
        $stmt->execute();

        $success = "Account created successfully!";
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

/* TOP BRAND */
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
    opacity:0.8;
}

/* CONTAINER */
.register-container {
    display:flex;
    width:950px;
    background:rgba(255,255,255,0.08);
    backdrop-filter: blur(15px);
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 30px 60px rgba(0,0,0,0.4);
}

/* LEFT PANEL */
.register-left {
    flex:1;
    padding:50px;
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
    background: linear-gradient(160deg,#2563eb,#1e40af);
}

.register-left h1 {
    font-size:32px;
    margin-bottom:15px;
}

.register-left p {
    font-size:15px;
    opacity:0.9;
    line-height:1.6;
}

/* RIGHT PANEL */
.register-right {
    flex:1;
    background:white;
    padding:50px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.register-right h2 {
    margin-bottom:20px;
}

.register-form {
    display:flex;
    flex-direction:column;
    gap:15px;
}

.register-form input,
.register-form select {
    padding:12px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:14px;
    transition:0.2s;
}

.register-form input:focus,
.register-form select:focus {
    border-color:#2563eb;
    outline:none;
    box-shadow:0 0 0 2px rgba(37,99,235,0.2);
}

.register-btn {
    background:#2563eb;
    color:white;
    padding:12px;
    border:none;
    border-radius:8px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.register-btn:hover {
    background:#1d4ed8;
}

.success-box {
    background:#e6ffed;
    color:#0a7a33;
    padding:10px;
    border-radius:8px;
    font-size:14px;
    margin-bottom:10px;
}

.error-box {
    background:#fdecea;
    color:#e74c3c;
    padding:10px;
    border-radius:8px;
    font-size:14px;
    margin-bottom:10px;
}

.login-link {
    margin-top:15px;
    font-size:14px;
}

.login-link a {
    color:#2563eb;
    text-decoration:none;
    font-weight:600;
}
</style>


<!-- CLICKABLE BRAND -->
<div class="brand-header">
    <a href="index.php">♻ CleanCity</a>
</div>


<div class="register-container">

    <!-- LEFT SIDE -->
    <div class="register-left">
        <h1>Join CleanCity</h1>
        <p>
            Become part of a sustainable ecosystem.  
            Earn points, sell recycled products, bid on waste materials,
            and contribute to a cleaner future.
        </p>
    </div>

    <!-- RIGHT SIDE -->
    <div class="register-right">
        <h2>Create Account 🚀</h2>

        <?php if($error){ ?>
            <div class="error-box"><?php echo $error; ?></div>
        <?php } ?>

        <?php if($success){ ?>
            <div class="success-box">
                <?php echo $success; ?>
                <div class="login-link">
                    <a href="login.php">Login Now</a>
                </div>
            </div>
        <?php } ?>

        <form method="POST" class="register-form">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>

            <select name="role">
                <option value="user">User</option>
                <option value="seller">Seller</option>
                <option value="bidder">Bidder</option>
            </select>

            <button name="register" class="register-btn">Create Account</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>

    </div>

</div>