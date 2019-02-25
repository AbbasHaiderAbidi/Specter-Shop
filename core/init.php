<?php
$host="127.0.0.1";
$user="abbas";
$password="abbas";
$database="specterDB";

$db=  mysqli_connect($host, $user, $password, $database);
if(mysqli_connect_errno()){
    echo 'Database connection failed with error '.  mysqli_connect_error();
    die();
}
session_start();
require_once (realpath($_SERVER['DOCUMENT_ROOT']).'/Specter_Shop/config.php');
require_once BASEURL.'helpers/helpers.php';
require BASEURL.'vendor/autoload.php';
$cart_id='';
$domain=($_SERVER['HTTP_HOST']!='localhost')?'.'.$_SERVER['HTTP_HOST']:false;
if(isset($_COOKIE[CART_COOKIE])){
    $cart_id=  sanitizer($_COOKIE[CART_COOKIE]);
}
if(isset($_SESSION['SBUser'])){
    $user_id=$_SESSION['SBUser'];
    $query=$db->query("SELECT * FROM users WHERE id='$user_id' AND deleted=0");
    $user_data=  mysqli_fetch_assoc($query);
    $fn=explode(' ',$user_data['full_name']);
    $user_data['first']=$fn[0];
    
}

if(isset($_SESSION['success_flash'])){
    echo '<div class="bg-success text-center"><p class="text-success">' . $_SESSION['success_flash'] . '</p></div>';   
    unset($_SESSION['success_flash']);
//    header("refresh:3;url=index.php");
}
if(isset($_SESSION['success_flash_user'])){
    echo '<div class="bg-success text-center"><p class="text-success">' . $_SESSION['success_flash_user'] . '</p></div>';   
    unset($_SESSION['success_flash_user']);
    header("refresh:2;url=users.php");
}
//if(isset($_SESSION['success_flash_arch'])){
//    echo '<div class="bg-success text-center"><p class="text-success">' . $_SESSION['success_flash_arch'] . '</p></div>';   
//    unset($_SESSION['success_flash_arch']);
//    header("refresh:2;url=archived.php");
//}
if(isset($_SESSION['error_flash'])){
   echo '<div class="bg-danger text-center clearfix"><div class="text-danger">' . $_SESSION['error_flash'] . '</div></div>';   
    unset($_SESSION['error_flash']);
    header("refresh:3;url=index.php");
    
}
if(isset($_SESSION['error_flash_user'])){
   echo '<div class="bg-danger text-center clearfix"><div class="text-danger">' . $_SESSION['error_flash_user'] . '</div></div>';   
    unset($_SESSION['error_flash_user']);
    header("refresh:3;url=users.php");
    
}

//session_destroy();

