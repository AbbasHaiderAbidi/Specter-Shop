<?php
include 'includes/head.php';
global $user_data;

?>
<style>
    body{
        background-image: url("/Specter_Shop/images/background/white_bag3.jpg");
        background-size: 100vw 100vh;
        background-attachment: fixed;
    }
    h2{
        color:#fff;
    }
    h4{
        color:#fff;
    }</style>
<div id='login-form'>
    <div>
        <?php
        require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
        if(!is_logged_in()){
            login_error_redirect();
        }        
        $old_pass=trim(isset($_POST['old_pass'])?sanitizer($_POST['old_pass']):'');
        $pass=trim(isset($_POST['pass'])?sanitizer($_POST['pass']):'');
        $confirm=trim(isset($_POST['confirm'])?sanitizer($_POST['confirm']):'');
        $new_hashed=  password_hash($pass, PASSWORD_DEFAULT);
        $errors=array();
        $hashed=$user_data['password'];
        $user_id=$user_data['id'];
        //form valid
        if($_POST){
            $d=0;
        if(empty($old_pass)||empty($pass)||empty($confirm)){
                $errors[]='You must provide all the fields.';
               $d=1;
            }
       
       //pass more than 6 chars
       if(strlen($pass)<3&&$d==0){
           $errors[]='Password must be at least 6 characters long.';
           $d=1;
       }
        //if new matches old
      if($pass!=$confirm){
          $errors[]='The new password does not matchs confirm new password.';
      }
        
        //password verification
        if(!password_verify($old_pass, $hashed)){
            $errors[]='Your old password Does not matches our records, please try again.';
            
        }
        
        //chk errors
        if(!empty($errors)){
            echo display_errors($errors);
            header('refresh:3;url=change_password.php');
        }else{
            //change password
            global $db;
            $db->query("UPDATE users SET password='$new_hashed' WHERE id='$user_id' AND deleted=0");
            $_SESSION['success_flash']='Your password has been changed.';
            header('Location:index.php');
        }
         }
        ?>
        
    </div>
    <h2 class="text-center">Change Password</h2><hr>
    <form action="change_password.php" method='POST'>
        <div class='form-group'>
            <label for="old_pass" class="pull-left"><h4 class="form_head">Old Password :</h4></label>
            <input type="password" class="form-control" name="old_pass" id="old_pass" placeholder="Enter your previous password" value="<?=$old_pass;?>">
        </div>
        <div class='form-group'>
            <label for="password" class="pull-left"><h4>New Password :</h4></label>
            <input type="password" class="form-control" name="pass" id="pass" placeholder="Enter your Password" value="<?=$pass;?>">
        </div>
        <div class='form-group'>
            <label for="confirm" class="pull-left"><h4>Confirm new Password:</h4></label>
            <input type="password" class="form-control" name="confirm" id="confirm" placeholder="Enter your Password again" value="<?=$confirm;?>">
        </div>
        <div class="form-group">
            <a href="index.php" class="btn btn-default btn-lg">Cancel</a>
            <input type='submit' class="btn btn-success btn-lg" value='Change Password'>
        </div>
        
    </form>
    <div class="text-right">
            <a href="/Specter_Shop/admin/index.php" class="btn btn-sm btn-default" alt='Home' style="margin-top: -50px;">Visit Home</a></div>
</div>
<?php
include 'includes/footer.php';

