
</div><hr style="width: 1110px;">

<footer class="text-center" id="footer">&copy; A Project by S. Abbas Haider Abidi <br>
 <b><font face="Vivaldi" size="5">Specter</font></b>
Contact: +91-9651489958 <br>
Email: abbashaider2131995@gmail.com</footer>

<script>
    function change_img(logged_user){
        var data={"L_id":logged_user};
        jQuery.ajax({
           url:'/Specter_Shop/admin/includes/change_img_modal.php',
           method:"post",
           data:data,
           success:function(data){
               jQuery('body').append(data);
               jQuery('#change_img_modal').modal('toggle');
           },
           error: function () {
                alert("Somthing Went wrong!");
            }
        });
    }
    function change_permissions(userID){
        var data={"user_id":userID};
        jQuery.ajax({
           url:'/Specter_Shop/admin/includes/change_permissions.php',
           method:"post",
           data:data,
           success:function(data){
               jQuery('body').append(data);
               jQuery('#change_permissions_modal').modal('toggle');
           },
           error: function () {
                alert("Somthing Went wrong!");
            }
        });
    }
    
    function updateSizes(){
        var sizeStr='';
        var i;
        for(i=1;i<=12;i++){
            if(jQuery('#sizze'+i).val()!=''){
                sizeStr+=jQuery('#sizze'+i).val()+':'+jQuery('#qty'+i).val()+':'+jQuery('#threshold'+i).val()+',';
            }
        }
        sizeStr=sizeStr.replace(/,*$/,'');
        jQuery('#sizes').val(sizeStr);
    }
    function del_modal(P_id) {
            
        var data = {"n": P_id};
        
        jQuery.ajax({
            url:'/Specter_Shop/admin/includes/Del_modal.php',
            method: "post",
            data: data,
            success: function (data) {            
                jQuery('body').append(data);
                
                jQuery('#details-del-modal').modal('toggle');
            },
            error: function () {
                alert("Somthing Went wrong!");
            }
        });

    }
    function image_modal(U_id){
        var data={"uid":U_id};
        jQuery.ajax({
        url:'/Specter_Shop/admin/includes/image_modal.php',
        method:"post",
        data:data,
        success:  function(data){
            jQuery('body').append(data);
                
                jQuery('#user_image_modal').modal('toggle');},
            error: function(){
                alert("Something went wrong!");
            }
    });
    }
    function get_child_options(selected){
        if(typeof selected==='undefined'){
            selected='';
        }
        
        var parentID=jQuery('#parent').val();
        jQuery.ajax({
            url: '/Specter_Shop/admin/parsers/child_categories.php',
            type: 'POST',
            data: {parentID : parentID, selected : selected},
            success:function(data){
                jQuery('#child').html(data);
            },
            error: function(){alert("Something went wrong in Child Options");}
        });
    }
    jQuery('select[name="parent"]').change(function(){
        get_child_options();
    });
</script>
</body>