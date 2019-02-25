<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Specter_Shop/core/init.php';
if (!is_logged_in()) {
    header("Location:login.php");
}
include './includes/head.php';
include './includes/navigation.php';
$user_currnt = $_SESSION['SBUser'];
if (isset($_GET['change'])) {
    if (!empty($_FILES['change-img']['name'])) {

        $mimeExt = '';
        $mimeType = '';
        $photo = $_FILES['change-img'];
        $pname = $photo['name'];
        $nameArr[] = explode('.', $pname);
        $nameArr = $nameArr[0];
        $d = 0;
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

        if ($mimeType != 'image' && $d == 0) {
            $errors[] = 'The file must be an Image.';
            $d = 1;
        }
        if (!in_array($fileExt, $extAllowed) && $d == 0) {
            $errors[] = $fileExt . 'The file must be of png, jpg, jpeg or bmp format.';
            $d = 1;
        }
        if ($fileSize > 10000000 && $d == 0) {
            $errors[] = 'The file size must ot exceed 10MB.';
            $d = 1;
        }
        if (!empty($errors)) {
            echo display_errors($errors);
        } else {

            $user_ji = mysqli_fetch_assoc($db->query("SELECT * FROM users WHERE id='$user_currnt'"));
            $imag_url = BASEURL . $user_ji['image'];
            echo $imag_url;
            unlink($imag_url);
            move_uploaded_file($tmpLoc, $uploadPath);
            $db->query("UPDATE users SET image='$dbpath' WHERE id='$user_currnt'");
            header("Location:index.php");
        }
    }
}
?>

<!--order to complete-->
<?php
$txnQ = "SELECT t.id,t.cart_id,t.full_name,t.description,t.txn_date,t.grand_total,c.paid,c.shipped,c.item 
        FROM transactions t LEFT JOIN cart c ON t.cart_id=c.id WHERE c.paid=1 AND c.shipped=0 ORDER BY t.txn_date DESC";
$txnRes = $db->query($txnQ);
?>
<div class='col-md-12'>
    <h3 class="text-center">Orders to Ship</h3>
    <table class="table table-bordered table-striped table-condensed">
        <thead>
        <th></th>
        <th>Name</th>
        <th>Description</th>
        <th>Total</th>
        <th>Date</th>
        </thead>
        <tbody>
            <?php while ($order = mysqli_fetch_assoc($txnRes)): ?>
                <tr>
                    <td><a href="orders.php?txn_id=<?= $order['id']; ?>" class='btn btn-xs btn-info'>Details</a></td>
                    <td><?= $order['full_name']; ?></td>
                    <td><?= $order['description']; ?></td>
                    <td><?= money($order['grand_total']); ?></td>
                    <td><?= pretty_date($order['txn_date']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="row">
    <!--sales by month-->

    <?php
    $thisyr = date("Y");
    $lastyr = $thisyr - 1;
    $thisyrQ = $db->query("SELECT grand_total,txn_date FROM transactions WHERE YEAR(txn_date) ='$thisyr'");
    $lastyrQ = $db->query("SELECT grand_total,txn_date FROM transactions WHERE YEAR(txn_date) ='$lastyr'");
    $current = array();
    $last = [];
    $currentTotal = 0;
    $lastTotal = 0;
    while ($x = mysqli_fetch_assoc($thisyrQ)) {
        $month = (int) date("m", strtotime($x['txn_date']));
        if (!array_key_exists($month, $current)) {

            $current[$month] = $x['grand_total'];
        } else {

            $current[$month] = (int) $current[$month] + (int) $x['grand_total'];
        }
        $currentTotal+=$x['grand_total'];
    }
    while ($y = mysqli_fetch_assoc($lastyrQ)) {
        $month = (int) date("m", strtotime($y['txn_date']));
        if (!array_key_exists($month, $last)) {

            $last[$month] = $y['grand_total'];
        } else {

            $last[$month] = (int) $last[$month] + (int) $y['grand_total'];
        }
        $lastTotal+=$y['grand_total'];
    }
    ?>        
    <div class="col-md-4" style="margin-left: 20px;">
        <h3 class="text-left">Sales Report</h3>
        <table class='table table-bordered table-condensed table-striped'>
            <thead>
            <th>Month</th>
            <th><?= $lastyr; ?></th>
            <th><?= $thisyr; ?></th>
            </thead>
            <tbody class="panel-body">
                <?php
                for ($i = 1; $i <= 12; $i++):
                    $dt = DateTime::createFromFormat('!m', $i);
                    ?>
                    <tr<?= (date("m") == $i) ? ' class=info' : ''; ?>>
                        <td><?= (date("m") == $i) ? ' <span class="glyphicon glyphicon-triangle-right"></span>' : ''; ?><?= $dt->format("F"); ?></td>
                        <td><?= (array_key_exists($i, $last)) ? money($last[$i]) : money(0); ?></td>
                        <td><?= (array_key_exists($i, $current)) ? money($current[$i]) : money(0); ?></td>
                    </tr>
                <?php endfor; ?>
                <tr>
                    <td><b>Total</b></td>
                    <td><?= money($lastTotal); ?></td>
                    <td><?= money($currentTotal); ?></td>
                </tr>
                <?php
                if ($lastTotal < $currentTotal) {
                    $ftc = 'bg-success';
                    $ftcap = ' Improvement';
                } else {
                    $ftc = 'bg-danger';
                    $ftcap = ' Deterioration';
                }
                ?>
                <tr class="<?= $ftc; ?>">
                    <td colspan="3" class="text-center">Year Report: <?= $ftcap; ?></td></tr>
            </tbody>
        </table>
    </div>
    
    <div class="col-md-7">
        <!--inventory-->
        <?php
        $iQuery = $db->query("SELECT * FROM products WHERE deleted=0");
        $lowitems = array();
        $sizes=array();
        while ($product = mysqli_fetch_assoc($iQuery)) {
            $item = array();
            $sizes = sizesToArray($product['sizes']);
          
            foreach ($sizes as $size) {
                if ((int)$size['quantity'] <= (int)$size['threshold']) {
                    $cat = get_category($product['categories']);
                    $item = array(
                        'name' => $product['name'],
                        'size' => $size['size'],
                        'quantity' => $size['quantity'],
                        'threshold' => $size['threshold'],
                        'category' => $cat['parent'] . ' - ' . $cat['child']
                    );
                    $lowitems[] = $item;
                }
            }
        }
        ?>
        <h3 class="text-center">Low Inventory</h3>
        <table class="table table-bordered table-condensed table-striped">
            <thead>
            <th>Product</th>
            <th>Category</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Threshold</th>
            </thead>
            <tbody>
                <?php foreach ($lowitems as $item): ?>
                    <tr<?= ($item['quantity']==0) ? ' class="danger"' : ''; ?>>
                        <td><?= $item['name']; ?></td>
                        <td><?= $item['category']; ?></td>
                        <td><?= $item['size']; ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td><?= $item['threshold']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>                
        </table>
    </div>
</div>

<?php
include './includes/footer.php';
?>

