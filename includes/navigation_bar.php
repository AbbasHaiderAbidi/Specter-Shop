<?php
$sql = "SELECT * FROM categories where parent=0 AND deleted=0";
$pquery = $db->query($sql);

?>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
        <a href="/Specter_Shop/index.php" class="navbar-brand">
            <!--<img alt="Brand" src="../images/background/logomain.png"/>-->
            <font face="Vivaldi" size="7">Specter</font><font size=1> by Abbas</font>&nbsp;&nbsp;&nbsp;</font>
        </a>
        </div>
        <ul class="nav navbar-nav">
            <?php while ($parent = mysqli_fetch_assoc($pquery)): ?>
                <?php 
                $parent_id=$parent['id'];
                $sql2="select * from categories where parent='$parent_id' AND deleted=0";
                $cquery=$db->query($sql2);
                ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php echo $parent['category'];?>
                        <span class="caret"></span>
                    </a>                        
                    <ul class="dropdown-menu" role="menu">
                        <?php while($child=  mysqli_fetch_assoc($cquery)): ?>
                        <li><a href="/Specter_Shop/category.php?cat=<?=$child['id'];?>">&nbsp;<?php echo $child['category'];?></a></li>         
                        <?php                                    endwhile;?>
                    </ul>
                </li>
            <?php endwhile; ?>
                <li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> My Cart</a></li>
        </ul>       
        <ul class="nav navbar-nav navbar-right"><?php if(isset($_SESSION['SBUser'])):?>
            <li><a href="/Specter_Shop/admin/index.php"><span class="glyphicon glyphicon-home"></span>&nbsp; Dashboard</a></li>
            <li><a href="/Specter_Shop/admin/logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp; Logout</a></li>
            <?php else:?>
            <li><a href="/Specter_Shop/admin/index.php"><span class="glyphicon glyphicon-log-in"></span>&nbsp; User Login</a></li>
            <?php                            endif;?>
        </ul>        
    </div>            
</nav>  


