<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation_bar.php';

if(isset($_GET['cat'])){
    $cat_id=  sanitizer($_GET['cat']);
    
}else{
    $cat_id='';
}
$sql="SELECT * FROM products WHERE categories='$cat_id' AND deleted=0";
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
                <h2 class="text-center"><?=$category['parent'].' - '.$category['child'];?></h2>
                <?php while ($products = mysqli_fetch_assoc($productQ)): ?>
                
                    <div class="col-md-3">
                        <div style="width: 200px; height: 250px;">
                            
                            <div class="hovereffect">
                            <?php $photoMain=explode(',',$products['image'])[0];?>
                            <img id="prdctimg" src="<?= (!empty($photoMain))?$photoMain:'images/products/default.jpg'; ?>" class="img-thumb img-thumbnail img-responsive" alt=<?= $products['name'] ?> />

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


