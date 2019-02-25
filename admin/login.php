<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/core/init.php';
include 'includes/head.php';
//include '../helpers/helpers.php';

?>
<style>
    body{
        background-image: url("/Specter_Shop/images/background/white_bag3.jpg");
        
        background-size: 100vw 100vh;
        background-attachment: fixed;
    }
    h1{
        color:#fff;
    }
    h3{
        color:#fff;
    }
</style>
<div id='login-form'>
    <div>
        <?php
        
        $email=trim(isset($_POST['email'])?sanitizer($_POST['email']):'');
        $pass=trim(isset($_POST['pass'])?sanitizer($_POST['pass']):'');
        $errors=array();
        
        //form valid
        if($_POST){
            $d=0;
        $userquery=$db->query("SELECT * FROM users WHERE email='$email' AND deleted=0");
        $users=  mysqli_fetch_assoc($userquery);
        $usercount=  mysqli_num_rows($userquery);
        
        $arch=  mysqli_num_rows($db->query("SELECT * FROM users WHERE email='$email' AND deleted=1"));
            if(empty($email)||empty($pass)){
                $errors[]='You must provide E-mail and Password.';
               $d=1;
            }
       //email validation
       if(!filter_var($email, FILTER_VALIDATE_EMAIL)&&$d==0){
           $errors[]='You must enter a valid E-mail.';
           $d=1;
       }
       //pass more than 6 chars
       if(strlen($pass)<6&&$d==0){
           $errors[]='Password must be at least 6 characters long.';
           $d=1;
       }
        //chk if email exists
        if($usercount<1&&$d==0){
            $errors[]='That E-mail does not exists';
            $d=1;
        }
        //archived or not
        if($arch>0){
            $errors[]='Your account has been deleted, contact Administrator';
        }
        
        //password verification
        if(!password_verify($pass, $users['password'])&&$d==0){
            $errors[]='Password Does not matches our records, please try again.';
            $d=1;
        }
        
        //chk errors
        if(!empty($errors)){
            echo display_errors($errors);
            header('refresh:3;url=login.php');
        }else{
            //log user in
            $user_id=$users['id'];
            login($user_id); 
            $date=date("Y-m-d H:i:s");
            global $db;
            $db->query("UPDATE users SET last_login='$date' WHERE id='$user_id' AND deleted=0");
            $_SESSION['success_flash']='You are now logged in.';
            header('Location:/Specter_Shop/index.php');
        }
         }
        ?>
        
    </div>
    <h1 class="text-center">Login Panel</h1><hr>
    <form action="login.php" method='POST'>
        <div class='form-group'>
            <label for="email" class="pull-left"><h3 class="form_head">E-mail :</h3></label>
            <input type="text" class="form-control" name="email" id="email" placeholder="Enter your e-mail" value="<?=$email;?>">
        </div>
        <div class='form-group'>
            <label for="password" class="pull-left"><h3>Password :</h3></label>
            <input type="password" class="form-control" name="pass" id="pass" placeholder="Enter your Password" value="<?=$pass;?>">
        </div>
        <input type='submit' class="btn btn-success btn-lg" value='Login to Specter'>
        <div class="text-right">
            <a href="/Specter_Shop/index.php" class="btn btn-sm btn-default" alt='Home' style="margin-top: -50px;">Visit Home</a></div>
    </form>
</div>
<?php
include 'includes/footer.php';
