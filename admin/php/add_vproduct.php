<?php


include("conn.php");
include("read.php");
date_default_timezone_set("Asia/Manila");
  $productname= $_POST['productname'];
  $category= $_POST['category'];
  $location= $_POST['location'];
  $group = $_POST['group'];
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



  $dte=date('Y-m-d').' '.date('H:i:s');
  $dtedisp=date('m/d/Y').' at '.date('g:iA');
  $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');




  $b64arr=json_decode($_POST['b64arr_json']);
  $mlist=json_decode($_POST['mlist_json']);
  $fnmarr=json_decode($_POST['fnmarr_json']);

  $vrntname=json_decode($_POST['vrntname_json']);
  $vrntsprice=json_decode($_POST['vrntsprice_json']);
  $vrntstock=json_decode($_POST['vrntstock_json']);
  $vrntrestock=json_decode($_POST['vrntrestock_json']);

   
  $v_id=mt_rand(1000000,9999999);   
  $sql="SELECT v_id FROM inventory where v_id=$v_id"; 
  $result=mysqli_query($link, $sql);     
  if($result && mysqli_num_rows($result) > 0){      
      $user_data=mysqli_fetch_assoc($result);      
      while($v_id ==  $user_data['v_id']){    
          $v_id=mt_rand(1000000,9999999);     
      }

  }

  
  
  $acount=count($fnmarr);
  $itdir='/images/vproducts/'.$v_id.'/';

  $fpc=0;
  $parr=array();
  for($x=0;$x<$acount;$x++){


   
     $y=$x+1;
   
   
   $structure = '../../images/vproducts/'.$v_id;
   
   if (!file_exists($structure)) {
     mkdir($structure, 0777, true);
   }
   
   
   
   
   $destdir = $structure.'/'.$fnmarr[$x];
   $actual_name = pathinfo($destdir,PATHINFO_FILENAME);
   $extension = pathinfo($destdir, PATHINFO_EXTENSION);
   
   $destdir=$structure.'/'.$actual_name."_".$v_id."_".$y.".".$extension;
   $txtsf=$actual_name."_".$v_id."_".$y.".".$extension;
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


   $flnms=implode("|",$parr);

if($fpc==0){
  for($v=0;$v<count($vrntname);$v++){
  
 
  $item_id=mt_rand(1000000,9999999);   
  $sql="SELECT item_id FROM inventory where item_id=$item_id"; 
  $result=mysqli_query($link, $sql);     
  if($result && mysqli_num_rows($result) > 0){      
      $user_data=mysqli_fetch_assoc($result);      
      while($item_id ==  $user_data['item_id']){    
          $item_id=mt_rand(1000000,9999999);     
      }

  }



  

    





              $sql="INSERT INTO inventory values ($item_id,$v_id,'',
              '$productname','$vrntname[$v]','$flnms','$location','$group',0,
              0,$vrntsprice[$v],$vrntstock[$v],'$category',
              $vrntrestock[$v],'$supplier','$description','$ad_details','$note',
              $weight,'$weight_unit',$height,$width,$length,'$length_unit',
              1,'$dte','$dtedisp',$dtenum,
                '0000-00-00 00:00:00','','')"; 
              if ($link->query($sql) === TRUE) {

                save_stock($link,$item_id,$vrntstock[$v],$dte,$dtedisp,$dtenum);

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
