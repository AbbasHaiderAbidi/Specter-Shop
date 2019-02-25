 <?php 
 require_once '../../core/init.php';
 $user_id=  $_POST['user_id'];
 $Img_res=$db->query("SELECT * FROM users WHERE id='$user_id'");
 $user_img=  mysqli_fetch_assoc($Img_res);
 ob_start();
 ?>
<div class="modal fade bd-example-modal-sm" id="change_permissions_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title text-center">Change Permissions for user : <?=$user_img['full_name'];?></h2>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                 
                 <form action="users.php?change_permissions=<?=$user_img['id'];?>" method="post">
                        <label for="permissions">New permissions for <?=$user_img['full_name'];?></label>
                        <select name="new_permissions" id="new_permissions" class="form-control">
                            <option value="admin,editor,emp" <?=($user_img['permissions']=='admin,editor,emp')?' selected':'';?>>Administrator</option>
                            <option value="editor,emp" <?=($user_img['permissions']=='editor,emp')?' selected':'';?>>Editor</option>
                            <option value="emp" <?=($user_img['permissions']=='emp')?' selected':'';?>>Employee</option>
                        </select>
                        <input type="submit" class="btn btn-success" style="margin-top: 20px;">
                </form>
                
        </div>
        </div>
        <div class="modal-footer">
                <button class="btn btn-default" onclick="closepermModal()">Cancel</button>
                
                
    </div>
        
    </div>
  </div>
    
</div>
<script>
   function closepermModal(){
       jQuery('#change_permissions_modal').modal('hide');
       setTimeout(function(){
       jQuery('#change_permissions_modal').remove();
       jQuery('.modal-backdrop').remove();
       }
               ,500);
   }
</script>
<?php echo ob_get_clean();?>