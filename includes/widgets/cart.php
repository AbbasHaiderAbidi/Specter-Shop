<h3 class="text-center">Shopping Cart</h3>
<div>
    <?php
    if(empty($cart_id)):?>
    <p>Your Shopping Cart is Empty!</p>
    <?php else:
        $cartQ=$db->query("SELECT * FROM cart WHERE id='$cart_id'");
        $cartRes=  mysqli_fetch_assoc($cartQ);
        $items=  json_decode($cartRes['item'],true);
       
        $sub_total=0;
    ?>
    <div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
          <a data-toggle="collapse" href="#collapse1"><span class="glyphicon glyphicon-shopping-cart"></span> Your Cart</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse">
      <div class="panel-body">
    <table class="table table-condensed table-responsive" id="cart_widget">
        <tbody>
            <?php foreach($items as $item):
                $productQ=$db->query("SELECT * FROM products WHERE id='{$item['id']}'");
                $product=  mysqli_fetch_assoc($productQ);
                ?>
            <tr>
                <td><?=$item['quantity'];?></td>
                <td><?=(strlen($product['name'])>10)?substr($product['name'],0,10).'...':$product['name'];?></td>
                <td><?=money($item['quantity']*$product['price']);?></td>
            </tr>
           <?php    
           $sub_total+=$item['quantity']*$product['price'];
           endforeach;?>
        <br>
            <tr> 
                <td></td>
                <td>Total: </td>
                <td class="bg-success"><?=money($sub_total);?></td>
            </tr>
        </tbody>
    </table>
          </div>
      <div class="panel-footer"><a href="cart.php" class="btn btn-xs btn-default pull-right">View Cart</a>
    <div class="clearfix"></div></div>
    </div>
  </div>
</div>
    
    <?php   endif;?>
    </div>
