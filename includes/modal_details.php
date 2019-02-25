<?php
require_once '../core/init.php';

$id = (int) $_POST['pid'];
$sql = "SELECT * FROM products WHERE id='$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);
$brand_id = $product['brand'];
$brand = mysqli_fetch_assoc($db->query("SELECT * FROM brand WHERE id='$brand_id'"));
$sizes = $product['sizes'];
$oneSize = explode(',', $sizes);
?>

<?php ob_start(); ?>
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <button class="close" type="button" onclick="closeModal()" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h2 class="text-center modal-title"><?= $product['name']; ?></h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <span id="modal_errors" class="bg-danger"></span>
                        <div class="col-sm-6 fotorama">
                            <?php $photos = explode(',', $product['image']);
                            foreach ($photos as $photo):
                                ?>
                                    <img src="<?= (!empty($photo)) ? $photo : 'images/products/default.jpg'; ?>" alt="<?= $product['name']; ?>" class="details img-thumbnail img" />  
                            <?php endforeach; ?>
                        </div>  
                        <div class="col-sm-6">
                            <h3 class="text-left">Details</h3>
                            <div class="row" style="margin-left: 5px;"><?= nl2br($product['description']); ?></div>
                            <hr><div class="row" style="margin-left: 5px;">
                            <font size="3">BRAND:</font> <font size="5"><?= $brand['brand_name']; ?></font>
                        <img src="<?= $brand['image']; ?>" class="img-rounded pull-right" style="width: 75px; height: auto;margin-right: 20px">
                        </div><hr>
                        <?php if($product['list_price']!=''):?>
                        <div class="row text-danger" style="margin-left: 5px;">
                            Price: <s> <?= money($product['list_price']); ?></s>
                        </div> 
                        <?php                                                        endif;?>
                         <div class="col-md-12" style="margin-left: 5px;">
                           <div class="row">
                            On Specter: <font class="text-success" size="4"> <?= money($product['price']); ?></font></div>
                         </div>
                        <hr>
                                <form action="add_cart.php" method="post" id="add_product_form">
                                    <input type="hidden" id="product_id" value="<?= $id; ?>" name="product_id">
                                    <input type="hidden" id="available" value="" name="available"><br>
                                    <div class="form-group col-md-12">
                                        <div class="col-xs-4 pull-right">
                                            <label for="quantity">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" min="0"><br><br>
                                        </div>
    <!--                                    <p class="text-success"><h4>In stock</h4></p>-->
                                    </div>
                                    <div class="form-group">
                                        <label for="size">Size</label>
                                        <select name="size" id="size" class="form-control">
                                            <option value="">Select</option>
                                            <?php
                                            foreach ($oneSize as $singleSize) {
                                                $oneOneSize = explode(":", $singleSize);
                                                $size = $oneOneSize[0];
                                                $available = $oneOneSize[1];
                                                if ($available > 0) {
                                                    echo '<option value="' . $size . '" data-available=' . $available . '>' . $size . ' '
                                                    . '( ' . $available . ' Available)' . '</option>';
                                                }
                                            }
                                            ?>



                                        </select>
                                    </div>

                                </form>
                        </div>     
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" onclick="closeModal()"><span class="glyphicon glyphicon-remove"></span>Cancel</button>
                <button class="btn btn-warning" onclick="add_to_cart();
                        return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add to Cart</button>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery('#size').change(function () {
        var available = jQuery('#size option:selected').data("available");
        jQuery('#available').val(available);
    });
    function closeModal() {
        jQuery('#details-modal').modal('hide');
        setTimeout(function () {
            jQuery('#details-modal').remove();
            jQuery('.modal-backdrop').remove();
        }
        , 500);
    }
    $(function () {
        $('.fotorama').fotorama({'loop': true, 'autoplay': true});
    });
</script>
<?php echo ob_get_clean(); ?>
