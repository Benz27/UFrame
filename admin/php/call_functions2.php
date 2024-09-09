<?php
$x=$_GET['x'];

session_start();
date_default_timezone_set("Asia/Manila");
include("conn.php");


switch ($x) {
    case "get_table_content0":
        include("inventory_adjustment.php");
        get_table_content0($link,$_POST['items']);
    break;
    
    
    
}

