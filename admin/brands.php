<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/core/init.php';
if(!is_logged_in()){
    login_error_redirect();
}
include './includes/head.php';
include './includes/navigation.php';
$sql = "SELECT * FROM brand WHERE deleted=0 ORDER BY brand_name";
$result = $db->query($sql);
$errors = array();
$page=1;

//editing a brand
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_id = sanitizer((int) $_GET['edit']);
    $sqle = "SELECT * FROM brand WHERE id='$edit_id' AND deleted=0";
    $ed_res = $db->query($sqle);
    $edit_brand = mysqli_fetch_assoc($ed_res);
}

//deleting a brand
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = sanitizer((int) $_GET['delete']);
    $sql = "SELECT * FROM brand WHERE id='$delete_id' AND deleted=0";
    $res = $db->query($sql);
    $del_brand = mysqli_fetch_assoc($res);
    $sql = "UPDATE brand SET deleted=1 WHERE id='$delete_id'";
    $db->query($sql);
    echo '<br><ul class="bg-danger"><li class="text-danger">Brand Deleted.</li></ul>';
    header("refresh:2;url=brands.php");
}
//if add form is submitted
if (isset($_POST['add_submit'])) {
    $new_brand = sanitizer($_POST['brand']);
    $imageDir = BASEURL . 'images/brands/';
    $img = $imageDir . basename($_FILES['brand_image']['name']);
    $imgType = pathinfo($img, PATHINFO_EXTENSION);

    if ($new_brand == '') {
        //chk empty
        $errors[] .='You must enter a Brand!';
    }
    $sql2 = "SELECT * FROM brand WHERE brand_name='$new_brand'";
    if (isset($_GET['edit'])) {
        $sql2 = "SELECT * FROM brand WHERE brand_name='$new_brand' AND id!='$edit_id'";
    }
    $count = mysqli_num_rows($db->query($sql2));
    if ($count > 0) {
        $errors[].=$new_brand . ' already exists, kindly enter another brand.';
    }
    if (file_exists($img)) {
        $errors[].='Selected File already exists';
    }
    $allwdExt=array('jpg','JPG','png','PNG','bmp','BMP','jpeg','JPEG');
    if (!in_array($imgType, $allwdExt)) {
        $errors[].='File should be of jpg, bmp, jpeg or png type.';
    }


    if (!empty($errors)) {
        echo  display_errors($errors);
    } else {
        //add brand

        if (move_uploaded_file($_FILES["brand_image"]["tmp_name"], $imageDir . $new_brand . '.' . $imgType)) {
            $location ='images/brands/' . $new_brand . '.' . $imgType;
            $sql = "INSERT INTO brand (brand_name,image) VALUES ('$new_brand','$location')";

            if (isset($_GET['edit'])) {
                $sql = "UPDATE brand SET brand_name='$new_brand',image='$location' WHERE id='$edit_id'";
            }
            $db->query($sql);
            echo '<br><ul class="bg-success"><li class="text-success">BRAND ' . $new_brand . ' ADDED </li></ul>';
        } else {
            echo '<ul class="bg-danger"><li class="text-danger">Upload failed</li></ul>';
        }
        header("refresh:2;url=brands.php");
    }
}

?>
<script src="../js/Search-table.js"></script>
<h2 class="text-center">Brands</h2>
<hr>
<!-- Brand Form-->

<div class="text-center">
    <form class="form-inline" action="brands.php<?= (isset($_GET['edit'])) ? '?edit=' . $edit_id : ''; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="brand">
                <?= (isset($_GET['edit'])) ? 'Edit ' : 'Add a '; ?>Brand:</label>
            <input type="text" name="brand" id="brand" class="form-control" value="<?= (isset($_GET['edit'])) ? sanitizer($edit_brand['brand_name']) : ((isset($_POST['brand'])) ? sanitizer($_POST['brand']) : ''); ?>" placeholder="Enter Brand Name">
            <input type="file" name="brand_image" id="brand_image" class="form-control" placeholder="Choose Brand logo" >&nbsp;
            <?php if (isset($_GET['edit'])): ?>
                <a href="brands.php" class="btn btn-default">Cancel</a>
            <?php endif; ?>
            <input type="submit" name="add_submit" class="btn btn-success" value="<?= (isset($_GET['edit'])) ? 'Edit ' : 'Add '; ?>Brand">

        </div>


    </form>
</div>
<hr>
<div class="text-center">
    <form class="form-inline">
        <div class="form-group">
            <input type="search" class="light-table-filter form-control" 
                   style="text-align: center" data-table="order-table" placeholder="Search Brand">
        </div></form></div>
<hr>
<table class="table table-bordered table-responsive table-striped table-hover" id="table">
    <thead class="">
    <th>Brand Logo</th>
    <th>Brand Name</th>
    <th>Edit</th>
    <th>Delete</th>
</thead>
<tbody>
    <?php 
   
    while ($brand = mysqli_fetch_assoc($result)): ?>
        <tr> 
            <td><div class="logo-container"><img src="<?= '../'.$brand['image']; ?>" class="img-responsive img-thumbnail img-fluid" ></div></td>
            <td><font face="arial" size="4"> <?= $brand['brand_name']; ?></font></td>
            <td><a href="brands.php?edit=<?= $brand['id']; ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
            <td><a href="brands.php?delete=<?= $brand['id']; ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-remove"></span></a></td>
        </tr>
    <?php 
    
    endwhile; ?>
        
</tbody>
</table>

<?php include './includes/footer.php'; ?>

