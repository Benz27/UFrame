<?php
session_start();

include("conn.php");

function savepd($id,$b64,$fn,$link){
    $structure = '/images/proofofdelivery';
    $sql="SELECT pddir,pdfname FROM transac where t_id=$id";  
    $result=mysqli_query($link, $sql); 
    $pddir="";
if($result && mysqli_num_rows($result) > 0){        
    $row=mysqli_fetch_assoc($result);  
    $pddir=$row['pdfname'];
    
}

if(!$pddir=""){

    unlink($structure.'/'.$pddir);
}

  
  if (!file_exists($structure)) {
    mkdir($structure, 0777, true);
  }
  
  
  
  
  $destdir = $structure.'/'.$fn;
  $actual_name = pathinfo($destdir,PATHINFO_FILENAME);
  $extension = pathinfo($destdir, PATHINFO_EXTENSION);
  
  $destdir=$structure.'/'.$actual_name."_".$id.".".$extension;
  $txtsf=$actual_name."_".$id.".".$extension;
  
  $data = $b64;
  
  list($type, $data) = explode(';', $data);
  list(, $data)      = explode(',', $data);
  $data = base64_decode($data);
  
  
  if (file_put_contents($destdir, $data)){
    $sql="UPDATE transac SET pddir='..$destdir', pdfname='$txtsf' WHERE t_id=$id"; 
    if ($link->query($sql) === TRUE) {
       
    } else {
        echo $link->error;
    }
  }else{
   
  };



}
