<?php

if($_SERVER['REQUEST_METHOD']=='POST'){

date_default_timezone_set("Asia/Manila");
$user_id=$_SESSION['user_id'];  
$item_id=explode(" ",$_GET['items']);
$shp=$_GET['shp'];
$cnt=count($item_id);   
$street=$_POST['street'];

$city=$_POST['city'];
$prov=$_POST['prov'];
$fname=$_POST['name'];
$num=$_POST['num'];
$email=$_POST['email'];
$note=$_POST['note'];
$iprice=array();;
$ids=str_replace(" ","+",$_GET['items']);
$qnt=array();
$totprice=0;
$cont=true;
$erarr=array();

$o_id=mt_rand(1000000, 9999999);    
  
 $sql="SELECT o_id FROM transac where o_id=$o_id";     
 $result=mysqli_query($link, $sql);    
 if($result && mysqli_num_rows($result) > 0){        
     $user_data=mysqli_fetch_assoc($result);       
     while($o_id ==  $user_data['o_id']){     
         $o_id=mt_rand(1000000,9999999);       
     }
 }


$red=0;
for($x=0;$x<$cnt;$x++){
  foreach (array_keys($s_cart, $item_id[$x]) as $key) {
    $k=$key;
  }

  $sql = "SELECT * FROM inventory where item_id=$item_id[$x]";  
                          
              
  if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){ 
      $row=mysqli_fetch_assoc($result); 

     
      $pqnt=$s_qnt[$k];
            
      $price=$row['selling_price']*$pqnt;
      $totprice+=$price;



    }
  }


  $t_id=mt_rand(1000000, 9999999);    
  
 $sql="SELECT t_id FROM transac where t_id=$t_id";     
 $result=mysqli_query($link, $sql);    
 if($result && mysqli_num_rows($result) > 0){        
     $user_data=mysqli_fetch_assoc($result);       
     while($t_id ==  $user_data['t_id']){     
         $t_id=mt_rand(1000000,9999999);       
     }
 }

  if($shp==0){
    $shp=="Cash on Delivery";
  }
  $trdte=date('Y-m-d');
  $trdte1=date('Y-m-d H:i:s');
  $tmedisp=date("g:iA");
  $mtme=date("G:i");
  $dtedisp=date('n/d/Y').' at '.date('g:iA');

 $dtenum= (date('Y')*10000)+(date('n')*100)+date('j');

   $sql = "INSERT INTO transac values ($t_id,'$item_id[$x]','$o_id',
    $user_id,$price,$s_qnt[$k],
   '$fname','$street','$city',
   '$prov','$email','$num',0,'$trdte1','$dtedisp',
   $dtenum,'$mtme','$tmedisp','$note',$shp,0,0,0,
   0,0,'','','')";


if ($link->query($sql) === TRUE) {

}else{
  $red+=1;
  array_push($erarr,$link->error);
}

}


if($red==0){


  for($x=0;$x<$cnt;$x++){
    $targ;
    $a=$s_cart;
    $b=$s_qnt;
      $sqnt=0;
    
    foreach (array_keys($a, $item_id[$x]) as $key) {
        $targ=$key;
    }
    


      $s_cart=array_merge(array_slice($a,0,$targ), array_slice($a,$targ+1));
      $s_qnt=array_merge(array_slice($b,0,$targ), array_slice($b,$targ+1));
      
      updc($s_cart,$s_qnt,$link);
      dc($link);
      
    
   }








  header("Location: ../profile/orders/");
}else{
  echo '0,';
  echo implode(",",$erarr);
}

}

$c_fname="";
$c_lname="";
$c_email="";
$c_phone="";
$c_street="";
$c_city="";
$c_prov="";

$h_hid="";

if(isset($_SESSION['user_id'])){
  include("address.php");
$sql="select * from info where user_id=$_SESSION[user_id]";
$result=mysqli_query($link, $sql);  
  if($result && mysqli_num_rows($result) > 0){        
        $row=mysqli_fetch_assoc($result);       
        $c_fname=$row['fname'];
        $c_lname=$row['lname'];
        if(count($a_id)>0){
          $c_street=$a_street[$a_sel];
          $c_city=$a_city[$a_sel];
          $c_prov=$a_prov[$a_sel];
          $c_email=$a_email[$a_sel];
          $c_phone=$a_phone[$a_sel];
        }
        $h_hid="hidden";
  }
}
function user(){
  echo'
  <button class="btn btn-sm btn-primary mb-2" type="button"  data-toggle="modal" data-target=".bd-example-modal-lg" onclick="clear_mod(0);ref_lst();">Manage Addresses</button>
  ';
if($GLOBALS['addr_count']>0){
  echo'<div class="row justify-content-between">
          <div class="col-12 mt-0">
              <p><b id="f_addr">'.$GLOBALS['chk_address'].'</b></p>
          </div>
          <div class="col-12" hidden>
              <p><b id="f_addr2">'.$GLOBALS['i_email'].'</b></p>
          </div> 
          <div class="col-12">
              <p><b id="f_addr3">'.$GLOBALS['i_phone'].'</b></p>
          </div> 
      </div>';
}else{
  echo'<div class="row justify-content-between">
          <div class="col-12 mt-0">
              <p><b id="f_addr"></b></p>
          </div>
          <div class="col-12">
              <p><b id="f_addr2"></b></p>
          </div> 
          <div class="col-12">
              <p><b id="f_addr3"></b></p>
          </div> 
      </div>';
}

echo '<div class="form-group" hidden><label for="STREET" class="small text-muted mb-1"><span style="color: red;">*</span>STREET/BLOCK</label> <input type="text" class="form-control form-control-sm" name="street" id="street" aria-describedby="helpId" placeholder="Your street" value="'.$GLOBALS['c_street'].'"> </div>
<div class="form-group" hidden><label for="CITY" class="small text-muted mb-1"><span style="color: red;">*</span>CITY</label> <input type="text" class="form-control form-control-sm" name="city" id="city" aria-describedby="helpId" placeholder="Your city" value="'.$GLOBALS['c_city'].'"> </div>
<div class="form-group" hidden><label for="PROVINCE" class="small text-muted mb-1"><span style="color: red;">*</span>PROVINCE</label> <input type="text" class="form-control form-control-sm" name="prov" id="prov" aria-describedby="helpId" placeholder="Your province" value="'.$GLOBALS['c_prov'].'"> </div>

        <hr class="mt-0">


<div class="form-group"> <label for="NAME" class="small text-muted mb-1"><span style="color: red;">*</span>NAME</label> <input type="text" class="form-control form-control-sm" name="name" id="name" aria-describedby="helpId" placeholder="Your name" value="'.$GLOBALS['c_fname'].' '.$GLOBALS['c_lname'].'"> </div>
<div class="form-group" hidden> <label for="NAME" class="small text-muted mb-1"><span style="color: red;">*</span>PHONE NUMBER</label> <input type="text" class="form-control form-control-sm" name="num" id="num" aria-describedby="helpId" placeholder="Your phone number" value="'.$GLOBALS['c_phone'].'"> </div>
<div class="form-group" hidden> <label for="NAME" class="small text-muted mb-1"><span style="color: red;">*</span>EMAIL ADDRESS</label> <input type="text" class="form-control form-control-sm" name="email" id="email" aria-describedby="helpId" placeholder="Your email address" value="'.$GLOBALS['c_email'].'"> </div>
';


}
function guest(){


echo '<div class="form-group"><label for="STREET" class="small text-muted mb-1"><span style="color: red;">*</span>STREET/BLOCK</label> <input type="text" class="form-control form-control-sm" name="street" id="street" aria-describedby="helpId" placeholder="Your street" value=""> </div>
<div class="form-group"><label for="CITY" class="small text-muted mb-1"><span style="color: red;">*</span>CITY</label> <input type="text" class="form-control form-control-sm" name="city" id="city" aria-describedby="helpId" placeholder="Your city" value=""> </div>
<div class="form-group"><label for="PROVINCE" class="small text-muted mb-1"><span style="color: red;">*</span>PROVINCE</label> <input type="text" class="form-control form-control-sm" name="prov" id="prov" aria-describedby="helpId" placeholder="Your province" value=""> </div>

<div class="row mt-4">
    <div class="col">
        <p class="text-muted mb-2">CONTACT DETAILS</p>
        <hr class="mt-0">
    </div>
</div>

<div class="form-group"> <label for="NAME" class="small text-muted mb-1"><span style="color: red;">*</span>NAME</label> <input type="text" class="form-control form-control-sm" name="name" id="name" aria-describedby="helpId" placeholder="Your name" value=""> </div>
<div class="form-group"> <label for="NAME" class="small text-muted mb-1"><span style="color: red;">*</span>PHONE NUMBER</label> <input type="text" class="form-control form-control-sm" name="num" id="num" aria-describedby="helpId" placeholder="Your phone number" value=""> </div>
<div class="form-group"> <label for="NAME" class="small text-muted mb-1"><span style="color: red;">*</span>EMAIL ADDRESS</label> <input type="text" class="form-control form-control-sm" name="email" id="email" aria-describedby="helpId" placeholder="Your email address" value=""> </div>
';


}

function get_min_max_rates($link){
  $max_id=0;
  $sql="SELECT app_id FROM delivery_apps where rate_per_kg = (SELECT max(rate_per_kg) as 'max' FROM delivery_apps) LIMIT 1";
  $result = mysqli_query($link, $sql);
  if($result && mysqli_num_rows($result) > 0){ 
      $row = mysqli_fetch_array($result);
      $max_id=$row['app_id'];
  }

  $min_id=0;
  $sql="SELECT app_id FROM delivery_apps where rate_per_kg = (SELECT min(rate_per_kg) as 'min' FROM delivery_apps) LIMIT 1";
  $result = mysqli_query($link, $sql);
  if($result && mysqli_num_rows($result) > 0){ 
      $row = mysqli_fetch_array($result);
      $min_id=$row['app_id'];
  }
  return $min_id.'|'.$max_id;

}


function get_rate($link,$item_id, $app_id){

  $sql="SELECT rate_per_kg FROM delivery_apps where app_id=$app_id";
  $result = mysqli_query($link, $sql);
  if($result && mysqli_num_rows($result) > 0){ 
      $row = mysqli_fetch_array($result);
      $rate=$row['rate_per_kg'];

      $sql="SELECT weight,weight_unit FROM inventory where item_id=$item_id";
      $result = mysqli_query($link, $sql);
      if($result && mysqli_num_rows($result) > 0){ 
          $row = mysqli_fetch_array($result);
          if($row['weight_unit']=="oz"){
              $rte_unit=0.0283495;
          }else if($row['weight_unit']=="lb"){
              $rte_unit=0.453592;
          }else if($row['weight_unit']=="mg"){
              $rte_unit=0.000001;
          }else if($row['weight_unit']=="g"){
              $rte_unit=0.001;
          }else if($row['weight_unit']=="kg"){
              $rte_unit=1;
          }
          $total= ($rate*$rte_unit)*$row['weight'];
         return number_format((float)$total, 2, '.', '');
      }
  }
}
