<?php

session_start();

include("conn.php");
$id=$_GET['id'];

$sh_id=0;
  if(isset($_SESSION['user_id'])){
    $sh_id=$_SESSION['user_id'];
  };

    $fpc=0;
    $prname=$_POST['name'];
    $tpe=$_POST['type'];    
    // $catg=$_POST['catg'];   
    $atdet=$_POST['atdet'];        
    $price=$_POST['price']; 
    $size=$_POST['size'];
    $stock=$_POST['stock']; 
    $parr=array();
    $flnms;
    
    $b64arr=json_decode($_POST['b64arr_json']);
    $mlist=json_decode($_POST['mlist_json']);
    $fnmarr=json_decode($_POST['fnmarr_json']);
    $acount=count($fnmarr);
    
    $sql="select * from items where item_id = $id";
    $result=mysqli_query($link, $sql);    
    if($result && mysqli_num_rows($result) > 0){        
        $row=mysqli_fetch_assoc($result);  

        $flnms2=explode("|",$row['filename']);
        $flnms2cnt=count($flnms2);

        $med=$row['mdir'];
        $pro_id=$row['item_id'];
        $updnum=$row['updnum'];
    }

    
date_default_timezone_set("Asia/Manila");
$trdte=date('Y-m-d H:i:s.nnn');
$dtedisp=date('m/d/Y').' at '.date('g:iA');
$dtenum=(date('Y')*10000)+(date('n')*100)+date('j');
// $mtme=date("G:i");
// $tmedisp=date("g:iA");
// $itdir='/images/products/'.$pro_id.'/';


for($x=0;$x<$acount;$x++){



$fdname=$sh_id;
$y=$x+1;

$structure = '/images/products/'.$pro_id;

// if (!file_exists($structure)) {
//   mkdir($structure, 0777, true);
// }

if($x<$flnms2cnt){
  $txtsf=$flnms2[$x];
    if($flnms2[$x]!=$fnmarr[$x]){
          unlink($med.$fnmarr[$x]);
    }
}

if($b64arr[$x]!=0){

    $destdir = $structure.'/'.$fnmarr[$x];
    $actual_name = pathinfo($destdir,PATHINFO_FILENAME);
    $extension = pathinfo($destdir, PATHINFO_EXTENSION);

    $destdir=$structure.'/'.$actual_name."_".$pro_id."_".$y.".".$extension;
    $txtsf=$actual_name."_".$pro_id."_".$y.".".$extension;
        

    $data = $b64arr[$x];
    
    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);
    
    
    if (file_put_contents($destdir, $data)){
      $fpc=1;
    }else{
      $fpc=2;
    };
    
}else{
  $fpc=1;
}
array_push($parr,$txtsf);
}
if($fpc==1){
  $updnum+=1;
  $flnms=implode("|",$parr);
  $sql="UPDATE items SET name = '$prname', details= '$tpe', size='$size', atdet='$atdet',price=$price,stock=$stock,filename='$flnms',upd='$trdte',upddisp='$dtedisp',updq=$updnum,updnum=$dtenum WHERE item_id=$pro_id"; 
    if ($link->query($sql) === TRUE) {
      
      echo $pro_id;
  
    } else {
      echo 3;
    }
}else{
  echo $fpc;
}



