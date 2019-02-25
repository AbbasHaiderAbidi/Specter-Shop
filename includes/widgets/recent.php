<h3 class="text-center">Popular Items</h3>
<?php
$tranQ=$db->query("SELECT * FROM cart WHERE paid=1 ORDER BY id DESC LIMIT 5");
$res=array();
while($row=  mysqli_fetch_assoc($tranQ)){
    $res[]=$row;
}
$row_count=$tranQ->num_rows;
$used_id=array();
for($i=0;$i<$row_count;$i++){
    $json_items=$res[$i]['item'];
    $items=  json_decode($json_items,true);
    foreach($items as $item){
        if(!in_array($item['id'], $used_id)){
            $used_id[]=$item['id'];
        }
    }
}
?>
<div id="recent_widget">
    <table class="table table-condensed table-bordered">
        <?php foreach($used_id as $id):
        $productQ=$db->query("SELECT id,name FROM products WHERE id='$id'");
        $product=  mysqli_fetch_assoc($productQ);
        ?>
        <tr>
            <td><?=substr($product['name'],0,15);?></td>
            <td><a onclick="details_modal('<?=$id?>')" class="text-primary" >View</a></td>
        </tr>
        <?php        endforeach;?>
        
    </table>
</div>
