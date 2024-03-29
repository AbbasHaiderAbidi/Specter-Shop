<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';

//complete order
if(isset($_GET['complete'])&&$_GET['complete']==1){
    $cart_id=(int)  sanitizer($_GET['cart_id']);
    $db->query("UPDATE cart SET shipped=1 WHERE id='$cart_id'");
    $_SESSION['success_flash']='The order has been completed';
     header("refresh:1,url=index.php");
}
if(isset($_GET['txn_id'])){
$txn_id= sanitizer((int)$_GET['txn_id']);
$txnQuery=$db->query("SELECT * FROM transactions WHERE id='$txn_id'");
$txn=  mysqli_fetch_assoc($txnQuery);
$cart_id=$txn['cart_id'];
$cart=mysqli_fetch_assoc($db->query("SELECT * FROM cart WHERE id='$cart_id'"));
$items=  json_decode($cart['item'],true);
$idArray=array();
$products=[];
foreach($items as $item){
  $idArray[]=$item['id'];  
}
$ids=  implode(',', $idArray);
$productQ=$db->query("
    SELECT i.id AS 'id',i.name AS 'name',c.id AS 'cid',c.category as 'child',p.category as 'parent' 
    FROM products i LEFT JOIN categories c ON i.categories=c.id
    LEFT JOIN categories p ON c.parent=p.id
    WHERE i.id IN({$ids}) ORDER BY i.name
    ");
    
while($p=  mysqli_fetch_assoc($productQ)){
    foreach($items as $item){
        if($item['id']==$p['id']){
            $x=$item;
            continue;
        }
    }
    $products[]=  array_merge($x,$p);
}

?>
<h2 class="text-center">Items Ordered</h2>
<table class="table table-bordered table-condensed table-striped">
    <thead>
    <th>Quantity</th>
    <th>Title</th>
    <th>Category</th>
    <th>Size</th>
    </thead>
    <tbody>
        <?php        foreach ($products as $product):?>
        <tr>
            <td><?=$product['quantity'];?></td>
            <td><?=$product['name'];?></td>
            <td><?=$product['parent'].' - '.$product['child'];?></td>
            <td><?=$product['size'];?></td>
        </tr>
        <?php        endforeach;?>
    </tbody>
</table>
<div class="row">
    <div class="col-md-6">
        <h3 class="text-center">Order Details</h3>
        <table class="table table-bordered table-condensed table-striped">
            <tbody>
                <tr>
                    <td>Sub-Total</td>
                    <td><?=  money($txn['sub_total']);?></td>
                </tr>
                <tr>
                    <td>Tax</td>
                    <td><?=  money($txn['tax']);?></td>
                </tr>
                <tr>
                    <td>Grand Total</td>
                    <td><?=  money($txn['grand_total']);?></td>
                </tr>
                <tr>
                    <td>Order Date:</td>
                    <td><?= pretty_date($txn['txn_date']);?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h3 class="text-center">Shipping Address:</h3>
        <address>
            <?=$txn['full_name'];?><br>
            <?=$txn['street'];?><br>
            <?=($txn['street2']!='')?$txn['street2'].'<br>':'';?>
            <?=$txn['city'].', '.$txn['state'].'- '.$txn['zip_code'];?><br>
            <?=$txn['country'];?><br>
        </address>
    </div>
</div>
<div class="pull-right">
    <a href="index.php" class="btn btn-lg btn-default">Cancel</a>
    <a href="orders.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-lg btn-primary">Complete order</a>
</div>
<?php }
include 'includes/footer.php'
?>