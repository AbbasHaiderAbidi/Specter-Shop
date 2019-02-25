<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
$logged_id= $_POST['L_id'];
$user_img=  mysqli_fetch_assoc($db->query("SELECT * FROM users WHERE id='$logged_id'"));
ob_start();
?>

<div class="modal fade bd-example-modal-sm" id="change_img_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title text-center"> <?=$user_img['full_name'];?></h2>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <img src="<?=($user_img['image']=='')?'../images/users/default.png':'../'.$user_img['image'];?>" class="img-thumbnail img-responsive img-rounded">
            </div>
            <form action="index.php?change=1" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="change_img">Upload Image:</label>
                    <input type="file" class="form-control" name="change-img" id="change_img">
                </div>  
                    <div class="form-group">
                        <input type="submit" class="btn btn-success form-control" value="Change Picture">
                        
                    </div>
               
            </form>
             <button class="btn btn-default btn-block" onclick="closechngModal()" style="margin-top: 5px;">Cancel</button>
        </div>
        
    </div>
  </div>
    
</div>

<!-- Small modal -->

<script>
   function closechngModal(){
       jQuery('#change_img_modal').modal('hide');
       setTimeout(function(){
       jQuery('#change_img_modal').remove();
       jQuery('.modal-backdrop').remove();
       }
               ,500);
   }
</script>
<?php echo ob_get_clean();?>