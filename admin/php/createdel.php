<?php

if($_SERVER['REQUEST_METHOD']=='POST'){

    $d_id=mt_rand(1000000, 9999999);  

    $sql="SELECT d_id FROM delivery where d_id=$d_id";  
    $result=mysqli_query($link, $sql);    
if($result && mysqli_num_rows($result) > 0){        
    $user_data=mysqli_fetch_assoc($result);  
    while($d_id ==  $user_data['d_id']){  
    $d_id=mt_rand(1000000,9999999); 
    }

}
$name=$_POST['cname'];
$num=$_POST['cnum'];
$route= explode(", ",$_POST['route']);
$dte=$_POST['estarr'];
$dtearr=explode("-",$dte);
$fee=0;
$dtedisp=$dtearr[1]."/".$dtearr[2]."/".$dtearr[0];
$dtenum=(intval($dtearr[0])*10000)+(intval($dtearr[1])*100)+intval($dtearr[2]);
$plate="";
$veh="";

if(isset($_POST['veh'])){
    $veh=$_POST['veh'];
};
if(isset($_POST['plate'])){
    $plate=$_POST['plate'];
};
$vehicle=$veh.';;'.$plate;

$sdte=date("Y-m-d");
$sdtedisp=date('n/d/Y');
$sdtenum=strval((date('Y')*10000)+(date('n')*100)+date('j'));

$sql="INSERT INTO delivery values($d_id,'','$name',$num,'$route[1]','$route[0]',$fee,'$vehicle','$sdte','$sdtedisp','$sdtenum','$dte','$dtedisp','$dtenum',0,'','','',0)"; 
if ($link->query($sql) === TRUE) {
header("Location: delivery.html?id=".$d_id);
} else {
 
}
}
