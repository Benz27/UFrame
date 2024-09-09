<?php

if($_SERVER['REQUEST_METHOD']=='POST'){
include("conn.php");
include("read.php");
date_default_timezone_set("Asia/Manila");
  $productname= $_POST['productname'];
  $category= $_POST['category'];
  $location= $_POST['location'];
  $group = $_POST['group'];
  $restock= $_POST['restock'];
  $qnt= $_POST['qnt'];
  $selling_price= $_POST['selling_price'];
  $tax=  $_POST['tax'];
  $supplier=  $_POST['supplier'];
  $description=$_POST['description'];
  $note=$_POST['note'];
  $parr=array();
  $flnms;
    
  $ad_details=$_POST['ad_details'];

  $weight= $_POST['weight'];
  $weight_unit= $_POST['weight_unit'];
  $height=  $_POST['height'];
  $width=  $_POST['width'];
  $length=$_POST['length'];
  $length_unit=$_POST['length_unit'];



    $b64arr=json_decode($_POST['b64arr_json']);
    $mlist=json_decode($_POST['mlist_json']);
    $fnmarr=json_decode($_POST['fnmarr_json']);
    $acount=count($fnmarr);

    
  $upddte=date('Y-m-d').' '.date('H:i:s');
  $upddtedisp=date('m/d/Y').' at '.date('g:iA');
  $upddtenum=(date('Y')*10000)+(date('n')*100)+date('j');

  $item_id=$_POST['item_id'];   


$fpc=0;
  for($x=0;$x<$acount;$x++){




    $y=$x+1;
    
    $structure = '../../images/products/'.$item_id;
    
    // if (!file_exists($structure)) {
    //   mkdir($structure, 0777, true);
    // }
    
    
    
    if($b64arr[$x]!=0){
    
        $destdir = $structure.'/'.$fnmarr[$x];
        $actual_name = pathinfo($destdir,PATHINFO_FILENAME);
        $extension = pathinfo($destdir, PATHINFO_EXTENSION);
    
        $destdir=$structure.'/'.$actual_name."_".$item_id."_".$y.".".$extension;
        $txtsf=$actual_name."_".$item_id."_".$y.".".$extension;
            
    
        $data = $b64arr[$x];
        
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        
        
        if (file_put_contents($destdir, $data)){
          
        }else{
          $fpc+=1;
        }
        
    }else{
        
        $destdir = $structure.'/'.$fnmarr[$x];
        $iname=$destdir;
        $actual_name = pathinfo($destdir,PATHINFO_FILENAME);
        $extension = pathinfo($destdir, PATHINFO_EXTENSION);
        $a=explode("_",$actual_name);
        $a[count($a)-1]=$y;
        $actual_name=implode("_",$a);
        $destdir=$structure.'/'.$actual_name.".".$extension;
        $txtsf=$actual_name.".".$extension;
        rename($iname,$destdir);
    
      $fpc=0;
    }
    array_push($parr,$txtsf);
    }



            if($fpc==0){
              $flnms=implode("|",$parr);
              save_stock($link,$item_id,$qnt);
              $sql="UPDATE inventory SET name='$productname',location='$location',grp='$group',media='$flnms',
              tax='$tax',selling_price=$selling_price,category='$category',quantity=$qnt,
              restockqnt=$restock,supplier='$supplier',description='$description',note='$note',ad_details='$ad_details',
              weight=$weight,weight_unit='$weight_unit',height=$height,width=$width,length=$length,length_unit='$length_unit',
              upddte='$upddte',upddtedisp='$upddtedisp',upddtenum=$upddtenum WHERE item_id=$item_id"; 
              if ($link->query($sql) === TRUE) {
                echo 1;
              }else{
                echo "Error : " . $link->error;
              }
            }
            
              

}
function save_stock($link,$item_id,$stock){

  $dte=date('Y-m-d').' '.date('H:i:s');
  $dtedisp=date('m/d/Y').' at '.date('g:iA');
  $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');

  $sql="SELECT quantity FROM inventory where item_id = $item_id";
  $result = mysqli_query($link, $sql);
  if($result && mysqli_num_rows($result) > 0){ 
      $row = mysqli_fetch_array($result);
      $oldqnt=$row['quantity'];
      $newqnt=$stock;
      $qntdiff=$newqnt-$oldqnt;


  }

  if($oldqnt!=$newqnt){
      $stock_id=mt_rand(1000000,9999999);   
      $sql="SELECT s_id FROM stock_history where s_id=$stock_id"; 
      $result=mysqli_query($link, $sql);     
      if($result && mysqli_num_rows($result) > 0){      
          $user_data=mysqli_fetch_assoc($result);      
          while($stock_id ==  $user_data['s_id']){    
              $stock_id=mt_rand(1000000,9999999);     
          }
      }
    
    
    
      $sql="INSERT INTO stock_history values ($stock_id,$item_id,$stock,$oldqnt,$qntdiff,
      '$dte','$dtedisp',$dtenum,1)"; 
      if ($link->query($sql) === TRUE){
        return 2;
      }else{
        return "Error : " . $link->error;
      }
  }
  return 1;
} 


