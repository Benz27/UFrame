<?php

if($_SERVER['REQUEST_METHOD']=='POST'){
  include("aconn.php");
include("read.php");
date_default_timezone_set("Asia/Manila");
    $link=$alink;
  $ab_uname= $_POST['uname'];
  $ab_fname= $_POST['fname'];
  $ab_lname= $_POST['lname'];
  $ab_email = $_POST['email'];
  $ab_street= $_POST['street'];
  $ab_city= $_POST['city'];
  $ab_prov= $_POST['prov'];
  $ab_contno= $_POST['contno'];
  $ab_country=  $_POST['country'];
  $ab_dte=  $_POST['dte'];
  $pass=$_POST['pass'];
  $dtec=date('m/d/Y').' at '.date('g:iA');
    $user_id=mt_rand(1000000,9999999);   
  $sql="SELECT user_id FROM user where user_id=$user_id"; 
  $result=mysqli_query($link, $sql);     
  if($result && mysqli_num_rows($result) > 0){      
      $user_data=mysqli_fetch_assoc($result);      
      while($user_id ==  $user_data['user_id']){    
          $user_id=mt_rand(1000000,9999999);     
      }

  }

              $sql="INSERT INTO user values ($user_id,'$ab_uname','$ab_email','$pass','admin',1,0,'0000-00-00 00:00:00','$dtec')"; 
              if ($link->query($sql) === TRUE) {
  
                      $sql="INSERT INTO info values ($user_id,'$ab_fname','$ab_lname','','','$ab_contno','$ab_street','$ab_city','$ab_prov','$ab_country','','','$ab_dte')";
                      if ($link->query($sql) === TRUE){
                        echo 1;
                      }else{
                          $errmsg="Error : " . $link->error;
                      }

              }else{
      
                  $errmsg="Error : " . $link->error;
              }

            
              

}

?>

