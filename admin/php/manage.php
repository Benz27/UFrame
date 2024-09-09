<?php
session_start();

include("conn.php");
$id=$_GET['id'];
$x=$_GET['x'];
$dlv=$_GET['dlv'];
$a=$_GET['a'];
$r=$_GET['r'];
date_default_timezone_set("Asia/Manila");
// $id=6110265;
// $x=0;
// $dlv=0;
// $a=1;

function canceled($id,$link){



        $sql="SELECT * FROM transac where t_id=$id";  
            $result=mysqli_query($link, $sql);    
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $item_ids=explode(" ",$row['i_ids']);
            $icnt=count($item_ids);
            $iqnt=explode(" ",$row['qnt']);
        }
        for($x=0;$x<$icnt;$x++){

            $sql="SELECT * FROM items where item_id=$item_ids[$x]";  
            $result=mysqli_query($link, $sql);    
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $stock=$row['stock'];
        }

        $stock+=$iqnt[$x];
            $sql="UPDATE items SET stock=$stock WHERE item_id=$item_ids[$x]"; 
            if ($link->query($sql) === TRUE) {
            
            } else {
                echo $link->error;
            }


        }


    
}

function upd(){
    $delv="";
    $id=$GLOBALS['id'];
    $link=$GLOBALS['link'];
    $x=$GLOBALS['x'];
    $dlv=$GLOBALS['dlv'];

        $sql="SELECT * FROM transac where t_id=$id";  
            $result=mysqli_query($link, $sql);    
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $sdte=explode("|",$row['statdte']);
            $sdtedisp=explode("|",$row['statdtedisp']);
            $sdtenum=explode("|",$row['statdtenum']);
        }

        if($sdte[0]){
            array_push($sdte,date("Y-m-d"));
            array_push($sdtedisp,date('m/d/Y').' at '.date('g:iA'));
            array_push($sdtenum,(date('Y')*10000)+(date('n')*100)+date('j'));
    
            $sdte=implode("|",$sdte);
            $sdtedisp=implode("|",$sdtedisp);
            $sdtenum=implode("|",$sdtenum);
        }else{
            $sdte=date("Y-m-d");
            $sdtedisp=date('m/d/Y').' at '.date('g:iA');
            $sdtenum=strval((date('Y')*10000)+(date('n')*100)+date('j'));
        }
        if($x==2){
            $delv=",d_id=$dlv";
        }

    $sql="UPDATE transac SET status=$x, statdte='$sdte', statdtedisp='$sdtedisp',statdtenum='$sdtenum' $delv WHERE t_id=$id"; 
    if ($link->query($sql) === TRUE) {
    
    } else {
    
    }
}


function savepd($id,$b64,$fn,$link){


    

    $sql="SELECT pddir,pdfname FROM transac where t_id=$id";  
    if($result=mysqli_query($link, $sql)){
        if($result && mysqli_num_rows($result) > 0){  
   
            $row=mysqli_fetch_assoc($result);  
            if(!$row['pdfname']==""){
         
               unlink('../../images/proofofdelivery/'.$row['pdfname']);
            
            }
        }
    }


    $structure = '../../images/proofofdelivery';

    $strt='../images/proofofdelivery';

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
  
  $strt.='/'.$actual_name."_".$id.".".$extension;

  if (file_put_contents($destdir, $data)){
    $sql="UPDATE transac SET pddir='$strt', pdfname='$txtsf' WHERE t_id=$id"; 
    if ($link->query($sql) === TRUE) {
       
    } else {
        echo $link->error;
    }
  }else{
   
  };



}
$z=true;
if($a==0){
    if($dlv==1){

        $d_id=mt_rand(1000000, 9999999);  

            $sql="SELECT d_id FROM delivery where d_id=$d_id";  
            $result=mysqli_query($link, $sql);    
        if($result && mysqli_num_rows($result) > 0){        
            $user_data=mysqli_fetch_assoc($result);  
            while($d_id ==  $user_data['d_id']){  
            $d_id=mt_rand(1000000,9999999); 
            }

        }
        $name=$_POST['name'];
        $num=$_POST['num'];
        $route= explode(", ",$_POST['route']);
        $dte=$_POST['dte'];
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
        // $sdtedisp=date('m/d/Y').' at '.date('g:iA');
        $sdtenum=strval((date('Y')*10000)+(date('n')*100)+date('j'));

        $sql="INSERT INTO delivery values($d_id,'$id','$name',$num,'$route[1]','$route[0]',$fee,'$vehicle','$sdte','$sdtedisp','$sdtenum','$dte','$dtedisp','$dtenum',0,'','','',1)"; 
        if ($link->query($sql) === TRUE) {
            $dlv=$d_id;
          
            upd();
        } else {
            $z=false;
            echo $link->error;
        }
        
    }else if($dlv==2){


        $sql="SELECT * FROM transac where t_id=$id";  
            $result=mysqli_query($link, $sql);    
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $stat=$row['status'];
            $sdte=explode("|",$row['statdte']);
            $sdtedisp=explode("|",$row['statdtedisp']);
            $sdtenum=explode("|",$row['statdtenum']);
        }


        array_push($sdte,date("Y-m-d"));
        array_push($sdtedisp,date('m/d/Y').' at '.date('g:iA'));
        array_push($sdtenum,(date('Y')*10000)+(date('n')*100)+date('j'));

        $sdte=implode("|",$sdte);
        $sdtedisp=implode("|",$sdtedisp);
        $sdtenum=implode("|",$sdtenum);


    $sql="UPDATE transac SET status=$x, statdte='$sdte', statdtedisp='$sdtedisp',statdtenum='$sdtenum' WHERE t_id=$id"; 
    if ($link->query($sql) === TRUE) {
        $z=false;
    } else {
        echo $link->error;
    }
    $dte=$_POST['pdte'];
    $dtearr=explode("-",$dte);
    $street=$_POST['pstreet'];
    $city=$_POST['pcity'];
    $prov=$_POST['pprov'];
    $dtedisp=$dtearr[1]."/".$dtearr[2]."/".$dtearr[0];
    $dtenum=(intval($dtearr[0])*10000)+(intval($dtearr[1])*100)+intval($dtearr[2]);
    $sql="INSERT INTO pickup values($id,'$dte','$street','$city','$prov','$dtedisp',$dtenum,0)"; 
    if ($link->query($sql) === TRUE) {
        $dlv=0;
        $z=false;
    } else {
        echo $link->error;
    }

    }else if($dlv==3){
        $dte=$_POST['cdte'];
        $dtearr=explode("-",$dte);
        $street=$_POST['pstreet'];
        $city=$_POST['pcity'];
        $prov=$_POST['pprov'];
        $dtedisp=$dtearr[1]."/".$dtearr[2]."/".$dtearr[0];
        $dtenum=(intval($dtearr[0])*10000)+(intval($dtearr[1])*100)+intval($dtearr[2]);
        $sql="UPDATE pickup set dte='$dte',dtedisp='$dtedisp',street='$street',city='$city',prov='$prov',dtenum=$dtenum where t_id=$id"; 
        if ($link->query($sql) === TRUE) {
            $dlv=0;
        
            upd();
        } else {
            $z=false;
            echo $link->error;
        }
    }else if($dlv==4){
        if($x>1){
            $z=false;
            $x=1;
        }
        $fee=$_POST['fee'];
        $sql="UPDATE transac set fee='$fee' where t_id=$id"; 
        if ($link->query($sql) === TRUE) {
            $dlv=0;
        } else {
            $z=false;
            echo $link->error;
        }

    }else if($dlv==5){
        
        $sql="SELECT * FROM transac where t_id=$id";  
        $result=mysqli_query($link, $sql); 
        $did=0;   
    if($result && mysqli_num_rows($result) > 0){        
        $row=mysqli_fetch_assoc($result);  
        $sdte=explode("|",$row['statdte']);
        $sdtedisp=explode("|",$row['statdtedisp']);
        $sdtenum=explode("|",$row['statdtenum']);
        $did=$row['d_id'];
        
    }




    $sql="SELECT * FROM delivery where d_id=$did";  
    $result=mysqli_query($link, $sql); 
    $iids=array();  

    if($result && mysqli_num_rows($result) > 0){        
    $row=mysqli_fetch_assoc($result);  
    $iids=explode(" ",$row['i_ids']);
    
    }

  

    foreach (array_keys($iids, $id) as $key) {
        $targ=$key;
    }

    $iids=array_merge(array_slice($iids,0,$targ), array_slice($iids,$targ+1));

    $iids=implode(" ",$iids);

    $sql="UPDATE delivery set i_ids='$iids' where d_id=$did"; 
    if ($link->query($sql) === TRUE) {
        
    } else {
       
        echo $link->error;

    }
    $z=false;
    $sdte=array_slice($sdte,0,1);
    $sdtedisp=array_slice($sdtedisp,0,1);
    $sdtenum=array_slice($sdtenum,0,1);
    // echo print_r($sdte);
    // echo print_r($sdtedisp);
    // echo print_r($sdtenum);

    $sdte=implode("|",$sdte);
    $sdtedisp=implode("|",$sdtedisp);
    $sdtenum=strval(implode("|",$sdtenum));
        // echo ' '.$sdte.', ';
        // echo $sdtedisp.', ';
        // echo $sdtenum.', ';
    $sql="UPDATE transac SET status=1, statdte='$sdte', statdtedisp='$sdtedisp',statdtenum='$sdtenum',d_id=0 WHERE t_id=$id"; 
    if ($link->query($sql) === TRUE) {
        
    } else {

        echo  $link->error;
    }
  
    }else if($dlv==6){
        $z=false;
        savepd($id,$_POST['base64'],$_POST['fname'],$link);

    }else if($dlv>10){
        
    $sql="SELECT * FROM delivery where d_id=$dlv";  
    $result=mysqli_query($link, $sql); 
    $iids=array();  

    if($result && mysqli_num_rows($result) > 0){        
    $row=mysqli_fetch_assoc($result);  
    $i_ids=$row['i_ids'];
    
    
    }
    if($i_ids==""){
        $iids=$id;
    }else{
        $iids=explode(" ",$i_ids);

        array_push($iids,$id);
  
        $iids=implode(" ",$iids);
    }


    $sql="UPDATE delivery set i_ids='$iids' where d_id=$dlv"; 
    if ($link->query($sql) === TRUE) {
        
    } else {
       
        echo $link->error;
    }
 


    }


if($z){
    if($x<0){

        canceled($id,$link);
    }
    upd();
}

}



$st=0;
$city;
$prov;
$canc="";
$pd="";
$pdn="";
$sql="SELECT * FROM transac where t_id=$id";
    $result=mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){        
    $row=mysqli_fetch_assoc($result);  
    $st=$row['status'];
    $shp=$row['shp'];
    $city=$row['city'];
    $prov=$row['prov'];
    $d_id=$row['d_id'];
    $fee=$row['fee'];
    $pd=$row['pddir'];
    $pdn=$row['pdfname'];
    }
    if($st<0){
        $canc="readonly";
    }
  $arr =  array(); 
  array_push($arr,$st);
  $str="";
if($st==0&&$shp==0){

        
    $str2='<div class="card-body">

             <div class="form-group"> <label class="small text-muted mb-1">SET SHIPPING FEE</label> <input type="text" value="" class="form-control form-control-sm" name="fee" id="fee" aria-describedby="helpId" placeholder="Shipping fee"> </div>           
     
         </div>';
     
        
        $str=$str2;
        
        
}
else if($st==1&&$shp==0){
   

$str0a = '
<div class="card-body">
<div class="form-group"> <label class="small text-muted mb-1">SHIPPING FEE</label> <input type="text" value="'.$fee.'" class="form-control form-control-sm" name="fee" id="fee" aria-describedby="helpId" placeholder="Shipping fee"> </div>           
<strong><a class="text-primary" style="cursor: pointer;" onclick="stat(0,10,4,0,0);send_email(0,10)">Update shipping fee</a></strong>

</div>
';
$str0b='';
if($d_id==-1){
    $str0b= '
    <div class="alert alert-warning text-center mt-4" role="alert">
      Shipment did not reach location and has been returned.
    </div>';
}


$str0c='
<div class="row mt-4">
            <div class="col">
                <p class="text-muted mb-2">SELECT DELIVERY</p>
                <hr class="mt-0">
            </div>
        </div>
    <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Delivery List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Courrier</th>
                        <th>Vehicle</th>
                        <th>Route</th>
                        <th>Date</th>
                        <th>Selection</th>
                    </tr>
                </thead>
                <tbody>';
$str0=$str0b.$str0a.$str0c;
                 $str1="";
                    $sql="select * from delivery having stat = 0";
                    $result = mysqli_query($link, $sql);
                    if($result && mysqli_num_rows($result) > 0){ 
                        while($row = mysqli_fetch_array($result)){
                            $d_id=$row['d_id'];
                             $str1.='<tr>
                                <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['name'].'</a></td>
                                <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['name'].'</a></td>
                                <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['proute'].', '.$row['croute'].'</a></td>
                                <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['dtedisp'].'</a></td>
                                <td><a class="text-primary" style="text-decoration: none;font-weight: bold;cursor:pointer;" onclick="stat(0,2,'.$d_id.',0,0);send_email(0,2)">Select</a></td>
                            </tr>';

                        }
                    }

             $str2='</tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="card-body">
        <div class="row mt-4">
            <div class="col">
                <p class="text-muted mb-2">CREATE DELIVERY TO LOCATION</p>
                <hr class="mt-0">
            </div>
        </div>
        <div class="form-group"> <label class="small text-muted mb-1"><span style="color:red;">*</span>COURIER NAME</label> <input type="text" value="" class="form-control form-control-sm" name="cname" id="cname" aria-describedby="helpId" placeholder="Courier Name"> </div>
        <div class="form-group"> <label class="small text-muted mb-1"><span style="color:red;">*</span>PHONE NUMBER</label> <input type="text" value="" class="form-control form-control-sm" name="cnum" id="cnum" aria-describedby="helpId" placeholder="Phone Number"> </div>
        <div class="form-group"> <label class="small text-muted mb-1"><span style="color:red;">*</span>ROUTE</label> <input type="text" value="'.$city.', '.$prov.'" class="form-control form-control-sm" name="route" id="route" aria-describedby="helpId" placeholder="Route"> </div>
        <div class="form-group"> <label class="small text-muted mb-1"><span style="color:red;">*</span>ESTIMATED ARRIVAL</label> <input type="date" value="" class="form-control form-control-sm" name="estarr" id="estarr" aria-describedby="helpId" placeholder="Estimated Arrival"> </div>
        <div class="form-group"> <label class="small text-muted mb-1">VEHICLE</label> <input type="text" value="" class="form-control form-control-sm" name="veh" id="veh" aria-describedby="helpId" placeholder="Vehicle"> </div>
        <div class="form-group"> <label class="small text-muted mb-1">PLATE NUMBER</label> <input type="text" value="" class="form-control form-control-sm" name="plate" id="plate" aria-describedby="helpId" placeholder="Plate number"> </div>
        <strong><a class="text-primary" style="cursor: pointer;" onclick="stat(0,2,1,0,0);send_email(0,2)">Create and Select this Delivery</a></strong>

    </div>';

$str=$str0.$str1.$str2;
}else if($st==3&&$shp==0){
 

$str0 = '
<div class="card-body">
<div class="form-group"> <label class="small text-muted mb-1">SHIPPING FEE</label> <input type="text" value="'.$fee.'" class="form-control form-control-sm" name="fee" id="fee" aria-describedby="helpId" placeholder="Shipping fee" readonly> </div>           

</div><div class="row mt-4">
    <div class="col">
        <p class="text-muted mb-2">DELIVERY ON THE WAY</p>
        <hr class="mt-0">
    </div>
</div>
<div class="card-body">
<div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
            <th>Courrier</th>
            <th>Phone Number</th>
            <th>Route</th>
            <th>Estimated Arrival</th>
            <th>Status</th>
            </tr>
        </thead>
        <tbody>';

         $str1="";
            $sql="select * from delivery where d_id=$d_id";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                while($row = mysqli_fetch_array($result)){
                    $status=$row['stat'];
                        $stat="";
                        switch ($status) {
                            case 0:
                                $stat="Standby";
                              break;
                            case 1:
                                $stat="On the way";
                              break;
                            case 2:
                                $stat="Finished";
                              break;
                          }
                          if($st>3){
                            $stat="Received";
                          }
                     $str1.='<tr>
                        <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['name'].'</a></td>
                        <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['phone'].'</a></td>
                        <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['proute'].', '.$row['croute'].'</a></td>
                        <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['dtedisp'].'</a></td>
                        <td><a class="text-primary" href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$stat.'</a></td>
                    </tr>';
                }
            }

     $str2='</tbody>
    </table>
</div>
</div>';


$str=$str0.$str1.$str2;


}else if($st==4&&$shp==0){


$str0 = '<div class="card-body">
    <div class="form-group"> <label class="small text-muted mb-1">UPLOAD PROOF OF DELIVERY</label>
    <div class="form-control-sm">
        <button type="button" id="btncl" class="btn btn-secondary" hidden>Clear</button>
        <button id="btncr" type="button" onclick="ifile.click();" class="btn btn-info">Browse files</button>
        <strong><a class="text-primary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" onclick="mflex()" id="txtsfr"></a></strong>
    </div>
    </div>  
    </div>
    <strong><a class="text-secondary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" id="updpd" hidden>Update proof of delivery</a></strong>
    <hr>
    <div class="card-body">
    <div class="form-group"> <label class="small text-muted mb-1">SHIPPING FEE</label> <input type="text" value="'.$fee.'" class="form-control form-control-sm" name="fee" id="fee" aria-describedby="helpId" placeholder="Shipping fee" readonly> </div>           
    
    </div>

    
    <div class="row mt-4">
        <div class="col">
            <p class="text-muted mb-2">DELIVERY REACHED LOCATION</p>
            <hr class="mt-0">
        </div>
    </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Courrier</th>
                    <th>Phone Number</th>
                    <th>Route</th>
                    <th>Estimated Arrival</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';
    
             $str1="";
                $sql="select * from delivery where d_id=$d_id";
                $result = mysqli_query($link, $sql);
                if($result && mysqli_num_rows($result) > 0){ 
                    while($row = mysqli_fetch_array($result)){
                        $status=$row['stat'];
                        $stat="";
                        switch ($status) {
                            case 0:
                                $stat="Standby";
                              break;
                            case 1:
                                $stat="On the way";
                              break;
                            case 2:
                                $stat="Finished";
                              break;
                        }
                        if($st>3){
                            $stat="Received";
                        }
                         $str1.='<tr>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['name'].'</a></td>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['phone'].'</a></td>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['proute'].', '.$row['croute'].'</a></td>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['estarrdisp'].'</a></td>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$stat.'</a></td>
                        </tr>';
                    }
                }
    
         $str2='</tbody>
        </table>
    </div>
    </div>';
    
    $str=$str0.$str1.$str2;
    
    
    }else if($st==5&&$shp==0){

        if($pd==""){
            $str00 = '<div class="card-body">
            <div class="form-group"> <label class="small text-muted mb-1">UPLOAD PROOF OF DELIVERY</label>
            <div class="form-control-sm">
                <button type="button" id="btncl" class="btn btn-secondary" hidden>Clear</button>
                <button id="btncr" type="button" onclick="ifile.click();" class="btn btn-info">Browse files</button>
                <strong><a class="text-primary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" onclick="mflex()" id="txtsfr"></a></strong>
            </div>
            </div>  
            </div>
            <strong><a class="text-secondary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" id="updpd">Save proof of delivery</a></strong>
            ';
        }else{
            $str00 = '<div class="card-body">
            <div class="form-group"> <label class="small text-muted mb-1">PROOF OF DELIVERY</label>
            <div class="form-control-sm">
                <strong><a class="text-primary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" onclick="mflex()" id="txtsfr"></a></strong>
            </div>
            </div>  
            </div>';
        }
        


            



            $str0 ='<hr>
            <div class="card-body">
            <div class="form-group"> <label class="small text-muted mb-1">SHIPPING FEE</label> <input type="text" value="'.$fee.'" class="form-control form-control-sm" name="fee" id="fee" aria-describedby="helpId" placeholder="Shipping fee" readonly> </div>           
            
            </div>
        
            
            <div class="row mt-4">
                <div class="col">
                    <p class="text-muted mb-2">DELIVERY REACHED LOCATION</p>
                    <hr class="mt-0">
                </div>
            </div>
            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Courrier</th>
                            <th>Phone Number</th>
                            <th>Route</th>
                            <th>Estimated Arrival</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';
            
                     $str1="";
                        $sql="select * from delivery where d_id=$d_id";
                        $result = mysqli_query($link, $sql);
                        if($result && mysqli_num_rows($result) > 0){ 
                            while($row = mysqli_fetch_array($result)){
                                $status=$row['stat'];
                                $stat="";
                                switch ($status) {
                                    case 0:
                                        $stat="Standby";
                                      break;
                                    case 1:
                                        $stat="On the way";
                                      break;
                                    case 2:
                                        $stat="Finished";
                                      break;
                                }
                                if($st>3){
                                    $stat="Received";
                                  }
                                 $str1.='<tr>
                                    <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['name'].'</a></td>
                                    <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['phone'].'</a></td>
                                    <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['proute'].', '.$row['croute'].'</a></td>
                                    <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['estarrdisp'].'</a></td>
                                    <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$stat.'</a></td>
                                </tr>';
                            }
                        }
            
                 $str2='</tbody>
                </table>
            </div>
            </div>';
            
            $str=$str00.$str0.$str1.$str2;
            
            
    }else if($st==2&&$shp==0){


    $str0 = '
    <div class="card-body">
    <div class="form-group"> <label class="small text-muted mb-1">SHIPPING FEE</label> <input type="text" value="'.$fee.'" class="form-control form-control-sm" name="fee" id="fee" aria-describedby="helpId" placeholder="Shipping fee" readonly> </div>           
    </div>
    <div class="row mt-4">
        <div class="col">
            <p class="text-muted mb-2">WAITING TO DEPART</p>
            <hr class="mt-0">
        </div>
    </div>
    <div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Courrier</th>
                    <th>Vehicle</th>
                    <th>Route</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>';
    
             $str1="";
                $sql="select * from delivery where d_id=$d_id";
                $result = mysqli_query($link, $sql);
                if($result && mysqli_num_rows($result) > 0){ 
                    while($row = mysqli_fetch_array($result)){
                        $status=$row['stat'];
                        $stat="";
                        switch ($status) {
                            case 0:
                                $stat="Standby";
                              break;
                            case 1:
                                $stat="On the way";
                              break;
                            case 2:
                                $stat="Finished";
                              break;

                          }
                          if($st>3){
                            $stat="Received";
                          }
                         $str1.='<tr>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['name'].'</a></td>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['name'].'</a></td>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['proute'].', '.$row['croute'].'</a></td>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['dtedisp'].'</a></td>
                            <td><a href="delivery.html?id='.$row['d_id'].'" style="text-decoration: none;font-weight: bold;">'.$stat.'</a></td>
                        </tr>';
                    }
                }
    
         $str2='</tbody>
        </table>
        <strong><a class="text-primary" style="cursor: pointer;" onclick="stat(0,1,5,0,0);send_email(0,11)">Unselect this delivery</a></strong>
    </div>
    </div>';
    
    $str=$str0.$str1.$str2;
    
    }else if($st==1&&$shp==1){

        $sql="select * from about where id=1";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
               $row = mysqli_fetch_array($result);

                $city=$row['city'];
                $street=$row['street'];
                $prov=$row['prov'];
            }
            
    $str2='<div class="card-body">
             <div class="row mt-4">
                 <div class="col">
                     <p class="text-muted mb-2">PREPARE THE ORDER</p>
                     <hr class="mt-0">
                 </div>
             </div>
             <div class="form-group"> <label class="small text-muted mb-1">MAXIMUM DATE TO BE COLLECTED</label> <input type="date" value="" class="form-control form-control-sm" name="pdte" id="pdte" aria-describedby="helpId" placeholder="Date to be collected"> </div>
             <div class="form-group"> <label class="small text-muted mb-1">STREET</label> <input type="text" value="'.$street.'" class="form-control form-control-sm" name="pstreet" id="pstreet" aria-describedby="helpId" placeholder="Street"></div>
             <div class="form-group"> <label class="small text-muted mb-1">CITY</label> <input type="text" value="'.$city.'" class="form-control form-control-sm" name="pcity" id="pcity" aria-describedby="helpId" placeholder="City"></div>
             <div class="form-group"> <label class="small text-muted mb-1">PROVINCE</label> <input type="text" value="'.$prov.'" class="form-control form-control-sm" name="pprov" id="pprov" aria-describedby="helpId" placeholder="Province"></div>
             
             <strong><a class="text-primary" style="cursor: pointer;" onclick="stat(0,2,2,0,1);send_email(1,2);">Confirm Details</a></strong>
     
         </div>';
     
        
        $str=$str2;
        
        
    }else if($st==2&&$shp==1){

        
        $str2='<div class="card-body">
                 <div class="row mt-4">
                     <div class="col">
                         <p class="text-muted mb-2">PICKUP DETAILS</p>
                         <hr class="mt-0">
                     </div>
                 </div>';
                 $str1="";
                 $sql="select * from pickup where t_id=$id";
                 $result = mysqli_query($link, $sql);
                 if($result && mysqli_num_rows($result) > 0){ 
                     while($row = mysqli_fetch_array($result)){
                          $str1='
                          <div class="form-group"> <label class="small text-muted mb-1">MAXIMUM DATE TO BE COLLECTED</label> <input type="date" value="'.$row['dte'].'" class="form-control form-control-sm" name="pdte" id="pdte" aria-describedby="helpId" placeholder="Date to be collected"> </div>
                          <div class="form-group"> <label class="small text-muted mb-1">STREET</label> <input type="text" value="'.$row['street'].'" class="form-control form-control-sm" name="pstreet" id="pstreet" aria-describedby="helpId" placeholder="Street"</div>
                          <div class="form-group"> <label class="small text-muted mb-1">CITY</label> <input type="text" value="'.$row['city'].'" class="form-control form-control-sm" name="pcity" id="pcity" aria-describedby="helpId" placeholder="City"></div>
                          <div class="form-group"> <label class="small text-muted mb-1">PROVINCE</label> <input type="text" value="'.$row['prov'].'" class="form-control form-control-sm" name="pprov" id="pprov" aria-describedby="helpId" placeholder="Province"></div>
                      </div>';
                     }
                 }   
                 $str0= ' <strong><a class="text-primary" style="cursor: pointer;" onclick="stat(0,2,3,0,1);send_email(1,10)">Update Details</a></strong>
             </div>';
         
            
             $str=$str2.$str1.$str0;
            
            
    }else if($st==3&&$shp==1){

        
        $str2='<div class="card-body">
                 <div class="row mt-4">
                     <div class="col">
                         <p class="text-muted mb-2">PICKUP DETAILS</p>
                         <hr class="mt-0">
                     </div>
                 </div>';
                 $str1="";
            $sql="select * from pickup where t_id=$id";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                while($row = mysqli_fetch_array($result)){
                     $str1='
                     <div class="form-group"> <label class="small text-muted mb-1">MAXIMUM DATE TO BE COLLECTED</label> <input type="date" value="'.$row['dte'].'" class="form-control form-control-sm" name="pdte" id="pdte" aria-describedby="helpId" placeholder="Date to be collected" readonly> </div>
                     <div class="form-group"> <label class="small text-muted mb-1">STREET</label> <input type="text" value="'.$row['street'].'" class="form-control form-control-sm" name="pstreet" id="pstreet" aria-describedby="helpId" placeholder="Street" readonly></div>
                     <div class="form-group"> <label class="small text-muted mb-1">CITY</label> <input type="text" value="'.$row['city'].'" class="form-control form-control-sm" name="pcity" id="pcity" aria-describedby="helpId" placeholder="City" readonly></div>
                     <div class="form-group"> <label class="small text-muted mb-1">PROVINCE</label> <input type="text" value="'.$row['prov'].'" class="form-control form-control-sm" name="pprov" id="pprov" aria-describedby="helpId" placeholder="Province" readonly></div>
                 </div>';
                }
            }
                 
         
            
            $str=$str2.$str1;
            
            
    }

array_push($arr,$str,$dlv,$shp,$id,$pd,$pdn);
echo json_encode($arr);
