<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/core/init.php';
$product_id=  sanitizer($_POST['product_id']);
$size=  sanitizer($_POST['size']);
$quantity=  sanitizer($_POST['quantity']);
$available=  sanitizer($_POST['available']);
$item=array();
$item[]=array(
    'id'=>$product_id,
    'size'=>$size,
    'quantity'=>$quantity,
);
$domain=($_SERVER['HTTP_HOST']!='localhost')?'.'.$_SERVER['HTTP_HOST']:false;
$query=$db->query("SELECT * FROM products WHERE id='{$product_id}'");
$product=  mysqli_fetch_assoc($query);
$_SESSION['success_flash']=$product['name'].' was added to your cart';
$cart_expire=date("Y-m-d H-i-s",  strtotime("+30 days"));
//chk if crt cookie exists
if($cart_id!=''){
    $cartQ=$db->query("SELECT * FROM cart WHERE id='{$cart_id}'");
    $cart=  mysqli_fetch_assoc($cartQ);
    $previous_items=  json_decode($cart['item'],true);
    $item_match=0;
    $new_item=array();
    foreach($previous_items as $pitem){
        if($item[0]['id']==$pitem['id']&&$item[0]['size']==$pitem['size']){
            $pitem['quantity']=$pitem['quantity']+$item[0]['quantity'];
            //$pitem['quantity']=0;
            if($pitem['quantity']>$available){
                $pitem['quantity']=$available;
                
            }
            $item_match=1;
        }
        $new_item[]=$pitem;
    }
    if($item_match!=1){
        $new_item=  array_merge($item,$previous_items);
    }
    $items_json=  json_encode($new_item);
    $db->query("UPDATE cart SET item='{$items_json}', expire_date='{$cart_expire}' WHERE id='{$cart_id}'");
    setcookie(CART_COOKIE,'',1,'/',$domain,false);
    setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
}else{
    $items_json=  json_encode($item);
    $db->query("INSERT INTO cart (item,expire_date) VALUES('{$items_json}','{$cart_expire}')");
    $cart_id=$db->insert_id;
    setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
}

?>

