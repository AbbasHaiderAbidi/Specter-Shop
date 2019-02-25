<?php
function display_errors($errors){
    $display='<br><ul class="bg-danger">';
    foreach ($errors as $error) {
        $display.='<li class="text-danger">'.$error.'</li>';
    }
    $display.='</ul>';
    return $display;
}
function display_msg($msgs){
    $display='<br><ul class="bg-success">';
    foreach ($msgs as $msg) {
        $display.='<li class="text-success">'.$msg.'</li>';
    }
    $display.='</ul>';
    return $display;
}
function sanitizer($dirty){
    return htmlentities((string)$dirty, ENT_QUOTES, "UTF-8");
}
function money($number){
    return '₹'.number_format($number,2);
}
function login($user_id){
    $_SESSION['SBUser']=$user_id;
}
function is_logged_in(){
    if(isset($_SESSION['SBUser'])&&$_SESSION['SBUser']>0){
        return true;
    }
    return false;
}
function login_error_redirect($url='login.php'){
    $_SESSION['error_flash']='You must be logged in to access this page.';
    header('Location:'.$url);
}
function has_permission($permission='admin'){
    global $user_data;
    $permissions=  explode(',', $user_data['permissions']);
    if(in_array($permission, $permissions,true)){
        return true;
    }
    return false;
}
function permission_error_redirect($url='login.php'){
    $_SESSION['error_flash']='You do not have permission to access that page.';
    header('Location:'.$url);
}
function pretty_date($date){
    return date("M d, Y h:i A",  strtotime($date));
}
function get_category($child_id){
    global $db;
    $id=  sanitizer($child_id);
    $SQLquery="SELECT p.id AS 'pid',p.category AS 'parent', c.id AS 'cid', c.category AS 'child',c.image AS 'image' FROM categories c INNER JOIN
 categories p ON c.parent=p.id WHERE c.id='$id'";
    $category=  mysqli_fetch_assoc($db->query($SQLquery));
    return $category;
}
function sizesToArray($str){
    $sizesArray=  explode(',', $str);
    
    $returnArray=array();
    for($i=0;$i<count($sizesArray);$i++){
        $s=  explode(':', $sizesArray[$i]);
//        var_dump($sizesArray[$i]);
       
        $returnArray[]=array(
            "size"=>$s[0],
            "quantity"=>$s[1],
            "threshold"=>$s[2]
        );
    }
     return $returnArray;
}
function sizesToStr($sizes){
    $sizeStr='';
    foreach($sizes as $size){
        $sizeStr.=$size['size'].':'.$size['quantity'].':'.$size['threshold'].',';
    }
    return rtrim($sizeStr,',');
}