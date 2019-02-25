<?php
$cat_id=(isset($_REQUEST['cat']))?sanitizer($_REQUEST['cat']):'';
$price_sort=(isset($_REQUEST['price_sort']))?sanitizer($_REQUEST['price_sort']):'';
$min_price=(isset($_REQUEST['min_price']))?sanitizer($_REQUEST['min_price']):'';
$max_price=(isset($_REQUEST['max_price']))?sanitizer($_REQUEST['max_price']):'';
$b=(isset($_REQUEST['brand']))?sanitizer($_REQUEST['brand']):'';
$brandQ=$db->query("SELECT * FROM brand WHERE deleted=0 ORDER BY brand_name");
?>

<h3 class="text-center">Search By </h3><br><hr>
<h4 class="text-center">Price: </h4>
<form action="search.php" method="post">
    <input type="hidden" name="cat" value="<?=$cat_id;?>">
    <input type="hidden" name="price_sort" value="0">
    <div class="radio">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="price_sort" value="low"<?=($price_sort=='low')?' checked':'';?>> Low to high<br>
    </div>
    <div class="radio">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="price_sort" value="high"<?=($price_sort=='high')?' checked':'';?>> High to low<br>
    </div><div class="row col-md-12">
        <input type="text" name="min_price" class="" style="width:55px" placeholder="Min. ₹" value="<?=$min_price;?>"> &nbsp;&nbsp;To &nbsp;&nbsp;
    <input type="text" name="max_price" class="" style="width:55px" placeholder="Max. ₹" value="<?=$max_price;?>">
    </div>
    <br><hr>
    
    <h4 class="text-center">Brand:</h4>
    <div style="overflow-y: auto; height: 300px; width: auto">
    <div class="radio">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="brand" value=""<?=($b=='')?' checked':'';?>> All<br>
    </div>
    <?php while($brand=  mysqli_fetch_assoc($brandQ)):?>
    <div class="radio">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="brand" value="<?=$brand['id']?>"<?=($b==$brand['id'])?' checked':'';?>><?=$brand['brand_name']?><br>
    </div>
    <?php    endwhile;?>
    </div><hr><br>
    
    <input type="submit" value="search" class="btn btn-default" style="width:100%">
</form>