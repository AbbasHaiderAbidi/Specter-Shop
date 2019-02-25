<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation_bar.php';

$sql="SELECT * FROM products WHERE deleted=0";
$cat_id=($_POST['cat']!='')?sanitizer($_POST['cat']):'';
if($cat_id!=''){
    $sql.=" AND categories='$cat_id'";
}
$price_sort=($_POST['price_sort']!='')?sanitizer($_POST['price_sort']):'';
$min_price=($_POST['min_price']!='')?sanitizer($_POST['min_price']):'';
$max_price=($_POST['max_price']!='')?sanitizer($_POST['max_price']):'';
$brand=($_POST['brand']!='')?sanitizer($_POST['brand']):'';
if($min_price!=''){
    $sql.=" AND price>='$min_price'";
}
if($max_price!=''){
    $sql.=" AND price<='$max_price'";
}
if($brand!=''){
    $sql.=" AND brand='$brand'";
}
if($price_sort=='low'){
    $sql.=" ORDER BY price";
}
if($price_sort=='high'){
    $sql.=" ORDER BY price DESC";
}
$productQ = $db->query($sql);
$category=  get_category($cat_id);

if(!empty($category['image'])){
    $image_path=$category['image'];
}else{
    $image_path='images/category/default.jpg';
}
?>
<div class="head_partial head-partial-blur head_partial-grayscale" style="background-image: url(<?=$image_path?>); "></div>
<div id="contnt">
    <div class="container-fluid">
        <!--left side bar-->
        <?php include 'includes/left_side_bar.php'; ?>
        <!-- Main Content-->
        <div class="col-md-8">
            <div class="row">
                <?php if($cat_id!=''):?>
                <h2 class="text-center"><?=$category['parent'].' - '.$category['child'];?></h2>
            <?php else:?>
                <h2 class="text-center">Results</h2>
            <?php endif;?>
                <?php while ($products = mysqli_fetch_assoc($productQ)): ?>
                
                    <div class="col-md-3">
                        <div style="width: 200px; height: 250px;">
                            
                            <div class="hovereffect">
<?php $photos=explode(',',$products['image']);?>
                            <img id="prdctimg" src="<?= (!empty($photos[0]))?$photos[0]:'images/products/default.jpg'; ?>" class="img-thumb img-thumbnail img-responsive" alt=<?= $products['name'] ?> />

                            <div class="overlay">
                                <h2><?= $products['name'] ?></h2><br><br><br><br>
                                <button type="button" class="btn custom-btn" onclick="details_modal(<?= $products['id']; ?>)">Details</button> 
                            </div>
                        </div>
                        </div>
                    </div>
                <?php endwhile; ?>


            </div>
        </div>
        <!--right side bar-->
        <?php include 'includes/right_side_bar.php'; ?>
    </div>
</div>


<!--modal Details-->

<!--JQuery Script-->

<?php include 'includes/footer_script.php'; ?>


