<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/core/init.php';
$parentID=(int)$_POST['parentID'];
$selected=  sanitizer($_POST['selected']);
$childQuery=$db->query("SELECT * FROM categories WHERE parent='$parentID' ORDER BY category");

ob_start();?>

<option value="">
</option>
<?php while($child= mysqli_fetch_assoc($childQuery)):?>
<option value="<?=$child['id'];?>" <?=($child['id']==$selected)?' selected':'';?>><?=$child['category'];?></option>
<?php endwhile;?>
<?php echo ob_get_clean();?>

