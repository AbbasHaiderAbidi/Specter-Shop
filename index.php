<?php
require_once 'core/init.php';
include 'includes/head.php';
//if(isset($_SESSION)){
//    header("refresh:3;url=index.php");
//}
$sql = "SELECT * FROM products WHERE featured=1 AND deleted=0";
if(isset($_GET['category'])){
    $category=  sanitizer($_GET['category']);
    $sql="SELECT * FROM products WHERE categories='$category' AND deleted=0";
}
$featured = $db->query($sql);
?>
<!--header navigation bar-->
<?php include 'includes/navigation_bar.php'; ?>
<!--Carousel -->
<div id="carousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0"></li>
        <li data-target="#carousel" data-slide-to="1"></li>
        <li data-target="#carousel" data-slide-to="2"></li>
        <li data-target="#carousel" data-slide-to="3"></li>
        <li data-target="#carousel" data-slide-to="4"></li>
        <li data-target="#carousel" data-slide-to="5"></li>
        <li data-target="#carousel" data-slide-to="6"></li>
        <li data-target="#carousel" data-slide-to="7"></li>
        <li data-target="#carousel" data-slide-to="8"></li>
        <li data-target="#carousel" data-slide-to="9"></li>
    </ol>
    
    <div class="carousel-inner">
        <div class="item active carousel-imgset" >
            <img src="images/background/backgrnd2.jpg" alt="Slide 1" class="tales">
            <div class="carousel-caption">
                <h3>Style yourself</h3>
                <p>Not Only Shop but also style yourself.</p>
            </div> 
        </div>
        <div class="item carousel-imgset">
            <img src="images/background/backgrnd3.jpg" alt="Slide 2" class="tales">
            <div class="carousel-caption">
                <h3>Don't Miss!</h3>
                <p>Hit every weekend to our shop to grab New exciting deals on your favourite products.</p>
            </div> 
        </div>
        <div class="item carousel-imgset">
            <img src="images/background/backgrnd.jpg" alt="Slide 3" class="tales">
            <div class="carousel-caption">
                <h3>The Best</h3>
                <p>We are the best place where you can put yourself to a vote of likeness.</p>
            </div> 
        </div>
        <div class="item carousel-imgset">
            <img src="images/background/backgrnd4.jpg" alt="Slide 4" class="tales">
            <div class="carousel-caption">
                <h3>The Best</h3>
                <p>We are the best place where you can put yourself to a vote of likeness.</p>
            </div> 
        </div>
        <div class="item carousel-imgset">
            <img src="images/background/backgrnd5.jpg" alt="Slide 5" class="tales">
            <div class="carousel-caption">
                <h3>The Best</h3>
                <p>We are the best place where you can put yourself to a vote of likeness.</p>
            </div> 
        </div>
        <div class="item carousel-imgset">
            <img src="images/background/backgrnd6.jpg" alt="Slide 6" class="tales">
            <div class="carousel-caption">
                <h3>The Best</h3>
                <p>We are the best place where you can put yourself to a vote of likeness.</p>
            </div> 
        </div>
        <div class="item carousel-imgset">
            <img src="images/background/backgrnd7.jpg" alt="Slide 7" class="tales">
            <div class="carousel-caption">
                <h3>The Best</h3>
                <p>We are the best place where you can put yourself to a vote of likeness.</p>
            </div> 
        </div>
        <div class="item carousel-imgset">
            <img src="images/background/backgrnd8.jpg" alt="Slide 8" class="tales">
            <div class="carousel-caption">
                <h3>The Best</h3>
                <p>We are the best place where you can put yourself to a vote of likeness.</p>
            </div> 
        </div>
        <div class="item carousel-imgset">
            <img src="images/background/backgrnd9.jpg" alt="Slide 9" class="tales">
            <div class="carousel-caption">
                <h3>The Best</h3>
                <p>We are the best place where you can put yourself to a vote of likeness.</p>
            </div> 
        </div>

    </div>
    <a href="#carousel" class="left carousel-control" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a href="#carousel" class="right carousel-control" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>


<div id="contnt">
    <div class="container-fluid">
        <!--left side bar-->
        <?php include 'includes/left_side_bar.php'; ?>
        <!-- Main Content-->
        <div class="col-md-8">
            <div class="row">
                <h2 class="text-center">Featured Products</h2>
                <?php while ($products = mysqli_fetch_assoc($featured)): ?>
                
                    <div class="col-md-3">
                        <div style="width: 200px; height: 250px;">
                            <!--<h4 class="text-center productName text-capitalize"><font face="Calibri" size="4"><?= $products['name'] ?></font></h4>-->
                            <div class="hovereffect">
<?php $photoMain=explode(',',$products['image'])[0];?>
                            <img id="prdctimg" src="<?=(!empty($photoMain))?$photoMain:'images/products/default.jpg';?>" class="img-thumb img-thumbnail img-responsive" alt=<?= $products['name'] ?> />

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

</html>
