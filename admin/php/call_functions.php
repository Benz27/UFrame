<?php
$x=$_GET['x'];

session_start();
date_default_timezone_set("Asia/Manila");
include("conn.php");


switch ($x) {
    case "get_orders":
        include("ordlst.php");
        orders($link,$_GET['type'],$_GET['catg']);
    break;
    case "get_orders_today":
        include("ordlst.php");
        orders_today($link,$_GET['type'],$_GET['catg']);
    break;
    case "get_total_orders":
        include("ordlst.php");
        echo total_orders($link);
    break;
    case "get_total_orders_today":
        include("ordlst.php");
        echo total_orders_today($link);
    break;
    case "get_dates":
        include("product.php");
        echo get_dates($link);
    break;
    case "get_lst_by_date":
        include("product.php");
        get_lst_by_date($link,$_POST['start'],$_POST['end'],$_POST['stat']);
    break;
    case "v_set_inputs":
        include("product.php");
        echo v_set_inputs($link,$_POST['item_id']);
    break;
    case "set_inputs":
        include("product.php");
        echo set_inputs($link,$_POST['item_id']);
    break;
    case "del_product":
        include("product.php");
        del_product($link,$_POST['item_id']);
    break;
    case "v_del_product":
        include("product.php");
        v_del_product($link,$_POST['item_id']);
    break;
    case "count_product":
        include("product.php");
         echo count_product($link,$_POST['start'],$_POST['end'],$_POST['stat']);
    break;
    case "count_inventory":
        include("product.php");
         echo count_inventory($link);
    break;
    case "get_investment":
        include("product.php");
         echo get_investment($link);
    break;
    case "get_profit":
        include("product.php");
         echo get_profit($link);
    break;
    case "get_tax":
        include("product.php");
         echo get_tax($link);
    break;
    case "get_original_price":
        include("product.php");
         echo get_original_price($link);
    break;
    case "get_stocks":
        include("stocks.php");
        get_stocks($link,$_POST['id']);
    break;
    case "count_low_stocks":
        include("stocks.php");
       echo count_low_stocks($link);
    break;
    case "set_stock_inputs":
        include("stocks.php");
        echo set_stock_inputs($link,$_POST['item_id']);
    break;
    case "upd_restockqnt":
        include("stocks.php");
        upd_restockqnt($link,$_POST['item_id'],$_POST['qnt']);
    break;
    case "count_category":
        include("category.php");
        echo count_category($link);
    break;
    case "load_category":
        include("category.php");
        load_category($link);
    break;
    case "add_category":
        include("category.php");
        add_category($link,$_POST['name'],$_POST['desc']);
    break;
    case "upd_category":
        include("category.php");
        upd_category($link,$_POST['id'],$_POST['name'],$_POST['desc']);
    break;
    case "del_category":
        include("category.php");
        echo del_category($link,$_POST['id'],$_POST['type']);
    break;
    case "get_categories":
        include("category.php");
        echo get_categories($link);
    break;
    case "table_content0":
        include("create_delivery.php");
        table_content0($link,$_POST['app_id']);
    break;
    case "table_content1":
        include("create_delivery.php");
        table_content1($link,$_POST['app_id']);
    break;
    case "get_table_content2":
        include("inventory_adjustment.php");
        get_table_content2($link,$_GET['items']);
    break;
    case "purchase_stock":
        include("inventory_adjustment.php");
        purchase_stock($link,$_POST['items'],$_POST['qnt']);
    break;
    case "load_location":
        include("location.php");
        load_location($link);
    break;
    case "get_apps":
        include("delivery_list.php");
        get_apps($link);
    break;
    case "get_app_details":
        include("delivery_list.php");
        get_app_details($link,$_POST['app_id']);
    break;
    case "assign":
        include("create_delivery.php");
        assign($link,$_GET['items'],$_POST['app_id']);
    break;
    case "unassign":
        include("create_delivery.php");
        unassign($link,$_POST['t_id'],$_POST['r_id']);
    break;
    case "table_content1_delvlist":
        include("delvlist.php");
        table_content1_delvlist($link,$_POST['app_id']);
    break;
    case "table_content2_delvlist":
        include("delvlist.php");
        table_content2_delvlist($link);
    break;
    case "get_transac":
        include("order.php");
        get_transac($link,$_POST['t_id']);
    break;
    case "cancel_purchase":
        include("order.php");
        cancel_purchase($link,$_POST['id']);
    break;
    case "get_users":
        include("customers.php");
        get_users($link);
    break;
    case "get_user_total_orders":
        include("users.php");
        get_user_total_orders($link,$_GET['user_id']);
    break;
    case "get_user_orders":
        include("users.php");
        get_user_orders($link,$_GET['type'],$_GET['user_id']);
    break;
    case "get_user_name":
        include("users.php");
       echo get_name($link,$_GET['user_id']);
    break;
    case "get_user_email":
        include("users.php");
       echo get_user_email($link,$_GET['user_id']);
    break;
    case "get_user_spent":
        include("users.php");
       echo get_spent($link,$_GET['user_id']);
    break;
    case "get_user_order_count":
        include("users.php");
       echo get_item_count($link,$_GET['user_id']);
    break;
    case "get_user_order":
        include("users.php");
       echo get_item_count($link,$_GET['user_id']);
    break;
    case "get_frequent_order":
        include("users.php");
       echo get_frequent_order($link,$_GET['user_id']);
    break;
    case "restock":
        include("stocks.php");
       echo upd_restock($link,$_POST['item_id'],$_POST['qnt']);
    break;
    case "stock_adjustments":
        include("stocks.php");
        stock_adjustments($link);
    break;
    case "get_sales":
        include("sales.php");

         get_sales($link,$_POST['start'],$_POST['end']);

        
    break;

    case "get_catg_sales":
        include("sales.php");

         get_catg_sales($link,$_POST['start'],$_POST['end']);

        
    break;
    case "get_order_sales":
        include("sales.php");

         get_order_sales($link,$_POST['start'],$_POST['end'],$_POST['catg']);

        
    break;
    case "get_sales_by_dte":
        include("sales.php");

         get_sales_by_dte($link,$_POST['start'],$_POST['end'],$_POST['sort']);

        
    break;

    case "get_card_stock":
        include("stocks.php");

        get_card_details($link);

        
    break;


    
}

