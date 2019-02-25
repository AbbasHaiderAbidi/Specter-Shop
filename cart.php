 <?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation_bar.php';
include 'includes/header_partial.php';

if ($cart_id != '') {
    $cartQ = $db->query("SELECT * FROM cart WHERE id='$cart_id'");
    $result = mysqli_fetch_assoc($cartQ);
    $items = json_decode($result['item'], true);

    $i = 1;
    $subtotal = 0;
    $item_count = 0;
}
?>
<div class="col-md-12">
    <div class="row">
        <h2 class="text-center">My Shopping Cart</h2><hr>
        <?php if ($cart_id == ''): ?>
            <div class="bg-danger">
                <p class="text-center text-danger">Your Shopping Cart is empty!</p> 
            </div><hr>

            <div class="text-center">
                <a href="index.php" class="btn btn-success btn-sm text-center">Start Shopping <span class="glyphicon glyphicon-triangle-right"></span></a>
            </div>
            <hr>
        <?php else: ?>
            <table class="table table-bordered table-condensed table-striped">
                <thead class="">
                <th>#</th>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Size</th>
                <th>Subtotal</th>
                </thead>
                <tbody>
                    <?php
                    $desc='';
                    foreach ($items as $item) {
                        $product_id = $item['id'];
                        $product_query = $db->query("SELECT * FROM products WHERE id='$product_id'");
                        $product = mysqli_fetch_assoc($product_query);
                        $sArr = explode(',', $product['sizes']);
                        foreach ($sArr as $sizeStr) {
                            $s = explode(':', $sizeStr);
                            if ($s[0] == $item['size']) {
                                $available = $s[1];
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><?= $product['name']; ?></td>
                            <td><?= money($product['price']); ?></td>
                            <td>
                                <button class="btn btn-default btn-xs glyphicon glyphicon-minus" onclick="update_cart('remove_one', '<?= $product['id']; ?>', '<?= $item['size']; ?>')"></button>
                                <?= $item['quantity']; ?>
                                <?php if ($item['quantity'] < $available): ?>
                                    <button class="btn btn-default btn-xs glyphicon glyphicon-plus" onclick="update_cart('add_one', '<?= $product['id']; ?>', '<?= $item['size']; ?>')"></button>
                                <?php else: ?>
                                    <span class="text-danger bg-danger">Max</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['size']; ?></td>
                            <td><?= money($item['quantity'] * $product['price']); ?></td>
                        </tr>
                        <?php
                        $i++;
                        $item_count+=$item['quantity'];
                        $desc.=($product['name'].',');
                        $subtotal+=($product['price'] * $item['quantity']);
                    }
                    $tax = TAXRATE * $subtotal;
                    
//                 $tax=number_format($tax,2);
                    $grand_total = $tax + $subtotal;
                    ?>
                </tbody>
            </table>
            <legend>&nbsp;&nbsp;Total</legend>
            <table class="table table-bordered table-condensed table-striped text-right">
                <thead class="total-table-header">
                <th class="text-center">Total Items</th>
                <th class="text-center">Sub-Total</th>
                <th class="text-center">Tax</th>
                <th class="text-center">Grand Total</th>
                </thead>
                <tbody>
                    <tr>
                        <td><?= $item_count; ?></td>
                        <td><?= money($subtotal); ?></td>
                        <td><?= money($tax); ?></td>
                        <td class="bg-success"><?= money($grand_total); ?></td>
                    </tr>
                </tbody>
            </table>


            <!-- Chkout btnl -->
            <button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#checkoutModal">
                <span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;Checkout >>
            </button>

            <!-- Modal -->
            <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="checkoutModalLabel">Shipping Address</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <form action="thankyou.php" method="post" id="payment_form">
                                    <input type="hidden" name="tax" value="<?=$tax;?>">
                                    <input type="hidden" name="subtotal" value="<?=$subtotal;?>">
                                    <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
                                    <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
                                    <input type="hidden" name="description" value="<?=  rtrim($desc, ',').'.'.'Total '.$item_count.' item'.(($item_count>1)?'s':'').' from Specter Shop';?>">
                                    <span class="bg-danger text-danger" id="payment-errors" name="payment-errors"></span>
                                    <div id="step1" style="display: block">
                                        <div class="form-group col-md-6">
                                            <label for="fullname">Full name :</label>
                                            <input class="form-control" id="fullname" name="fullname" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">E-mail :</label>
                                            <input class="form-control" id="email" name="email" type="text">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="h_no">Address Line 1 :</label>
                                            <input class="form-control" id="h_no" name="h_no" type="text" data-stripe="address_line1">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="street">Address Line 2 :</label>
                                            <input class="form-control" id="street" name="street" type="text" data-stripe="address_line2">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="city">City :</label>
                                            <input class="form-control" id="city" name="city" type="text" data-stripe="address_city">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="state">State :</label>
                                            <input class="form-control" id="state" name="state" type="text" data-stripe="address_state">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="pincode">ZIP Code :</label>
                                            <input class="form-control" id="pincode" name="pincode" type="text" data-stripe="address_zip">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="contact">Country :</label>
                                            <input class="form-control" id="contact" name="contact" type="text" data-stripe="address_country">
                                        </div>
                                    </div>
                                    <div id="step2" style="display: none;">
                                        <div class="form-group col-md-3">
                                            <label for="name">Name on card:</label>
                                            <input type="text" class="form-control" id="name" data-stripe="name">
                                        </div>
                                         <div class="form-group col-md-3">
                                            <label for="cardno">Card no. :</label>
                                            <input type="text" class="form-control" id="cardno" data-stripe="number">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="cvc">CVC :</label>
                                            <input type="text" class="form-control" id="cvc"  data-stripe="cvc">
                                        </div>
                                         <div class="form-group col-md-2">
                                            <label for="expmonth">Expiry Month:</label>
                                            <select id="expmonth" class="form-control" data-stripe="exp_month">
                                                <option value=""></option>
                                                <?php for($j=1;$j<=12;$j++):?>
                                                <option value="<?=$j;?>"><?=$j;?></option>
                                                <?php endfor;?>
                                            </select>
                                        </div>
                                         <div class="form-group col-md-2">
                                            <label for="expyear">Expiry Year:</label>
                                           <select id="expyear" class="form-control"  data-stripe="exp_year">
                                                <option value=""></option>
                                                <?php $yr=date("Y");
                                                for($j=0;$j<11;$j++):?>
                                                <option value="<?=$yr+$j;?>"><?=$yr+$j;?></option>
                                                <?php endfor;?>
                                            </select>
                                        </div>
                                    </div>
                            </div>     
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
                            <button type="button" class="btn btn-primary" onclick="check_address();" id="next_btn">Next >></button>
                            <button type="button" class="btn btn-default" onclick="back_address();" id="back_btn" style="display: none;"><< Back</button>
                            <button type="submit" class="btn btn-success" id="check_out_btn" style="display: none;">Check Out <span class="glyphicon-triangle-right glyphicon"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
<?php endif; ?>
        </div>
    </div>
<script>

   window.onbeforeunload =function(){
       $('#check_out_btn').disabled=true;
   };
    function back_address(){
        jQuery('#payment-errors').html('');
                    jQuery('#step1').css("display","block");
                    jQuery('#checkoutModalLabel').html("Shipping Address :")
                    jQuery('#next_btn').css("display","inline-block");
                    jQuery('#step2').css("display","none");
                    jQuery('#back_btn').css("display","none");
                    jQuery('#check_out_btn').css("display","none");
    }
    function check_address(){
        var data={
            "fullname":jQuery('#fullname').val(),
            "email":jQuery('#email').val(),
            "h_no":jQuery('#h_no').val(),
            "street":jQuery('#street').val(),
            "city":jQuery('#city').val(),
            "state":jQuery('#state').val(),
            "pincode":jQuery('#pincode').val(),
            "contact":jQuery('#contact').val(),
        };
        jQuery.ajax({
            url:'/Specter_Shop/admin/parsers/check_address.php',
            method:"post",
            data:data,
            success:function(data){
                if(data!='Passed'){
                    jQuery('#payment-errors').html(data);
                    
                }
                if(data=='Passed'){
                    jQuery('#payment-errors').html('');
                    jQuery('#step1').css("display","none");
                    jQuery('#checkoutModalLabel').html("Enter Your Card Details :")
                    jQuery('#next_btn').css("display","none");
                    jQuery('#step2').css("display","block");
                    jQuery('#back_btn').css("display","inline-block");
                    jQuery('#check_out_btn').css("display","inline-block");
                }
            },
            error:function(){
                alert("Something went wrong");
            },
        });
    }
    
Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');
function stripeResponseHandler(status, response) {

  // Grab the form:
  var $form = $('#payment_form');

  if (response.error) { // Problem!

    // Show the errors on the form
    $form.find('#payment-errors').text(response.error.message);
    $(':input[type="submit"]').prop('disabled', false);
    //$form.find('button').prop('disabled', false); // Re-enable submission

  } else { // Token was created!

    // Get the token ID:
    var token = response.id;

    // Insert the token into the form so it gets submitted to the server:
    $form.append($('<input type="hidden" name="stripeToken" />').val(token));

    // Submit the form:
    $(':input[type="submit"]').prop('disabled', true);
    //jQuery('#chk_out_btn').css("display","none");
    $form.get(0).submit();

  }
}

jQuery(function($){
$('#payment_form').submit(function(event){
var $form=$(this);
$form.find('button').prop('disabled',true);
 jQuery('#chk_out_btn').css("display","none");
Stripe.card.createToken($form,stripeResponseHandler);
return false;
});
});
</script>
<?php
include 'includes/footer_script.php';
