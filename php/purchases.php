<?php

if(isset($_SESSION['user_id'])){
  $id=$_SESSION['user_id'];
  
}else{
  header("Location: ../");

}

function get_item_stock($link,$id){
  $sql="SELECT item_id FROM transac where t_id=$id";
  $result = mysqli_query($link, $sql);
  if($result && mysqli_num_rows($result) > 0){ 
      $row = mysqli_fetch_array($result);
          $sql="SELECT quantity FROM inventory where item_id=$row[item_id]";
          $result = mysqli_query($link, $sql);
          $row = mysqli_fetch_array($result);


          return $row['quantity'];
  }
}

function chck_delv_req($link,$r_id){
    $sql = "SELECT * FROM delivery_request where r_id=$r_id";  
    if($resultp = mysqli_query($link, $sql)){
        if(mysqli_num_rows($resultp) > 0){ 
            return true;
        }
    }
}

function get_total($link,$t_id){
  $sql="SELECT t_id,price,r_id FROM transac where t_id=$t_id";
  $result = mysqli_query($link, $sql);
  if($result && mysqli_num_rows($result) > 0){ 
      $row = mysqli_fetch_array($result);
      $price=$row['price'];
          if($row['r_id']!=0){
              $sql="SELECT fee FROM delivery_request where r_id=$row[r_id]";
              $result = mysqli_query($link, $sql);
              if($result && mysqli_num_rows($result) > 0){ 
                  $row = mysqli_fetch_array($result);
                      $fee=$row['fee'];

                      
                  return '<label style="float:right;">Total: ₱'.$price+$fee.'(₱'.$fee.' Shipping fee)</label>';
          
              }
          }
          return '<label style="float:right;">Sub Total: ₱'.$price.'</label>';
  }
}


$disp="No current orders.";
$status="Pending";

  if(isset($_GET['o'])){
      if($_GET['o']==2){
        $status="Pending";
        $ord="and r_id = 0 and status >= 0";
      }else if($_GET['o']==3){
        $status="To Ship";
        $ord="and r_id > 0 and status >= 0 and d_id = 0 and d_stat=0";
      }else if($_GET['o']==4){
        $status="To Receive";
          $ord="and r_id > 0 and d_id > 0 and status >= 0 and d_stat=0";
      }else if($_GET['o']==5){
        $status="Completed";
          $ord="and r_id > 0 and d_id > 0 and status >= 0 and d_stat > 0";
      }
      else if($_GET['o']==6){
        $status="Canceled";
        $ord="and status < 0";
    }
  }else{
      $disp="All orders will apear here.";
      $ord="";
  }

$sql = "SELECT * FROM transac where user_id=$id $ord order by dte DESC";  
$sss=0;
if($resultp = mysqli_query($link, $sql)){

    if(mysqli_num_rows($resultp) > 0){ 
      while($dta = mysqli_fetch_array($resultp)){ 


        $r_id=$dta['r_id'];
        $d_id=$dta['d_id'];
        $d_stat=$dta['d_stat'];
        $status="Pending";
        $canc=true;
        $buy_again=false;
        $statu=$dta['status'];
        if($r_id!=0){
          $status="To Ship";
          $canc=false;

          if($d_id!=0){
            $status="To Receive";
            $canc=false;

            if($d_stat!=0){
              $status="Completed";
              $canc=false;
              $buy_again=true;
            }
          }

        }
        if($statu<0){
          $status="Canceled";
          $canc=false;
          $buy_again=true;
        }


        $item_id=$dta['item_id'];
        $t_id=$dta['t_id'];
        $shp=$dta['shp'];
        $fee=$dta['fee'];
        $pddir=$dta['pddir'];
        $price=$dta['price'];
        $qnt=$dta['qnt'];
        $stat=$dta['status'];

        $pdfname=$dta['pdfname'];
        $tot=$price;
        echo '<div class="container">
        <article class="card">
    <div class="card-body" id="'.$t_id.'div">
    <h6>Order ID: '.$t_id.' <span style="float:right;">'.$status.'</span></h6>

    <hr>';




    $sql = "SELECT * FROM inventory where item_id=$item_id";  
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){ 

          $row=mysqli_fetch_assoc($result); 
          $med=explode("|",$row['media']);
          $v_id=$row['v_id'];
          $name=$row['name'];
          $src='../../images/products/'.$row['item_id'];
          if($v_id>0){
            $src='../../images/vproducts/'.$row['v_id'];
            $name=$row['name'].'<span class="text-secondary"> - '.$row['v_name'].'</span>';
          }


          $catg=$row['category'];
          echo '<a href="../purchase/?id='.$t_id.'" style="text-decoration:none;color:black;">
            <figure class="itemside mb-3">
            <div class="aside"><img src="'.$src.'/'.$med[0].'" class="img-sm border"></div>
            <figcaption class="info align-self-center">
            <h5 class="title">'.$name.'</h5><p>'.$catg.'</p><span class="text-black mx-1">&middot;</span><span class="text-muted">x'.$qnt.'</span>
            </figcaption>
            </figure></a>
        ';
        }
    }

    if($stat==0){
      $stn=-9;
      
    }else{
      $stn=$stat*=-1;
    }
    $sh="";
    if($stat>=1&&$shp==0){
      $sh="(shipping fee: ₱".$fee.") ";
      $tot+=$fee;
    }
    
    echo''.get_total($link,$t_id).'
    <br>
    <hr>';


      if($canc){
        echo '<button class="btn btn-warning" id="'.$t_id.'can" onclick="cancel('.$t_id.')" style="float:right;">Cancel</button>';
      }
      if($buy_again){
        echo '<button onclick="addto('.$item_id.',0,'.get_item_stock($link,$t_id).')" class="btn btn-warning" style="float:right;">Buy Again</button>';
      }

    echo '
    </div>
    </article>
    </div> 
    <br>';


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

