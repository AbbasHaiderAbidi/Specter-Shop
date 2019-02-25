<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';

if (!is_logged_in()) {
    login_error_redirect();
}
if (!has_permission('editor')) {
    permission_error_redirect('index.php');
}

include './includes/head.php';
include './includes/navigation.php';

if(isset($_GET['change_permissions'])){
if(isset($_POST['new_permissions'])&&  has_permission('admin')){
$new_perm=  sanitizer($_POST['new_permissions']);
$new_perm_id=  sanitizer($_GET['change_permissions']);
$db->query("UPDATE users SET permissions='$new_perm' WHERE id='$new_perm_id'");
header("Location:users.php");
}
}


if (isset($_GET['delete'])) {  
    if(has_permission('admin')){
    $delete_id = sanitizer($_GET['delete']);
    $db->query("UPDATE users SET deleted=1 WHERE id='$delete_id'");
    $_SESSION['success_flash_user'] = 'User has been deleted.';
    header("Location:users.php");
    }else{
        permission_error_redirect('users.php');
    }
}
if (isset($_GET['add'])) {
    $name = (isset($_POST['name'])) ? sanitizer($_POST['name']) : '';
    $email = (isset($_POST['email'])) ? sanitizer($_POST['email']) : '';
    $password1 = (isset($_POST['password1'])) ? sanitizer($_POST['password1']) : '';
    $confirm = (isset($_POST['confirm'])) ? sanitizer($_POST['confirm']) : '';
    $permissions = (isset($_POST['permissions'])) ? sanitizer($_POST['permissions']) : '';
    $errors = array();
    $d=0;
    if ($_POST) {
        $email_query=$db->query("SELECT * FROM users WHERE email='$email'");
        $email_count=mysqli_num_rows($email_query);
        $required = array('name', 'email', 'password1', 'confirm', 'permissions');
        foreach ($required as $f) {
            if (empty($_POST[$f])&&$d==0) {
                $errors[] = 'You must fill out all fields';
                $d=1;
                break;
            }
        }
        if (strlen($password1) < 6&&$d==0) {
            $errors[] = 'The password must be at least 6 characters long.';
            $d=1;
        }
        if ($password1 != $confirm && $d==0) {
            $errors[] = 'Your password do not match.';
            $d=1;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)&&$d==0) {
           $errors[]='You must enter a valid E-mail.'; 
           $d=1;
        }
        if($email_count!=0&&$d==0){
            $errors[]='E-mail already exists in our Database.';
            $d=1;
        }
        
        if (!empty($_FILES['user_photo']['name'])) {

            $mimeExt = '';
            $mimeType = '';
            $photo = $_FILES['user_photo'];
            $pname = $photo['name'];
            $nameArr[] = explode('.', $pname);
            $nameArr = $nameArr[0];

            if (!empty($nameArr) && $nameArr[0] != '') {
                $fileName = $nameArr[0];
                $fileExt = $nameArr[1];
            }
            $mime = explode('/', $photo['type']);
            if (!empty($mime[1]) && !empty($mime[0])) {
                $mimeType = $mime[0];
                $mimeExt = $mime[1];
                $uploadName = md5(microtime()) . '.' . $fileExt;
                $uploadPath = BASEURL . 'images/users/' . $uploadName;
                $dbpath = 'images/users/' . $uploadName;
            }
            $tmpLoc = $photo['tmp_name'];
            $fileSize = $photo['size'];
            $extAllowed = array('png', 'jpg', 'jpeg', 'bmp', 'PNG', 'BMP', 'JPG', 'JPG');
            
            if ($mimeType != 'image'&&$d==0) {
                $errors[] = 'The file must be an Image.';
                $d = 1;
            }
            if (!in_array($fileExt, $extAllowed)&& $d==0) {
                $errors[] = $fileExt.'The file must be of png, jpg, jpeg or bmp format.';
                $d = 1;
            }
            if ($fileSize > 10000000 && $d == 0) {
                $errors[] = 'The file size must ot exceed 10MB.';
                $d = 1;
            }
        }
        if (!empty($errors)) {
            echo display_errors($errors);
        } else {
            
            move_uploaded_file($tmpLoc, $uploadPath);
            //add user to db
            $hashed_pass=  password_hash($password1, PASSWORD_DEFAULT);
            $db->query("INSERT INTO users (full_name,email,password,permissions,image) VALUES('$name','$email','$hashed_pass','$permissions','$dbpath')");
            $_SESSION['success_flash_user']='User has been Added';
            header("Location:users.php");
        }
    }
    ?>
    <h2 class="text-center">Add new User</h2>
    <form action="users.php?add=1" method="post" enctype="multipart/form-data">
        <div class="form-group col-md-6">
            <label for="name">Full Name<font color="RED">* </font>:</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= $name ?>" placeholder="Enter name of new user">
        </div>
        <div class="form-group col-md-6">
            <label for="email">E-mail<font color="RED">* </font>:</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= $email ?>" placeholder="Enter e-mail of the user">
        </div>
        <div class="form-group col-md-6">
            <label for="password1">Password<font color="RED">* </font>:</label>
            <input type="password" name="password1" id="password" class="form-control" value="<?= $password1 ?>" placeholder="Enter new password">
        </div>
        <div class="form-group col-md-6">
            <label for="confirm">Confirm Password<font color="RED">* </font>:</label>
            <input type="password" name="confirm" id="confirm" class="form-control" value="<?= $confirm ?>" placeholder="Confirm new password">
        </div>
        <div class="form-group col-md-6">
            <label for="permissions">Permissions<font color="RED">* </font>:</label>
            <select class="form-control" name="permissions" id="permissions">
                <option value="" <?= ($permissions == '') ? ' selected' : ''; ?>></option>
                <option value="emp" <?= ($permissions == 'emp') ? ' selected' : ''; ?>>Employee</option>
                <option value="emp,editor" <?= ($permissions == 'emp,editor') ? ' selected' : ''; ?>>Editor</option>
                <option value="admin,editor,emp" <?= ($permissions == 'admin,editor,emp') ? ' selected' : ''; ?>>Administrator</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="user_photo">Picture<font color="RED">* </font>:</label>
            <input type="file" class="form-control " name="user_photo" id="user_photo"></div>

        <div class="form-group text-right col-md-6" style="margin-top: 25px;">
            <a href="users.php" class="btn btn-default">Cancel</a>&nbsp;&nbsp;
            <input type="submit" class="btn btn-success" value="Add new User">
        </div>
    </form>
    <?php
} else {
    $userQuery = $db->query("SELECT * FROM users WHERE deleted=0 ORDER BY full_name");
    ?>
    <h2 class="text-center">Users</h2><hr>
    <a href="users.php?add=1" class="btn btn-success btn-lg pull-right" style="margin-top: -80px;">Add new User</a>
    <table class="table table-bordered table-striped table-responsive table-condensed">
        <thead><th></th><th>Image</th><th>Name</th>
        <th>E-mail</th><th>Last Login</th><th>Join Date</th><th>Permissions</th></thead>
    <tbody>
        <?php while ($user = mysqli_fetch_assoc($userQuery)): ?>
            <tr>
                <?php $user_id=$user['id'];?>
                <td>
                    <?php
                    if ($user['id'] != $user_data['id']):
                        ?>
                        <a href="users.php?delete=<?= $user_id; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></a>
                    <?php else:?>
                        
                        <span class="glyphicon glyphicon-triangle-right"></span>
                      
                    <?php endif; ?>
                </td>
                <td><button class="btn btn-default btn-sm" onclick="image_modal(<?= $user['id']; ?>)">View Image</button></td>
                <td><?= $user['full_name']; ?></td>
                <td><?= $user['email']; ?></td>
                <td><?=($user['last_login']=='0000-00-00 00:00:00')?'Never':pretty_date($user['last_login']); ?></td>
                <td><?= pretty_date($user['join_date']); ?></td>
                <td><?= $user['permissions']; ?>
                    <?php if(has_permission('admin')):?>
                    <button class="btn btn-info btn-sm pull-right" onclick="change_permissions(<?=$user_id;?>)">Change</button>
                    
                    <?php endif;?>
                
                </td>
            </tr>

        <?php endwhile; ?>

    </tbody>
    </table>

<?php }
include './includes/footer.php';
?>
