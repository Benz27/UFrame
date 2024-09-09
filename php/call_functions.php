<?php
$x=$_GET['x'];

session_start();
include("conn.php");


switch ($x) {
    case "trc":
        include("cart.php");
        trc($link);
        break;
    case "savinf":
        include("info.php");
        savinf($link);
        break;
    case "chngpass":
        include("security.php");
        chngpass($link);
        break;
    case "delacc":
        include("security.php");
        delacc($link);
    break;
    case "addr_add":
        include("address.php");
        addr_add($_POST['street'],$_POST['city'],$_POST['prov'],$_POST['email'],$_POST['phone'],$_POST['sel'],$link);
    break;
    case "addr_edt":
        include("address.php");
        addr_edt($_POST['id'],$_POST['street'],$_POST['city'],$_POST['prov'],$_POST['email'],$_POST['phone'],$link);
    break;
    case "addr_rem":
        include("address.php");
        addr_rem($_POST['id'],$link);
    break;
    case "swtch_def":
        include("address.php");
        swtch_def($_POST['id'],$link);
    break;
    case "addr_list_chk":
        include("address.php");
        addr_list_chk();
    break;
    case "get_categories":
        include("category.php");
        echo get_categories($link);
    break;
    case "get_purchase":
        include("purchase.php");
        get_purchase($link);
    break;
    case "cancel_purchase":
        include("purchase.php");
        echo cancel_purchase($link,$_POST['id']);
    break;
    
}

