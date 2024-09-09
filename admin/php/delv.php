<?php
$modal=0;
if($_SERVER['REQUEST_METHOD']=='POST'){

    $croute=$_POST['croute'];
    $proute=$_POST['proute'];
    $dte=$_POST['estarr'];
    $dtearr=explode("-",$dte);
    $dtedisp=$dtearr[1]."/".$dtearr[2]."/".$dtearr[0];
    $id=$_GET['id'];
    $dtenum=(intval($dtearr[0])*10000)+(intval($dtearr[1])*100)+intval($dtearr[2]);

    $name=$_POST['name'];
    $num=$_POST['num'];
    $plate="";
    $veh="";

    if(isset($_POST['veh'])){
        $veh=$_POST['veh'];
    };
    if(isset($_POST['plate'])){
        $plate=$_POST['plate'];
    };

    $vehicle=$veh.';;'.$plate;
    $sql="UPDATE delivery set dte='$dte',name='$name',phone='$num',croute='$croute',proute='$proute',vehicle='$vehicle',estarr='$dte',estarrdisp='$dtedisp',estarrnum=$dtenum where d_id=$id"; 
    if ($link->query($sql) === TRUE) {
        $modal=1;
    } else {
        $link->error;
    }


}

if(!isset($_GET['id'])){
    header("Location: delvlist.html");
}

$d_id=$_GET['id'];
$sql="select * from delivery where d_id = $d_id";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){ 

      $row=mysqli_fetch_assoc($result); 
      $id=explode(" ",$row['i_ids']);
      $idcnt=count($id);
      if($id[0]==""){
        $idcnt=0;
      }
      $name=$row['name'];
      $phone=$row['phone'];
        $proute=$row['proute'];
        $croute=$row['croute'];
        $fee=$row['fee'];
        $vehicle=explode(";;",$row['vehicle']);
        $veh=$vehicle[0];
        $plate=$vehicle[1];
        $estarr=$row['estarr'];
    }else{
        header("Location: delvlist.html");
    }
}
$idcnt=count($id);
