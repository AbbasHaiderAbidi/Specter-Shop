<?php
$id = (int) $_POST['n'];
?>

<div class="modal fade details-1" id="details-del-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                
                <button class="close" type="button" onclick="closedelModal()" aria-label="close"><span aria-hidden="true">&times;</span></button>
                <h2 class="text-center modal-title">Confirm Delete</h2>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    You are about to Delete. This action cannot be undone.<br>
                    Do you want to proceed?
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" onclick="closedelModal()">Cancel</button>
                <form action="../admin/categories.php" method="get">
                    <button class="btn btn-danger" type="submit" name="p_delete" value="<?=$id?>"><span class="glyphicon glyphicon-shopping-trash"></span>Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
   function closedelModal(){
       jQuery('#details-del-modal').modal('hide');
       setTimeout(function(){
       jQuery('#details-del-modal').remove();
       jQuery('.modal-backdrop').remove();
       }
               ,500);
   }
</script>
