<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/core/init.php';
//include $_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/helpers/helpers.php';
//include $_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/config.php';
$mode=  sanitizer($_POST['mode']);
$edit_size=  sanitizer($_POST['edit_size']);
$edit_id=  sanitizer($_POST['edit_id']);
$cartQ=$db->query("SELECT * FROM cart WHERE id='{$cart_id}'");
$result=  mysqli_fetch_assoc($cartQ);
var_dump($result);
$items=  json_decode($result['item'],true);
$updated_items=array();
if($mode=='remove_one'){
    foreach ($items as $item){
        if($item['id']==$edit_id&&$item['size']==$edit_size){
            $item['quantity']=$item['quantity']-1;
        }
        if($item['quantity']>0){
            $updated_items[]=$item;
        }
    }  
}
if($mode=='add_one'){
    foreach ($items as $item){
        if($item['id']==$edit_id&&$item['size']==$edit_size){
            $item['quantity']=$item['quantity']+1;
        }        
            $updated_items[]=$item;
    }  
}

if(!empty($updated_items)){
    $json_update=  json_encode($updated_items);
    $db->query("UPDATE cart SET item='$json_update' WHERE id='$cart_id'");
    $_SESSION['success_flash']='Your shopping cart has been updated';
}
if(empty($updated_items)){
    $db->query("DELETE FROM cart WHERE id='$cart_id'");
    setcookie(CART_COOKIE,'',1,'/',$domain,false);
}