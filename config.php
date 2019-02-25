<?php 
define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/Specter_Shop/');
define('CART_COOKIE','1AbbaS2HaideR3');
define('CART_COOKIE_EXPIRE',time()+(86400*30));
define('TAXRATE',0.15);
define('CURRENCY','INR');
define('CHECKOUTMODE','TEST'); //for devlopment for projct ONLY

if(CHECKOUTMODE=='TEST'){
    define('STRIPE_PRIVATE','sk_test_4EoSmTTZmqFmoyYI9ltCOuNm');
    define('STRIPE_PUBLIC','pk_test_ChucZLi09cfdgV9W0aU7AzMs');
}
if(CHECKOUTMODE=='LIVE'){
    define('STRIPE_PRIVATE','sk_live_0uzz6jVRSEnswobjIWkJcXMj');
    define('STRIPE_PUBLIC','pk_live_f5GBqWwjV1TBgqIPsNThpjAJ');
}
