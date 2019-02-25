<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
if(is_logged_in()){
unset($_SESSION['SBUser']);
header("Location: login.php");
}else{
    login_error_redirect();
}
