<?php


session_start();
include("aconn.php");
include("read.php");



$x=$_GET['fun'];





switch ($x) {
    case "updt":
        updt($alink);
    break;
    case "chngpass":
        chngpass($alink);
    break;
    case "delacc":
        delacc($alink);
    break;
}







function updt($alink){
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
  $user_id=  $_POST['id'];


              $sql="UPDATE user set username='$ab_uname',email='$ab_email' where user_id=$user_id"; 
              if ($link->query($sql) === TRUE) {
  
                      $sql="UPDATE info set fname='$ab_fname',lname='$ab_lname',contno='$ab_contno',street='$ab_street',city='$ab_city',prov='$ab_prov',country='$ab_country',dte='$ab_dte' where user_id=$user_id";
                      if ($link->query($sql) === TRUE){
                        echo 1;
                      }else{
                          echo"Error : " . $link->error;
                      }

              }else{
      
                echo "Error : " . $link->error;
              }
}


function chngpass($alink){
    $link=$alink;
  $ab_pass=  $_POST['pass'];
  $user_id=  $_POST['id'];


              $sql="UPDATE user set password='$ab_pass' where user_id=$user_id"; 
              if ($link->query($sql) === TRUE) {
  
                echo 1;

              }else{
      
                echo "Error : " . $link->error;
              }
}    
              
function delacc($alink){
    $link=$alink;
    $sql="DELETE FROM user WHERE user_id=$_POST[id]"; 
    if ($link->query($sql) === TRUE) {
        echo 1;
    } else {
        echo 'delete error: '.$link->error;
    }
}    


                                        


