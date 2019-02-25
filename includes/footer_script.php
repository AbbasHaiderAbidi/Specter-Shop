<footer class="text-center" id="footer"> 
 <b><font face="Vivaldi" size="5">Specter</font></b> - A project by <font face="Vivaldi" size="5"><b>
 S. Abbas Haider Abidi </b></font> <br>
Contact: <b>+91-9651489958 </b><br>
Email: <b>abbashaider2131995<font face="Vivaldi" size="6">@</font>gmail.com</b></footer>

<script>
    jQuery(window).scroll(function () {
        var kill = jQuery(this).scrollTop();
        if (kill < 450) {
            jQuery('#logotxt').css({
                "transform": "translate(" + (-kill / 2) + "px, " + kill / 3 + "px)"
            });

        }

    });
    function details_modal(id) {
            
        var data = {"pid": id};
        
        jQuery.ajax({
            url:'/Specter_Shop/includes/modal_details.php',
            method: "post",
            data: data,
            success: function (data) {            
                jQuery('body').append(data);
                
                jQuery('#details-modal').modal('toggle');
            },
            error: function () {
                alert("Somthing Went wrong!");
            }
        });

    }
    function update_cart(mode,edit_id,edit_size){
        var data={"mode":mode,"edit_id":edit_id,"edit_size":edit_size};
        jQuery.ajax({
            url:'/Specter_Shop/admin/parsers/update_cart.php',
            data:data,
            method:"post",
            success: function(){
                //alert("Something went right!");
        location.reload();
            },
            error: function(){
                alert("Something went Wrong!");
            }
        });
    }
    function add_to_cart(){
        jQuery('#modal_errors').html("");
        var size=jQuery('#size').val();
        var quantity=jQuery('#quantity').val();
        var available=jQuery('#available').val();
        var error='';
        var data=jQuery('#add_product_form').serialize();
        
        if(size==''||quantity==''||quantity==0){
            error+='<div class="text-danger text-center">You must choose a size and quantity</div><hr>';
            jQuery('#modal_errors').html(error);
            return;
        }else if(parseInt(quantity)>parseInt(available)){
             error+='<div class="text-danger text-center">Quantity available: '+available+'</div><hr>';
            jQuery('#modal_errors').html(error);
           return;
        }else{
            jQuery.ajax({
                url:'/Specter_Shop/admin/parsers/add_cart.php',
                data:data,
                method:"post",
                success:function(){
                
                location.reload();
                },
                error:function(){alert('Something Went Wrong!');}
            });
        }
    }
</script>
</body>
</html>