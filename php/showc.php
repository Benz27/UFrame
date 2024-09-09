<?php
session_start();
include("conn.php");
if($_SERVER['REQUEST_METHOD']=='POST'){
  $sh_id=0;
  if(isset($_SESSION['user_id'])){
    $sh_id=$_SESSION['user_id'];
  };

    $fpc=0;
    $prname=$_POST['name'];
    $details=$_POST['details_json'];    
    $catg=$_POST['catg'];   
    $note=$_POST['note'];        
    $summ=$_POST['summ'];  
    $price=$_POST['price']; 
    $size=$_POST['size'];
    $stock=$_POST['stock']; 
    $parr=array();
    $flnms;
    
    $b64arr=json_decode($_POST['b64arr_json']);
    $mlist=json_decode($_POST['mlist_json']);
    $fnmarr=json_decode($_POST['fnmarr_json']);
    $acount=count($fnmarr);
    $pro_id=mt_rand(1000000, 9999999);  

    $sql="SELECT pro_id FROM user where pro_id=$pro_id";  
    $result=mysqli_query($link, $sql);    
    if($result && mysqli_num_rows($result) > 0){        
        $user_data=mysqli_fetch_assoc($result);  
        while($pro_id ==  $user_data['pro_id']){  
            $pro_id=mt_rand(1000000,9999999); 
        }

    }

date_default_timezone_set("Asia/Manila");
$trdte=date('Y-m-d H:i:s.nnn');
$dtedisp=date('m/d/Y').' at '.date('g:iA');
$dtenum= (date('Y')*10000)+(date('n')*100)+date('j');
$mtme=date("G:i");
$tmedisp=date("g:iA");
$itdir='/images/products/'.$pro_id.'/';
for($x=0;$x<$acount;$x++){




 $fdname=$sh_id;

  $y=$x+1;


$structure = '../images/products/'.$pro_id;

if (!file_exists($structure)) {
  mkdir($structure, 0777, true);
}




$destdir = $structure.'/'.$fnmarr[$x];
$actual_name = pathinfo($destdir,PATHINFO_FILENAME);
$extension = pathinfo($destdir, PATHINFO_EXTENSION);

$destdir=$structure.'/'.$actual_name."_".$pro_id."_".$y.".".$extension;
$txtsf=$actual_name."_".$pro_id."_".$y.".".$extension;
array_push($parr,$txtsf);

$data = $b64arr[$x];

list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);


if (file_put_contents($destdir, $data)){
  $fpc=1;
}else{
  $fpc=2;
};

}
if($fpc==1){
  $flnms=implode("|",$parr);
  $sql="INSERT INTO items values ($pro_id, '$itdir', '$prname','$summ','$details','$catg','$note',$price,$stock,'$flnms','$trdte','$dtedisp',$dtenum,'$mtme','$tmedisp','1000-01-01 00:00:00','',0,0,0)"; 
    if ($link->query($sql) === TRUE) {
      
           echo $pro_id;
  
    } else {
      echo 3;
    }
}else{
  echo $fpc;
}


}
        


?>