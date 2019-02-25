 <?php 
 require_once '../../core/init.php';
 $uid=  $_POST['uid'];
 $Img_res=$db->query("SELECT * FROM users WHERE id='$uid'");
 $user_img=  mysqli_fetch_assoc($Img_res);
 ob_start();
 ?>
<div class="modal fade bd-example-modal-sm" id="user_image_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title text-center"> <?=$user_img['full_name'];?></h2>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <img src="<?=($user_img['image']=='')?'../images/users/default.png':'../'.$user_img['image'];?>" class="img-thumbnail img-responsive img-rounded">
            </div>
        </div>
        <div class="modal-footer">
                <button class="btn btn-default" onclick="closeimgModal()">Cancel</button>
  
    </div>
    </div>
  </div>
    
</div>
<script>
   function closeimgModal(){
       jQuery('#user_image_modal').modal('hide');
       setTimeout(function(){
       jQuery('#user_image_modal').remove();
       jQuery('.modal-backdrop').remove();
       }
               ,500);
   }
</script>
<?php echo ob_get_clean();?>