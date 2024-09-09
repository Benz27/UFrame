<?php

if($_SERVER['REQUEST_METHOD']=='POST'){
include("conn.php");
include("read.php");
date_default_timezone_set("Asia/Manila");
  $productname= $_POST['productname'];
  $category= $_POST['category'];
  $location= $_POST['location'];
  $group = $_POST['group'];
  $stock= $_POST['stock'];
  $restock= $_POST['restock'];
  $origprice= $_POST['origprice'];
  $charge= $_POST['charge'];
  $tax=  $_POST['tax'];
  $supplier=  $_POST['supplier'];
  $description=$_POST['description'];
  $note=$_POST['note'];

  $dte=date('Y-m-d').' '.date('H:i:s');
  $dtedisp=date('m/d/Y').' at '.date('g:iA');
  $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');

  $item_id=mt_rand(1000000,9999999);   
  $sql="SELECT item_id FROM inventory where item_id=$item_id"; 
  $result=mysqli_query($link, $sql);     
  if($result && mysqli_num_rows($result) > 0){      
      $user_data=mysqli_fetch_assoc($result);      
      while($item_id ==  $user_data['item_id']){    
          $item_id=mt_rand(1000000,9999999);     
      }

  }

              $sql="INSERT INTO inventory values ($item_id,'',
              '$productname','$location','$group','$origprice',
              $tax,$profit,$stock,'$category',
              $restock,'$supplier','$description','$note',
              '$dte','$dtedisp',$dtenum,
                '0000-00-00 00:00:00','','')"; 
              if ($link->query($sql) === TRUE) {

                echo 1;
              }else{
      
                  echo "Error : " . $link->error;
              }

            
              

}

?>

