<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
$user_img_id=$_SESSION['SBUser'];
$img_path=  mysqli_fetch_assoc($db->query("SELECT * FROM users WHERE id='$user_img_id'"))['image'];
?>
<div class="container">
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">

        <a href="index.php" class="navbar-brand">
            <font face="Vivaldi" size="6" style="margin-top: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;Specter Administrator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
        </a>

        <ul class="nav navbar-nav">
            <li><a href="brands.php">Brands&nbsp;</a></li>
            <li><a href="categories.php">Categories&nbsp;</a></li>
            <li><a href="products.php">Products&nbsp;</a></li>
             <?php if(has_permission('editor')):?>
             <li><a href="users.php">Users</a></li>
             <?php                 endif;?>
             <?php if(has_permission('editor')):?>
            <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" >Archived&nbsp;<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                <li><a href="archived.php?products=1">Products</a></li>
                <li><a href="archived.php?categories=1">Categories</a></li>
                <li><a href="archived.php?brands=1">Brands</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Administrator</li>
                <li><a href="archived.php?users=1">Users</a></li></ul>
            </li>
           
           
            <?php endif;?>
            
            
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <a class="navbar-brand navbar-img-user" href='#'><img class="img-responsive" src="<?=($img_path!='')?'../'.$img_path:'../images/users/default.png';?>" ></a>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome &nbsp;<?=$user_data['first'];?>!&nbsp;<span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li><img class="img-thumbnail img-responsive" src="<?=($img_path!='')?'../'.$img_path:'../images/users/default.png';?>" ></li>
                    <li class="divider"></li>
                    <li><a onclick="change_img(<?=$user_img_id;?>)" href="#">Change Image</a></li>
                    <li><a href="../index.php" target="_blank">Visit Site</a></li>
                    <li class="divider"></li>
                    <li class="dropdown-header">Profile</li>
                    <li><a href="change_password.php">Change Password</a></li>
                    <li><a href="logout.php">Logout</a></li>
              
                </ul>
                
            </li>
        </ul>
    </div>            
</nav>  
</div>