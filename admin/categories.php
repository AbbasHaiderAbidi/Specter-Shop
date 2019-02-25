<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
if (!is_logged_in()) {
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
$sql = "SELECT * FROM categories WHERE parent=0 AND deleted=0";
$result = $db->query($sql);
$errors = array();
$category = '';
$category_value = '';
$parent_value = 0;
$post_parent = '';
$dbpath='';
$cat_img='';
//Edit Catgeories
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = (int) sanitizer($_GET['edit']);
    $edit_sql = "SELECT * FROM categories WHERE id='$edit_id' AND deleted=0";
    $e_result = $db->query($edit_sql);
    $e_category = mysqli_fetch_assoc($e_result);
}

//delet catgories child
if (isset($_GET['c_delete']) && !empty($_GET['c_delete'])) {
    $del_child = sanitizer((int) $_GET['c_delete']);
    $dcsql = "UPDATE categories SET deleted=1 WHERE id='$del_child'";
    $db->query($dcsql);
    $success_del_cCat = '<ul class="bg-success"><li class="text-success">Category Deleted. </li></ul>';
    ?>
    <script>
        jQuery('document').ready(function () {
            jQuery('#eroor').html('<?= $success_del_cCat ?>');
        });
    </script>
    <?php
    header("refresh:2;url=categories.php");
}
//delete parent
if (isset($_GET['p_delete']) && !empty($_GET['p_delete'])) {
    $del_parent = sanitizer((int) $_GET['p_delete']);
    $dpsql = "UPDATE categories SET deleted=1 WHERE parent='$del_parent' OR id='$del_parent'";
    $db->query($dpsql);
    $success_del_pCat = '<ul class="bg-success"><li class="text-success">Category Deleted. </li></ul>';
    ?>
    <script>
        jQuery('document').ready(function () 
            jQuery('#eroor').html('<?= $success_del_pCat ?>');
        });
    </script>
    <?php
    header("refresh:3;url=categories.php");
}
if(isset($_GET['delete_cat_img'])&&isset($_GET['edit'])){
    $del_img_id=  sanitizer($_GET['edit']);
    $del_img_cat=  mysqli_fetch_assoc($db->query("SELECT * FROM categories WHERE id='$del_img_id'"));
    $deleteImage=BASEURL.$del_img_cat['image'];
    unset($deleteImage);
    $db->query("UPDATE categories SET image='' WHERE id='$del_img_id'");
    header("Location:categories.php?edit=".$del_img_id);
}
//form processing
if (isset($_POST) && !empty($_POST)) {
    $post_parent = sanitizer($_POST['parent']);
    $category = sanitizer($_POST['category']);
    $fileExt='';
    $sql_chk_duplicate = "SELECT * FROM categories WHERE category='$category' AND parent='$post_parent' AND deleted=0";
    if (isset($_GET['edit'])) {
        $id = $e_category['id'];
        $sql_chk_duplicate = "SELECT * FROM categories WHERE category='$category' AND parent='$post_parent' AND id!='$id'";
    }
    $dub_res = $db->query($sql_chk_duplicate);
    //chk if empty
    if ($category == '') {
        $errors[].='The category cannot be left blank.';
    }
    if (mysqli_num_rows($dub_res) > 0) {
        $errors[].='The category ' . $category . ' already exists in for this Parent cateogry.';
    }
    if (!empty($_FILES['cat_photo']['name'])) {

            $mimeExt = '';
            $mimeType = '';
            $photo = $_FILES['cat_photo'];
            $name = $photo['name'];
            $nameArr[] = explode('.', $name);
            $nameArr = $nameArr[0];

            if (!empty($nameArr) && $nameArr[0] != '') {
                $fileName = $nameArr[0];
                $fileExt = $nameArr[1];
            }
            $mime = explode('/', $photo['type']);
            if (!empty($mime[1]) && !empty($mime[0])) {
                $mimeType = $mime[0];
                $mimeExt = $mime[1];
                $uploadName = 'cat_bg'.$post_parent.'-'.$category. '.' . $fileExt;
                $uploadPath = BASEURL . 'images/category/' . $uploadName;
                $dbpath = 'images/category/' . $uploadName;
            }
            $tmpLoc = $photo['tmp_name'];
            $fileSize = $photo['size'];
            $extAllowed = array('png', 'jpg', 'jpeg', 'bmp', 'PNG', 'BMP', 'JPG', 'JPG');
            $d = 0;
            if ($mimeType != 'image') {
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
        $final_errors = display_errors($errors);
        ?>
        <script>
            jQuery('document').ready(function () {
                jQuery('#eroor').html('<?= $final_errors; ?>');
            });

        </script>
        <?php
    } else {
        if(!empty($_FILES['cat_photo']['name'])){
            move_uploaded_file($tmpLoc, $uploadPath);
        }
        $update_cat = "INSERT INTO categories(category,parent,image) VALUES('$category','$post_parent','$dbpath')";
        if (isset($_GET['edit'])) {
            $update_cat = "UPDATE categories SET category='$category', parent='$post_parent', image='$dbpath' WHERE id='$edit_id'";
        }
        $db->query($update_cat);
        if (isset($_GET['edit'])) {
            $success_new_cat = '<ul class="bg-success"><li class="text-success">Category ' . $category . ' Edited. </li></ul>';
        } else {
            $success_new_cat = '<ul class="bg-success"><li class="text-success">Category ' . $category . ' Added. </li></ul>';
        }
        ?>
        <script>
            jQuery('document').ready(function () {
                jQuery('#eroor').html('<?= $success_new_cat ?>');
            });
        </script>
        <?php
        header("refresh:2;url=categories.php");
    }
}
if (isset($_GET['edit'])) {
    $category_value = $e_category['category'];
    $parent_value = $e_category['parent'];
    $cat_img=$e_category['image'];
} else {
    if (isset($_POST)) {
        $category_value = $category;
        $parent_value = $post_parent;
    }
}
?>

<h2 class="text-center">Categories</h2><hr>
<div class="row">
    <div class="col-md-6">
        <legend><?= (isset($_GET['edit'])) ? 'Edit ' : 'Add New'; ?> Category</legend>
        <div id="eroor"></div>
        <form action="categories.php<?= ((isset($_GET['edit'])) ? '?edit=' . $edit_id : ''); ?>" method="post" class="form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="parent">Parent</label>
                <select class="form-control" name="parent" id="parent">
                    <option value="0" <?= ($parent_value == 0) ? 'selected="selected"' : ''; ?>>Parent</option>
<?php while ($parent = mysqli_fetch_assoc($result)): ?>
                        <option value="<?= $parent['id']; ?>" <?= ($parent_value == $parent['id']) ? 'selected="selected"' : ''; ?>><?= $parent['category']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" class="form-control" name="category" value="<?= $category_value; ?>">
            </div>
            <div form-group>
<?php if (isset($_GET['edit'])&&!empty($cat_img)): ?>
                <div class="saved-image text-center col-md-12"><img src="<?= '../' . $cat_img; ?>" alt="Saved Image" class="thumbnail img-thumbnail">
                    <a href="categories.php?delete_cat_img=1&edit=<?= $edit_id ?>" class="btn btn-danger btn-sm" style="margin-top: -150px;">Delete Image</a><br><hr>
                </div>
            <?php else: ?>
                    <label for="photo">Picture:</label>
                    <input type="file" class="form-control " name="cat_photo" id="cat_photo">
              <?php                    endif;?>
            </div>
            <div class="form-group">
                <br><hr>
                <input type="submit" class="form-control btn btn-success" value="<?= isset($_GET['edit']) ? 'Edit ' : 'Add '; ?>Category">
            </div>
        </form>
    </div>
    <!-- category table-->
    <div class="col-md-6" style="overflow-y: auto;">
<?php
$result1 = $db->query($sql);
while ($parents = mysqli_fetch_assoc($result1)):
    $parent_id = $parents['id'];
    $sql2 = "SELECT * FROM categories WHERE parent='$parent_id' AND deleted=0";
    $cresult = $db->query($sql2);
    ?>

            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#collapse<?= $parents['id']; ?>">
    <?= $parents['category']; ?>
                            </a>
                            <span style="float: right;">

                                <input type="button" class="btn btn-xs btn-danger" value="Remove" onClick="del_modal(<?= $parents['id']; ?>)">


                            </span>

                        </h4>
                    </div>
                    <div id="collapse<?= $parents['id']; ?>" class="panel-collapse collapse">
                        <ul class="list-group">
    <?php while ($child = mysqli_fetch_assoc($cresult)): ?>
                                <li class="list-group-item text-left">
                                <?= $child['category']; ?>

                                    <span style="float: right;">
                                        <a href="categories.php?edit=<?= $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                                        <a href="categories.php?c_delete=<?= $child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
                                    </span>
                                </li>
    <?php endwhile; ?>
                        </ul>
                        <div class="panel-footer"><a href="categories.php?edit=<?= $parents['id']; ?>" class="btn btn-sm btn-info  ">Edit Parent</a></div>
                    </div>
                </div>
            </div>
<?php endwhile; ?>
    </div>
</div>


<?php include './includes/footer.php'; ?>

