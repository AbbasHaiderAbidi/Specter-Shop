<?php
require_once 'core/init.php';
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Token is created using Stripe.js or Checkout!
// Get the payment token submitted by the form:


$token = $_POST['stripeToken'];
$fullname = sanitizer($_POST['fullname']);
$email = sanitizer($_POST['email']);
$street1 = sanitizer($_POST['h_no']);
$street2 = sanitizer($_POST['street']);
$city = sanitizer($_POST['city']);
$zip = sanitizer($_POST['pincode']);
$state = sanitizer($_POST['state']);
$country = sanitizer($_POST['contact']);
$grand_total = sanitizer($_POST['grand_total']);
$subtotal = sanitizer($_POST['subtotal']);
$cart_id = sanitizer($_POST['cart_id']);
$tax = sanitizer($_POST['tax']);
$description = sanitizer($_POST['description']);
//$charge_amount = number_format($grand_total,2)*100;
$charge_amount = (int) $grand_total * 100;

$metadata = array(
    "cart_id" => $cart_id,
    "tax" => $tax,
    "subtotal" => $subtotal,
);

// Charge the user's card
try {
    $charge = \Stripe\Charge::create(array(
                "amount" => $charge_amount,
                "currency" => CURRENCY,
                "description" => $description,
                "source" => $token,
                "receipt_email" => $email,
                "metadata" => $metadata
    ));
    //adjust inventory
    $itemQ = $db->query("SELECT * FROM cart WHERE id='$cart_id'");
    $item_res = mysqli_fetch_assoc($itemQ);
    $items = json_decode($item_res['item'], true);
    foreach ($items as $item) {
        $newSizes = array();
        $item_id = $item['id'];
        //echo $item_id;
        $productQ = $db->query("SELECT * FROM products WHERE id='$item_id'");
        $product = mysqli_fetch_assoc($productQ);
        $sizes = sizesToArray($product['sizes']);
        foreach ($sizes as $size) {
            if ($size['size'] == $item['size']) {
                $q = $size['quantity'] - $item['quantity'];
                $newSizes[] = array('size' => $size['size'],
                    'quantity' => $q,
                    'threshold'=>$size['threshold']);
            }else{
                $newSizes[] = array('size' => $size['size'],
                    'quantity' => $size['quantity'],
                    'threshold'=>$size['threshold']);
            }
        }
        $sizeStr=  sizesToStr($newSizes);
        $db->query("UPDATE products SET sizes='$sizeStr' WHERE id='$item_id'");
    }
    //update cart
    $db->query("UPDATE cart SET paid=1 WHERE id='{$cart_id}'");
    $db->query("INSERT into transactions 
                (charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,country,sub_total,tax,grand_total,description,txn_type) 
         VALUES ('$charge->id','$cart_id','$fullname','$email','$street1','$street2','$city','$state','$zip','$country','$subtotal','$tax','$grand_total','$description','$charge->object')");
    setcookie(CART_COOKIE, '', 1, '/', $domain, false);
    include './includes/head.php';
    include './includes/navigation_bar.php';
    include './includes/header_partial.php';
    ?>
    <h1 class="text-success bg-success">Thank You!</h1>
    <p>You have been Charged <?= money($grand_total); ?>
        Your receipt number is : <?= $cart_id; ?>
    <address>
        <?= $fullname; ?><br>
        <?= $street1; ?><br>
        <?= ($street2 != '') ? $street2 . '<br>' : ''; ?>
        <?= $city . ', ' . $state . '- ' . $zip; ?><br>
        <?= $country; ?><br>
    </address>
    <hr>
    </p>
    <?php
} catch (\Stripe\Error\Card $ex) {
    echo 'ABBBAS ' . $ex;
}
