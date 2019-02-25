<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
if (!is_logged_in()) {
    login_error_redirect();
}
if (!has_permission('editor')) {
    permission_error_redirect('index.php');
}
include 'includes/head.php';
include 'includes/navigation.php';
$a_product_Q = $db->query("SELECT * FROM products WHERE deleted=1");
$a_categories_Q = $db->query("SELECT * FROM categories WHERE deleted=1");
$a_brands_Q = $db->query("SELECT * FROM brand WHERE deleted='1'");
$a_users_Q = $db->query("SELECT * FROM users WHERE deleted=1");
$errors = array();
if (isset($_GET['restore_product'])) {
    $restore_id = sanitizer($_GET['restore_product']);
    $db->query("UPDATE products SET deleted=0 WHERE id='$restore_id'");
    $restore_msg = array('Product Sucessfully Restored.');
    //echo display_msg($restore_msg);
    header("Location:archived.php?products=1");
}
if (isset($_GET['delete_product'])) {
    $delete_id = sanitizer($_GET['delete_product']);
    $db->query("DELETE FROM products WHERE id='$delete_id'");
    //echo '<br><ul class="bg-danger"><li class="text-danger">Brand Deleted.</li></ul>';
    header("Location:archived.php?products=1");
}
if (isset($_GET['restore_brand'])) {
    $restore_id = sanitizer($_GET['restore_brand']);
    $db->query("UPDATE brand SET deleted=0 WHERE id='$restore_id'");
    //$restore_msg = array('Product Sucessfully Restored.');
    //echo display_msg($restore_msg);
    header("Location:archived.php?brands=1");
}
if (isset($_GET['delete_brand'])) {
    $delete_id = sanitizer($_GET['delete_brand']);
    $db->query("DELETE FROM brand WHERE id='$delete_id'");
    //echo '<br><ul class="bg-danger"><li class="text-danger">Brand Deleted.</li></ul>';
    header("Location:archived.php?brands=1");
}
if (isset($_GET['restore_cat'])) {
    $restore_id = sanitizer($_GET['restore_cat']);
    $db->query("UPDATE categories SET deleted=0 WHERE id='$restore_id'");
    //$restore_msg = array('Product Sucessfully Restored.');
    //echo display_msg($restore_msg);
    header("Location:archived.php?categories=1");
}
if (isset($_GET['delete_cat'])) {
    $delete_id = sanitizer($_GET['delete_cat']);
    $db->query("DELETE FROM categories WHERE id='$delete_id'");
    //echo '<br><ul class="bg-danger"><li class="text-danger">Brand Deleted.</li></ul>';
    header("Location:archived.php?categories=1");
}
if (isset($_GET['restore_user'])) {
    $restore_id = sanitizer($_GET['restore_user']);
    $db->query("UPDATE users SET deleted=0 WHERE id='$restore_id'");
    //$restore_msg = array('Product Sucessfully Restored.');
    //echo display_msg($restore_msg);
    header("Location:archived.php?users=1");
}
if (isset($_GET['delete_user'])) {
    $delete_id = sanitizer($_GET['delete_user']);
    $db->query("DELETE FROM users WHERE id='$delete_id'");
    //echo '<br><ul class="bg-danger"><li class="text-danger">Brand Deleted.</li></ul>';
    header("Location:archived.php?users=1");
}
if (isset($_GET['products'])) :
    ?>
    <div class = "panel panel-heading">
        <div class = "panel-body">
            <h2 class = "text-center"> ARCHIVED PRODUCTS </h2>
        </div>

    </div>


    <table class = "table table-bordered table-condensed table-striped order-table" id = "table">

        <thead><th class="text-center">Restore</th><th class = "text-center">Product</th><th class = "text-center">Price</th>
        <th class = "text-center">Category</th><th class="text-center">Delete</th></thead>

    <tbody>
    <?php
    while ($arch_product = mysqli_fetch_assoc($a_product_Q)):
        $chilID = $arch_product['categories'];
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
                <td class="text-center">
                    <a href="archived.php?restore_product=<?= $arch_product['id'] ?>" class="btn btn-xs btn-default">
                    <span class="glyphicon glyphicon-refresh"></span></a>
                </td>
                <td><?= $arch_product['name']; ?></td>
                <td><?= money($arch_product['price']); ?></td>
                <td><?= $category; ?></td>
                <td class="text-center"><a href="archived.php?delete_product=<?= $arch_product['id']; ?>" class="btn btn-xs btn-default btn-danger"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
    <?php endwhile; ?>
    </tbody>
    </table> 

<?php
endif;
if (isset($_GET['brands'])):
    ?>   
<h2 class="text-center">Brands</h2>
<hr>
    <table class="table table-bordered table-condensed table-striped order-table" id="table">
        <thead class="">       
        <th class='text-center'>Restore</th>
        <th class='text-center'>Brand Name</th>
        <th class='text-center'>Delete Permanently</th>
    </thead>
    <tbody>
    <?php while ($brand = mysqli_fetch_assoc($a_brands_Q)): ?>
            <tr> 
                <td class="text-center"><a href="archived.php?restore_brand=<?= $brand['id']; ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-refresh"></span></a></td>
                <td><font face="arial" size="4"> <?= $brand['brand_name']; ?></font></td>
                <td class="text-center"><a href="archived.php?delete_brand=<?= $brand['id']; ?>" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
        <?php endwhile;
    ?>

    </tbody>
    </table>


<?php
endif;
if (isset($_GET['categories'])):
    ?><?php
    
    ?>
<h2 class="text-center">Categories</h2>
<hr>
    <table class="table table-bordered table-condensed table-striped order-table" id="table">
        <thead class="">       
        <th class='text-center'>Restore</th>
        <th class='text-center'>Brand Name</th>
        <th class='text-center'>Delete Permanently</th>
    </thead>
    <tbody>
    <?php while ($cat = mysqli_fetch_assoc($a_categories_Q)): 
        $catChild=$cat['parent'];
        $cat_P=  mysqli_fetch_assoc($db->query("SELECT * FROM categories WHERE id='$catChild'"));
        
        ?>
            <tr> 
                <td class="text-center"><a href="archived.php?restore_cat=<?= $cat['id']; ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-refresh"></span></a></td>
                <td><font face="arial" size="4"> <?= $cat_P['category'] . '-' . $cat['category']; ?></font></td>
                <td class="text-center"><a href="archived.php?delete_cat=<?= $cat['id']; ?>" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span></a></td>
            </tr>
        <?php endwhile;
    ?>

    </tbody>
    </table>

<?php
endif;
if (isset($_GET['users'])){
if(has_permission('admin')):    

    ?>
<h2 class="text-center">Users</h2><hr>
   
    <table class="table table-bordered table-striped table-responsive table-condensed">
        <thead><th class="text-center">Restore</th><th class="text-center">Name</th>
        <th class="text-center">E-mail</th><th class="text-center">Last Login</th><th class="text-center">Join Date</th>
        <th class="text-center">Permissions</th><th class="text-center">Delete Permanently</th></thead>
    <tbody>
        <?php while ($user = mysqli_fetch_assoc($a_users_Q)): ?>
        <tr>
                <td class="text-center"><a href="archived.php?restore_user=<?= $user['id']; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-refresh"></span></a>      
                </td> 
                <td><?= $user['full_name']; ?></td>
                <td><?= $user['email']; ?></td>
                <td><?=($user['last_login']=='0000-00-00 00:00:00')?'Never':pretty_date($user['last_login']); ?></td>
                <td><?= pretty_date($user['join_date']); ?></td>
                <td><?= $user['permissions']; ?></td>
                <td class="text-center"><a href="archived.php?delete_user=<?= $user['id']; ?>" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span></a>      
                </td> 
            </tr>

        <?php endwhile; ?>

    </tbody>
    </table>

<?php else:
 permission_error_redirect('index.php');
endif;
}
include 'includes/footer.php';
