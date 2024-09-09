<?php

include("conn.php");
include("read.php");
date_default_timezone_set("Asia/Manila");

$vrnt = json_decode($_POST['vrnt_json']);
$vrntname = json_decode($_POST['vrntname_json']);
$vrntsprice = json_decode($_POST['vrntsprice_json']);
$vrntstock = json_decode($_POST['vrntstock_json']);
$vrntrestock = json_decode($_POST['vrntrestock_json']);
$v_id = $_POST['v_id'];


$v_ids = array();

$sql = "SELECT item_id FROM inventory where v_id=$v_id";
$result = mysqli_query($link, $sql);
if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    array_push($v_ids, $row['item_id']);
  }
}



$b64arr = json_decode($_POST['b64arr_json']);
$mlist = json_decode($_POST['mlist_json']);
$fnmarr = json_decode($_POST['fnmarr_json']);
$acount = count($fnmarr);
$parr = array();
$flnms = "";
$fpc = 0;
for ($x = 0; $x < $acount; $x++) {




  $y = $x + 1;

  $structure = '../../images/vproducts/' . $v_id;

  if ($b64arr[$x] != 0) {

    $destdir = $structure . '/' . $fnmarr[$x];
    $actual_name = pathinfo($destdir, PATHINFO_FILENAME);
    $extension = pathinfo($destdir, PATHINFO_EXTENSION);

    $destdir = $structure . '/' . $actual_name . "_" . $v_id . "_" . $y . "." . $extension;
    $txtsf = $actual_name . "_" . $v_id . "_" . $y . "." . $extension;


    $data = $b64arr[$x];

    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);


    if (file_put_contents($destdir, $data)) {
    } else {
      $fpc += 1;
    }
  } else {

    $destdir = $structure . '/' . $fnmarr[$x];
    $iname = $destdir;
    $actual_name = pathinfo($destdir, PATHINFO_FILENAME);
    $extension = pathinfo($destdir, PATHINFO_EXTENSION);
    $a = explode("_", $actual_name);
    $a[count($a) - 1] = $y;
    $actual_name = implode("_", $a);
    $destdir = $structure . '/' . $actual_name . "." . $extension;
    $txtsf = $actual_name . "." . $extension;
    rename($iname, $destdir);

    $fpc = 0;
  }
  array_push($parr, $txtsf);
}



$flnms = implode("|", $parr);






if ($fpc == 0) {

  for ($x = 0; $x < count($v_ids); $x++) {
    $bool = false;
    foreach (array_keys($vrnt, $v_ids[$x]) as $key) {
      $bool = true;
    }

    if ($bool) {


      update_var($link, $vrntsprice[$x], $vrntstock[$x], $vrntrestock[$x], $v_ids[$x], $vrntname[$x], $flnms, $fpc);
    } else {

      v_del_product($link, $v_ids[$x]);
    }
  }



  for ($x = 0; $x < count($vrnt); $x++) {
    $bool = true;
    foreach (array_keys($v_ids, $vrnt[$x]) as $key) {
      $bool = false;
    }

    if ($bool) {

      add_variant($link, $vrntsprice[$x], $vrntstock[$x], $vrntrestock[$x], $v_id, $vrntname[$x], $flnms, $fpc);
    }
  }
}






function add_variant($link, $charge, $stock, $restock, $v_id, $v_name, $flnms, $fpc)
{
  $productname = $_POST['productname'];
  $category = $_POST['category'];
  $location = $_POST['location'];
  $group = $_POST['group'];

  $origprice = 0;

  $tax =  0;
  $supplier =  $_POST['supplier'];
  $description = $_POST['description'];
  $ad_details = $_POST['ad_details'];
  $note = $_POST['note'];

  $weight = $_POST['weight'];
  $weight_unit = $_POST['weight_unit'];
  $height =  $_POST['height'];
  $width =  $_POST['width'];
  $length = $_POST['length'];
  $length_unit = $_POST['length_unit'];



  $dte = date('Y-m-d') . ' ' . date('H:i:s');
  $dtedisp = date('m/d/Y') . ' at ' . date('g:iA');
  $dtenum = (date('Y') * 10000) + (date('n') * 100) + date('j');

  $item_id = mt_rand(1000000, 9999999);
  $sql = "SELECT item_id FROM inventory where item_id=$item_id";
  $result = mysqli_query($link, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    while ($item_id ==  $user_data['item_id']) {
      $item_id = mt_rand(1000000, 9999999);
    }
  }











  $sql = "INSERT INTO inventory values ($item_id,$v_id,'',
              '$productname','$v_name','$flnms','$location','$group','$origprice',
              $tax,$charge,$stock,'$category',
              $restock,'$supplier','$description','$ad_details','$note',
              $weight,'$weight_unit',$height,$width,$length,'$length_unit',
              1,'$dte','$dtedisp',$dtenum,
                '0000-00-00 00:00:00','','')";
  if ($link->query($sql) === TRUE) {

    save_stock_add($link, $item_id, $stock, $dte, $dtedisp, $dtenum);
  } else {

    echo "Error : " . $link->error;
  }
}


function save_stock_add($link, $item_id, $stock, $dte, $dtedisp, $dtenum)
{
  $stock_id = mt_rand(1000000, 9999999);
  $sql = "SELECT s_id FROM stock_history where s_id=$stock_id";
  $result = mysqli_query($link, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
    $user_data = mysqli_fetch_assoc($result);
    while ($stock_id ==  $user_data['s_id']) {
      $stock_id = mt_rand(1000000, 9999999);
    }
  }



  $sql = "INSERT INTO stock_history values ($stock_id,$item_id,$stock,0,$stock,
  '$dte','$dtedisp',$dtenum,0)";
  if ($link->query($sql) === TRUE) {
    echo 2;
  } else {
    echo "Error : " . $link->error;
  }
}























function update_var($link, $selling_price, $qnt, $restock, $item_id, $v_name, $flnms, $fpc)
{
  $productname = $_POST['productname'];
  $category = $_POST['category'];
  $location = $_POST['location'];
  $group = $_POST['group'];


  $tax =  0;
  $supplier =  $_POST['supplier'];
  $description = $_POST['description'];
  $note = $_POST['note'];


  $ad_details = $_POST['ad_details'];

  $weight = $_POST['weight'];
  $weight_unit = $_POST['weight_unit'];
  $height =  $_POST['height'];
  $width =  $_POST['width'];
  $length = $_POST['length'];
  $length_unit = $_POST['length_unit'];



  $upddte = date('Y-m-d') . ' ' . date('H:i:s');
  $upddtedisp = date('m/d/Y') . ' at ' . date('g:iA');
  $upddtenum = (date('Y') * 10000) + (date('n') * 100) + date('j');




  if ($fpc == 0) {

    save_stock($link, $item_id, $qnt);
    $sql = "UPDATE inventory SET name='$productname',v_name='$v_name',location='$location',grp='$group',media='$flnms',
              tax='$tax',selling_price=$selling_price,category='$category',quantity=$qnt,
              restockqnt=$restock,supplier='$supplier',description='$description',note='$note',ad_details='$ad_details',
              weight=$weight,weight_unit='$weight_unit',height=$height,width=$width,length=$length,length_unit='$length_unit',
              upddte='$upddte',upddtedisp='$upddtedisp',upddtenum=$upddtenum WHERE item_id=$item_id";
    if ($link->query($sql) === TRUE) {
      echo 1;
    } else {
      echo "Error : " . $link->error;
    }
  }
}





function save_stock($link, $item_id, $stock)
{

  $dte = date('Y-m-d') . ' ' . date('H:i:s');
  $dtedisp = date('m/d/Y') . ' at ' . date('g:iA');
  $dtenum = (date('Y') * 10000) + (date('n') * 100) + date('j');

  $sql = "SELECT quantity FROM inventory where item_id = $item_id";
  $result = mysqli_query($link, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $oldqnt = $row['quantity'];
    $newqnt = $stock;
    $qntdiff = $newqnt - $oldqnt;
  }

  if ($oldqnt != $newqnt) {
    $stock_id = mt_rand(1000000, 9999999);
    $sql = "SELECT s_id FROM stock_history where s_id=$stock_id";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      $user_data = mysqli_fetch_assoc($result);
      while ($stock_id ==  $user_data['s_id']) {
        $stock_id = mt_rand(1000000, 9999999);
      }
    }



    $sql = "INSERT INTO stock_history values ($stock_id,$item_id,$stock,$oldqnt,$qntdiff,
      '$dte','$dtedisp',$dtenum,1)";
    if ($link->query($sql) === TRUE) {
      return 2;
    } else {
      return "Error : " . $link->error;
    }
  }
  return 1;
}


function v_del_product($link, $item_id)
{
  $sql = "delete from inventory where item_id=$item_id";
  if ($link->query($sql) === TRUE) {
    echo 3;
  } else {
    echo $link->error;
  }
}


