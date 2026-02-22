<?php
include "includes/auth.php";

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