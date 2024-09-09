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
  $ad_details=$_POST['ad_details'];
  $note=$_POST['note'];

  $weight= $_POST['weight'];
  $weight_unit= $_POST['weight_unit'];
  $height=  $_POST['height'];
  $width=  $_POST['width'];
  $length=$_POST['length'];
  $length_unit=$_POST['length_unit'];


  $parr=array();
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


  $b64arr=json_decode($_POST['b64arr_json']);
  $mlist=json_decode($_POST['mlist_json']);
  $fnmarr=json_decode($_POST['fnmarr_json']);
  $acount=count($fnmarr);
  $itdir='/images/products/'.$item_id.'/';

  $fpc=0;
  for($x=0;$x<$acount;$x++){


   
     $y=$x+1;
   
   
   $structure = '../../images/products/'.$item_id;
   
   if (!file_exists($structure)) {
     mkdir($structure, 0777, true);
   }
   
   
   
   
   $destdir = $structure.'/'.$fnmarr[$x];
   $actual_name = pathinfo($destdir,PATHINFO_FILENAME);
   $extension = pathinfo($destdir, PATHINFO_EXTENSION);
   
   $destdir=$structure.'/'.$actual_name."_".$item_id."_".$y.".".$extension;
   $txtsf=$actual_name."_".$item_id."_".$y.".".$extension;
   array_push($parr,$txtsf);
   
   $data = $b64arr[$x];
   
   list($type, $data) = explode(';', $data);
   list(, $data)      = explode(',', $data);
   $data = base64_decode($data);
   
   
   if (file_put_contents($destdir, $data)){
  
   }else{
    $fpc+=1;
   };
   
   }
    if($fpc==0){

    
     $flnms=implode("|",$parr);




              $sql="INSERT INTO inventory values ($item_id,'','',
              '$productname','','$flnms','$location','$group','$origprice',
              $tax,$charge,$stock,'$category',
              $restock,'$supplier','$description','$ad_details','$note',
              $weight,'$weight_unit',$height,$width,$length,'$length_unit',
              1,'$dte','$dtedisp',$dtenum,
                '0000-00-00 00:00:00','','')"; 
              if ($link->query($sql) === TRUE) {

                save_stock($link,$item_id,$stock,$dte,$dtedisp,$dtenum);

              }else{
      
                  echo "Error : " . $link->error;
              }
      }

         

            
              

}

  //functions
function save_stock($link,$item_id,$stock,$dte,$dtedisp,$dtenum){
  $stock_id=mt_rand(1000000,9999999);   
  $sql="SELECT s_id FROM stock_history where s_id=$stock_id"; 
  $result=mysqli_query($link, $sql);     
  if($result && mysqli_num_rows($result) > 0){      
      $user_data=mysqli_fetch_assoc($result);      
      while($stock_id ==  $user_data['s_id']){    
          $stock_id=mt_rand(1000000,9999999);     
      }
  }



  $sql="INSERT INTO stock_history values ($stock_id,$item_id,$stock,0,$stock,
  '$dte','$dtedisp',$dtenum,0)"; 
  if ($link->query($sql) === TRUE){
    echo 1;
  }else{
    echo "Error : " . $link->error;
  }

} 
?>

