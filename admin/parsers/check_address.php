<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
$name =     sanitizer($_POST['fullname']);
$email =    sanitizer($_POST['email']);
$h_no =     sanitizer($_POST['h_no']);
$contact =  sanitizer($_POST['contact']);
$street =   sanitizer($_POST['street']);
$city =     sanitizer($_POST['city']);
$pincode =  sanitizer($_POST['pincode']);
$state =    sanitizer($_POST['state']);
$errors=array();
$required=array(
    'fullname'  =>'Full name',
    'email'     =>'E-mail',
    'h_no'      =>'House no./Flat no.',
    'city'      =>'City',
    'pincode'   =>'PIN Code',
    'street'    =>'Street/Locality Address',
    'contact'   =>'Contact no.',
);
//chk if required is filled or not
foreach($required as $f=>$d){
    if(empty($_POST[$f])||$_POST[$f]==''){
        $errors[]=$d.' is required.';
    }
}
//chk for valid email
if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[]='Please enter a valid email';
}

if(!empty($errors)){
    echo display_errors($errors);
}else{
    echo 'Passed';
}