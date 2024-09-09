<?php

if(isset($_SESSION['user_id'])){
  $id=$_SESSION['user_id'];
  
}else{
  header("Location: ../");

}

if(!isset($_GET['t'])){
  if(isset($_GET['o'])){

      if($_GET['o']==2){
        $disp="Confirmed orders will apear here.";
        $ord="and shp = 0 and status = 1";
      }else if($_GET['o']==3){
        $disp="Orders that are to be shipped will apear here.";
        $ord="and shp = 0 and status = 2";
      }else if($_GET['o']==4){
          $disp="On the way orders will apear here.";
          $ord="and shp = 0 and status = 3";
      }else if($_GET['o']==5){
          $disp="Completed orders will apear here.";
          $ord="and shp = 0 and (status = 5 or status = 4)";
      }else if($_GET['o']==6){
          $disp="Canceled will apear here.";
          $ord="and shp = 0 and status < 0";
      }
    
  }else{
      $disp="Pending orders will apear here.";
      $ord="and shp = 0 and status = 0";
  }

}else{

  if(isset($_GET['o'])){

    if($_GET['o']==2){
      $disp="Confirmed orders will apear here.";
      $ord="and shp = 1 and status = 1";
    }else if($_GET['o']==3){
      $disp="Orders that are to be picked up will apear here.";
      $ord="and shp = 1 and status = 2";
    }else if($_GET['o']==4){
        $disp="Completed orders will apear here.";
        $ord="and shp = 1 and status = 3";
    }else if($_GET['o']==5){
      $disp="Canceled will apear here.";
      $ord="and shp = 1 and status < 0";
    }
  
}else{
    $disp="Pending orders will apear here.";
    $ord="and shp = 1 and status = 0";
}


}




$sql3 = "SELECT * FROM about where id=1";
if($result3 = mysqli_query($link, $sql3)){
  if(mysqli_num_rows($result3) > 0){ 
      while($row3 = mysqli_fetch_array($result3)){  
        $about_email= $row3['email'];
        $about_contno= $row3['contact'];
        $ab_street= $row3['street'];
        $ab_bar= $row3['bar'];
        $ab_city= $row3['city'];
        $ab_prov= $row3['prov'];
          }
      mysqli_free_result($result3);
  } else{
      echo "No records matching your query were found.";
  }
} else{
  echo "ERROR: Could not able to execute $sql3. " . mysqli_error($link);
}



$sql = "SELECT * FROM transac where user_id=$id $ord order by dte DESC";  
$sss=0;
if($resultp = mysqli_query($link, $sql)){

    if(mysqli_num_rows($resultp) > 0){ 
      while($dta = mysqli_fetch_array($resultp)){ 

 
        $cnt=$dta['itmc'];
        $item_id=explode(" ",$dta['i_ids']);
        $qnt=explode(" ",$dta['qnt']);
        $price=explode(" ",$dta['price']);
        $t_id=$dta['t_id'];
        $stat=$dta['status'];
        $shp=$dta['shp'];
        $d_id=$dta['d_id'];
        $fee=$dta['fee'];
        $pddir=$dta['pddir'];
        $pdfname=$dta['pdfname'];
        echo '<div class="container">
        <article class="card">
    <div class="card-body" id="'.$t_id.'div">
    <h6>Order ID: '.$t_id.'</h6>
    <hr>';

    if($shp==1 && (($stat>=2 || $stat<=-2) && $stat!=-9)){
      $sql="select * from pickup where t_id=$t_id";
      $result = mysqli_query($link, $sql);
      if($result && mysqli_num_rows($result) > 0){ 
         $row = mysqli_fetch_array($result);
          $city=$row['city'];
          $street=$row['street'];
          $prov=$row['prov'];
          $dte=$row['dtedisp'];
      }
    
      echo '<small>Pickup Address: '.$street.', '.$city.', '.$prov.' | Max pickup date <i class="fa fa-calendar"></i> : '.$dte.' |Email and Contact Number <i class="fa fa-mail-bulk"></i> : '.$about_email.', '.$about_contno.'</small>
      <hr>';
    }
  
  
    echo' 
    <div class="col"><small>Name: '.$dta['fname'].'</small></div>
    <div class="col"><small>Address: '.$dta['street'].', '.$dta['city'].', '.$dta['prov'].'</small> </div>
    <div class="col"><small>Email Address: '.$dta['email'].'</small> </div>
    <div class="col"><small>Phone Number: '.$dta['phone'].'</small> </div>';
   
        if($stat>1 && $shp==0){
        
        $sql2 = "SELECT * FROM delivery where d_id=$d_id";  
          
        if($result2 = mysqli_query($link, $sql2)){
         
          if(mysqli_num_rows($result2) > 0){ 

            $dta2 = mysqli_fetch_array($result2);
            $estarr=$dta2['estarrdisp'];
            $name=$dta2['name'];
            $phone=$dta2['phone'];
            // $d_id=$dta2['d_id'];
            $stat2=$dta2['stat'];
            $st2="";
            switch ($stat2) {
              case 0:
                $st2="Waiting departure";
                break;
              case 1:
                $st2= "On the way";
                break;
              case 2:
                $st2= "Shipment delivered";
              break;
            }

          }
        }

          echo'  <article class="card">
            <div class="card-body row">
            <div class="col"> <strong>Estimated delivery date:</strong> <br>'.$estarr.' </div>
            <div class="col"> <strong>Courrier:</strong> <br> '.$name.', | <i class="fa fa-phone"></i> '.$phone.' </div>
            <div class="col"> <strong>Status:</strong> <br> '.$st2.' </div>
            <div class="col"> <strong>Delivery ID:</strong> <br> '.$d_id.' </div>
            </div>
            </article>';
          }
            echo'<div class="track">';

    if($shp==0){
      $snum=4;
      $canc="";
      $sarr=array();
      $stp=$stat;
      
      if($stp<0){
        
        $stp*=-1;
        if($stp==9){
          $stp=0;
        }
        $canc=" canceled";
      }
      for($x=0;$x<5;$x++){
        $clss="step".$canc;
        if($x<$stp){
          $clss="step active".$canc;
        }
        array_push($sarr,$clss);
      }
      echo'
      <div class="'.$sarr[0].'" id="'.$t_id.'s1"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order confirmed</span> </div>
      <div class="'.$sarr[1].'" id="'.$t_id.'s2"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text"> Waiting departure</span> </div>
      <div class="'.$sarr[2].'" id="'.$t_id.'s3"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the way </span> </div>
      <div class="'.$sarr[4].'" id="'.$t_id.'s4"> <span class="icon"> <i class="fa fa-check-circle"></i> </span> <span class="text">Recieved</span> </div>';
    
    }else{
      $snum=3;
      $sarr=array();
      $stp=$stat;
      $canc="";
      if($stp<0){
        $stp*=-1;
        $canc=" canceled";
      }
      for($x=0;$x<3;$x++){
        $clss="step".$canc;
        if($x<$stp){
          $clss="step active".$canc;
        }
        array_push($sarr,$clss);
      }
      echo'
      <div class="'.$sarr[0].'" id="'.$t_id.'s1"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order confirmed</span> </div>
      <div class="'.$sarr[1].'" id="'.$t_id.'s2"> <span class="icon"> <i class="fa fa-user"></i> </span> <span class="text">Ready to be picked up</span> </div>
      <div class="'.$sarr[2].'" id="'.$t_id.'s3"> <span class="icon"> <i class="fa fa-check-circle"></i> </span> <span class="text">Recieved</span> </div>';
    }


    echo'</div>
    <hr>
    <ul class="row">';
    
    for($x=0;$x<$cnt;$x++){
        $sql = "SELECT * FROM items where item_id=$item_id[$x]";  
        if($result = mysqli_query($link, $sql)){
            if(mysqli_num_rows($result) > 0){ 

              $row=mysqli_fetch_assoc($result); 
              $med=explode("|",$row['filename']);
              $catg=$row['category'];
              echo '<li class="col-md-4">
                <figure class="itemside mb-3">
                <div class="aside"><img src="'.'../..'.$row['mdir'].$med[0].'" class="img-sm border"></div>
                <figcaption class="info align-self-center">
                <p class="title">'.$row['name'].'<br>'.$catg.'</p> <span class="text-muted">₱'.$price[$x].'</span><span class="text-black mx-1">&middot;</span><span class="text-muted">x'.$qnt[$x].'</span>
                </figcaption>
                </figure>
            </li>';
            }
        }
    }
    
    

$sh="";
$tot=$dta['totprice'];

if($stat>=1&&$shp==0){
  $sh="(shipping fee: ₱".$fee.") ";
  $tot+=$fee;
}

echo '</ul>
<hr>

';

if($shp==0&&$pddir!=""&&($stat==4||$stat==3)){
echo'<button class="btn btn-warning" id="'.$t_id.'re" onclick="cre('.$t_id.',5)">Confirm shipment has been received</button>';
echo '<strong><a class="text-primary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" onclick='."mflex("."'".$pddir."')".' id="txtsfr">View prrof of delivery</a></strong>';  
}else if($shp==0&&$pddir==""&&($stat==4||$stat==3)){
  echo'<button class="btn btn-warning" id="'.$t_id.'re" onclick="cre('.$t_id.',5)">Confirm shipment has been received</button>';

}else if($shp==0&&$pddir!=""&&$stat==5){
  // echo'<button class="btn btn-warning" id="'.$t_id.'can" >Request refund</button>
  // <button class="btn btn-warning" id="'.$t_id.'re">Request exchange</button>';
  echo '<strong><a class="text-primary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" onclick='."mflex("."'".$pddir."')".' id="txtsfr">View prrof of delivery</a></strong>';  

}else if($shp==0&&$pddir==""&&$stat==5){
  // echo'<button class="btn btn-warning" id="'.$t_id.'can" >Request refund</button>
  // <button class="btn btn-warning" id="'.$t_id.'re">Request exchange</button>';
}else if($stat<=2 && $stat>=0){
  if($stat==0){
    $stn=-9;
    
  }else{
    $stn=$stat*=-1;
  }
  

  echo'<button class="btn btn-warning" id="'.$t_id.'can" onclick="cre('.$t_id.','.$stn.')">Cancel</button>';

}else if($stat<0){
  echo '<strong><a class="text-warning" style="cursor: pointer;margin-left: 10px;text-decoration:none;">Your order has been canceled</a></strong>';  
  if($pddir!=""){
    echo '<strong><a class="text-primary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" onclick="mflex()" id="txtsfr">View prrof of delivery</a></strong>';
  }
}else if($shp==1&&$pddir!=""&&($stat==4||$stat==3)){

  echo '<strong><a class="text-primary" style="cursor: pointer;margin-left: 10px;text-decoration:none;" onclick='."mflex("."'".$pddir."')".' id="txtsfr">View prrof of delivery</a></strong>';  
  }else if($shp==1&&$pddir==""&&($stat==4||$stat==3)){

  
  }

echo '<label style="float:right;">Total: '.$sh.'₱'.$tot.'</label>
</div>
</article>
</div> ';

      }
    }else{
      echo '<div id="empt">
      <div class="container py-5 text-center">
        <h1 class="display-5">'.$disp.'</h1>
      </div>
  
      <div class="row py-5 p-4 rounded shadow-sm">
           
        <div class="col-lg-12">
          <div class="rounded-pill px-4 py-3 text-uppercase font-weight-bold text-center"><a href="../../">Go to shop.</a></div>

        </div>
      </div>

      </div>
      ';
    }
}

