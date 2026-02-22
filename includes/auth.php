<?php
include __DIR__ . "/../config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Get fresh user data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(!$user){
    session_destroy();
    header("Location: login.php");
    exit();
}
?>