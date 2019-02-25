<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
if (!is_logged_in()) {
    login_error_redirect();
}
include 'includes/head.php';
include 'includes/navigation.php';
$dbpath = '';
if (isset($_GET['delete'])) {
    $did = sanitizer($_GET['delete']);
    $db->query("UPDATE products SET deleted=1 WHERE id='$did'");
    header('Location:products.php');
}
if (isset($_GET['add']) || isset($_GET['edit'])) {
    $brandQuery = $db->query("SELECT * FROM brand ORDER BY brand_name");
    $productChk = $db->query("SELECT * FROM products");
    $parentQuery = $db->query("SELECT * FROM categories WHERE parent=0 ORDER BY category");
    $title = ((isset($_POST['title']) && !empty($_POST['title'])) ? sanitizer($_POST['title']) : '');
    $brand = ((isset($_POST['brand']) && !empty($_POST['brand'])) ? sanitizer($_POST['brand']) : '');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent'])) ? sanitizer($_POST['parent']) : '');
    $category = ((isset($_POST['child']) && !empty($_POST['child'])) ? sanitizer($_POST['child']) : '');
    $price = ((isset($_POST['price']) && !empty($_POST['price'])) ? sanitizer($_POST['price']) : '');
    $list_price = ((isset($_POST['list_price'])) ? sanitizer($_POST['list_price']) : '');
    $description = ((isset($_POST['description'])) ? sanitizer($_POST['description']) : '');
    $sizes = rtrim(((isset($_POST['sizes']) && !empty($_POST['sizes'])) ? sanitizer($_POST['sizes']) : ''), ',');
    $saved_img = '';
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $products = mysqli_fetch_assoc($db->query("SELECT * FROM products WHERE id=$edit_id"));
        if (isset($_GET['delete_img'])) {
            $imagei = (int) $_GET['imagei'] - 1;
            $images = explode(',', $products['image']);
            $imag_url = BASEURL . $images[i];
            unset($imag_url);
            unset($images[$imagei]);
            $imageStr = implode(',', $images);
            $db->query("UPDATE products SET image='$imageStr' WHERE id='$edit_id'");
            header('Location:products.php?edit=' . $edit_id);
        }
        $title = ((isset($_POST['title']) && !empty($_POST['title'])) ? sanitizer($_POST['title']) : $products['name']);
        $brand = ((isset($_POST['brand']) && !empty($_POST['brand'])) ? sanitizer($_POST['brand']) : $products['brand']);
        $category = ((isset($_POST['child']) && !empty($_POST['child'])) ? sanitizer($_POST['child']) : $products['categories']);
        $parentRes = mysqli_fetch_assoc($db->query("SELECT * FROM categories WHERE id='$category'"));
        $parent = ((isset($_POST['parent']) && !empty($_POST['parent'])) ? sanitizer($_POST['parent']) : $parentRes['parent']);
        $price = ((isset($_POST['price']) && !empty($_POST['price'])) ? sanitizer($_POST['price']) : $products['price']);
        $list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price'])) ? sanitizer($_POST['list_price']) : $products['list_price']);
        $description = ((isset($_POST['description']) && !empty($_POST['description'])) ? sanitizer($_POST['description']) : $products['description']);
        $sizes = rtrim(((isset($_POST['sizes']) && !empty($_POST['sizes'])) ? sanitizer($_POST['sizes']) : $products['sizes']), ',');
        $saved_img = ($products['image'] != '') ? $products['image'] : '';
        $dbpath = $saved_img;
    }
    if (!empty($sizes)) {
        $sizeString = sanitizer($sizes);
        $sizeArr = explode(',', $sizeString);
        $sArr = array();
        $qArr = array();
        $tArr=[];
        foreach ($sizeArr as $ss) {
            $s = explode(':', $ss);
            $sArr[] = $s[0];
            $qArr[] = $s[1];
            $tArr[]=$s[2];
        }
    } else {
        $sizeArr = array();
    }
    if ($_POST) {
        $errors = array();
        $fileExt = '';
        $required = array('title', 'brand', 'parent', 'child', 'price', 'sizes');
        $extAllowed = array('png', 'jpg', 'jpeg', 'bmp', 'PNG', 'BMP', 'JPG', 'JPG');
        $tmpLoc = array();
        $uploadPath = array();
        if (isset($_GET['add'])) {
            while ($productLst = mysqli_fetch_assoc($productChk)) {
                if ($title == $productLst['name'] && $category == $productLst['categories'] && $brand == $productLst['brand'] && $price == $productLst['price']) {
                    $errors[] = "The product $title already exists.";
                    break;
                }
            }
        }
        foreach ($required as $field) {
            if ($_POST[$field] == '') {
                $errors[] = 'All required fields must be filled.';
                break;
            }
        }
if(!empty($_FILES['photo']['name'])){
        $photo_count = count($_FILES['photo']['name']);
}else{
    $photo_count=0;
}
        if ($photo_count > 0) {
            for ($i = 0; $i < $photo_count; $i++) {
                $name = $_FILES['photo']['name'][$i];
                $nameArr[] = explode('.', $name);
                $fileName = $nameArr[$i][0];
                $fileExt = $nameArr[$i][1];
                $mime = explode('/', $_FILES['photo']['type'][$i]);
                $mimeType = $mime[0];
                $mimeExt = $mime[1];
                $tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
                $fileSize = $_FILES['photo']['size'][$i];
                $uploadName = md5(microtime() . $i) . '.' . $fileExt;
                $uploadPath[] = BASEURL . 'images/products/' . $uploadName;
                if ($i != 0) {
                    $dbpath.=',';
                }
                $dbpath .= 'images/products/' . $uploadName;
                $d = 0;
                if ($mimeType != 'image') {
                    $errors[] = 'The file must be an Image,' . ' for the' . ($i + 1) . 'th Image';
                    $d = 1;
                }
                if (!in_array($fileExt, $extAllowed) && $d == 0) {
                    $errors[] = $fileExt . 'The file must be of png, jpg, jpeg or bmp format,' . ' for the' . ($i + 1) . 'th Image';
                    $d = 1;
                }
                if ($fileSize > 10000000 && $d == 0) {
                    $errors[] = 'The file size must ot exceed 10MB,' . ' for the' . ($i + 1) . 'th Image';
                    $d = 1;
                }
            }
        }

        if (!empty($errors)) {
            echo display_errors($errors);
        } else {
            //go ahead
            if ($photo_count > 0) {
                for ($i = 0; $i < $photo_count; $i++) {
                    move_uploaded_file($tmpLoc[$i], $uploadPath[$i]);
                }
            }
            $insertSql = "INSERT INTO products (name,price,list_price,brand,categories,image,description,sizes)"
                    . " VALUES('$title','$price','$list_price','$brand','$category','$dbpath','$description','$sizes')";
            $r = '<br><ul class="bg-success"><li class="text-success">Product ' . $title . ' ADDED. </li></ul>';
            if (isset($_GET['edit'])) {
                $insertSql = "UPDATE products SET name='$title', price='$price',sizes='$sizes', list_price='$list_price', brand='$brand', categories='$category', image='$dbpath', description='$description' WHERE id='$edit_id'";
                $r = '<br><ul class="bg-success"><li class="text-success">Product ' . $title . ' EDITED. </li></ul>';
            }
            $db->query($insertSql);
            echo $r;
            header('refresh:2;url=products.php');
        }
    }
    ?>
    <h2 class="text-center"><?= (isset($_GET['edit'])) ? 'Edit ' : 'Add new'; ?> Product </h2>
    <hr>
    <form action="products.php?<?= (isset($_GET['edit'])) ? "edit=$edit_id" : 'add=1'; ?>" method="POST" enctype="multipart/form-data">
        <div class="col-md-3 form-group">
            <label for="title">Title<font color="RED"">* </font>:</label>
            <input type="text" name="title" id="title" class="form-control" value="<?= $title; ?>" placeholder="Enter the name of the product"/>
        </div>
        <div class="col-md-3 form-group">
            <label for="title">Brand<font color="RED" >* </font>:</label>
            <select class="form-control" id="brand" name="brand">
                <option value=""<?= $brand == '' ? ' selected' : '' ?>></option>
                <?php while ($b = mysqli_fetch_assoc($brandQuery)): ?>
                    <option value="<?= $b['id']; ?>"<?= ($brand == $b['id'] ? ' selected' : ''); ?>><?= $b['brand_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label for="parent">Parent Category<font color="RED">* </font>:</label>
            <select class="form-control" id="parent" name="parent">
                <option value=""<?= $parent == '' ? ' selected' : '' ?>></option>
                <?php while ($p = mysqli_fetch_assoc($parentQuery)): ?>
                    <option value="<?= $p['id']; ?>"<?= ($parent == $p['id'] ? ' selected' : ''); ?>>
                        <?= $p['category']; ?></option>
                    <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label for="child">Child Category<font color="RED">* </font>:</label>
            <select class="form-control" id="child" name="child"> 
                <option value="">Select the Parent first</option>
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label for="price">Price<font color="RED">* </font> (in ₹):</label>
            <input type="text" id="price" class="form-control" placeholder="Shop price of the product" name="price" 
                   value="<?= $price; ?>">
        </div>
        <div class="col-md-3 form-group">
            <label for="price">List Price (in ₹):</label>
            <input type="text" id="list_price" class="form-control" placeholder="List price of the product" name="list_price" 
                   value="<?= $list_price; ?>">
        </div>
        <div class="col-md-3 form-group">
            <label>Select :</label>
            <button class="btn btn-info form-control" onclick="jQuery('#sizesModal').modal('toggle');
                    return false;">Quantity & Sizes</button>
        </div> 
        <div class="col-md-3 form-group">

            <label for="price">Quantity & Sizes Preview :</label>
            <input type="text" class="form-control" name="sizes" id="sizes" value="<?= $sizes; ?>" readonly>
        </div> 
        <div class="col-md-6 form-group saved-image"><hr width="500px" class="pull-left">
            <?php if ($saved_img != ''): ?>
                <?php $imagei = 1;
                $images = explode(',', $saved_img);
                ?>
                <?php foreach ($images as $image): ?>


                    <div class="saved-image text-center col-md-4"><br><img src="<?= '../' . $image ?>" alt="Saved Image" class="thumbnail center-block" align="center">
                        <br><a href="products.php?delete_img=1&edit=<?= $edit_id ?>&imagei=<?= $imagei; ?>" class="btn btn-danger btn-sm" style="margin-top: -25px;">Delete Image</a>
                    </div>
                    <?php $imagei++;
                endforeach;
                ?>
    <?php else: ?>
                <label for="photo">Picture<font color="RED">* </font>:</label>
                <input type="file" class="form-control " name="photo[]" id="photo" multiple>
    <?php endif; ?>
        </div>
        <div class="col-md-6 form-group">
            <label for="description">Description :</label>
            <textarea class="form-control" name="description" id="description" rows="6" placeholder="Decription of the product"><?= $description; ?></textarea> 
        </div>
        <div class="form-group pull-right col-md-3">
            <input type="submit" class="btn btn-lg btn-success col-md-6" value="<?= (isset($_GET['edit'])) ? 'Edit' : 'Add'; ?> Product">
            <a href="products.php" class="btn btn-default btn-lg col-md-4 pull-right">Cancel</a>

        </div>
    </form>
    <!-- Modal -->

    <div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="sizesModalLabel">Sizes & Quantities</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
    <?php for ($i = 1; $i <= 12; $i++): ?>
                            <div class="col-md-2 form-group">
                                <label for="sizze<?= $i; ?>">Size:</label>
                                <input type="text" name="sizze<?= $i; ?>" id="sizze<?= $i ?>" value="<?= (!empty($sArr[$i - 1])) ? $sArr[$i - 1] : ''; ?>" class="form-control"> 
                            </div>
                            <div class="col-md-2 form-group">
                                <label for="qty<?= $i; ?>">Quantity:</label>
                                <input type="number" name="qty<?= $i; ?>" id="qty<?= $i ?>" value="<?= (!empty($qArr[$i - 1])) ? $qArr[$i - 1] : ''; ?>" class="form-control" min="0"> 
                            </div>
                        <div class="col-md-2 form-group">
                                <label for="qty<?= $i; ?>">Threshold:</label>
                                <input type="number" name="threshold<?= $i; ?>" id="threshold<?= $i ?>" value="<?= (!empty($tArr[$i - 1])) ? $tArr[$i - 1] : ''; ?>" class="form-control" min="0"> 
                         </div>
    <?php endfor; ?> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateSizes();
                            jQuery('#sizesModal').modal('toggle');
                            return false;">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <?php
}else {

    $sql = "SELECT * FROM products WHERE deleted=0";
    $presults = $db->query($sql);
    if (isset($_GET['featured'])) {
        $id = (int) $_GET['id'];
        $featured = (int) $_GET['featured'];
        $featuredsql = "UPDATE products SET featured=$featured WHERE id=$id";
        $db->query($featuredsql);
        header('location: products.php');
    }
    ?>
    <div class="panel panel-heading">
        <div class="panel-body">
            <h2 class="text-center"> PRODUCTS </h2>
            <a href="products.php?add=1" class="btn btn-success btn-lg pull-right" style="margin-top: -45px;">Add Product</a><div class="clearfix"></div>
        </div>
        <form class="form-inline text-center">
            <div class="form-group">
                <input type="search" class="light-table-filter form-control" 
                       style="text-align: center" data-table="order-table" placeholder="Search Product">
            </div></form>
    </div>
    <table class="table table-bordered table-condensed table-striped order-table" id="table">
        <thead><th></th><th class="text-center">Product</th><th class="text-center">Price</th><th class="text-center">Category</th><th class="text-center">Featured</th><th class="text-center">Sold</th></thead>
    <tbody>
        <?php
        while ($product = mysqli_fetch_assoc($presults)):
            $chilID = $product['categories'];
            $catsql = "SELECT * FROM categories WHERE id='$chilID'";
            $resultcat = $db->query($catsql);
            $child = mysqli_fetch_assoc($resultcat);
            $parentID = $child['parent'];
            $psql = "SELECT * FROM categories WHERE id='$parentID'";
            $pres = $db->query($psql);
            $parent = mysqli_fetch_assoc($pres);
            $category = $parent['category'] . '-' . $child['category'];
            ?>
            <tr>
                <td class="text-right">
                    <a href="products.php?edit=<?= $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="products.php?delete=<?= $product['id'] ?>" class="btn btn-xs btn-default">
                        <span class="glyphicon glyphicon-trash"></span></a>
                </td>
                <td><?= $product['name']; ?></td>
                <td><?= money($product['price']); ?></td>
                <td><?= $category; ?></td>
                <td><a href="products.php?featured=<?= (($product['featured'] == 0) ? '1' : '0'); ?>&id=<?= $product['id']; ?>" class="btn btn-xs btn-default">
                        <span class="glyphicon glyphicon-<?= (($product['featured'] == 1 ? 'minus' : 'plus')); ?>"></span></a>
                    &nbsp <?= (($product['featured'] == 1) ? 'Featured Product' : ''); ?>
                </td>
                <td></td>
            </tr>
    <?php endwhile; ?>
    </tbody>
    </table>
<?php } include 'includes/footer.php';
?>
<script>
    jQuery('document').ready(function () {
        get_child_options('<?= $category; ?>');
    });

</script>
<script src="../js/Search-table.js"></script>

