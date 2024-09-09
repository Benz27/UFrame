<?php
$adbhost = "";
$adbuser = "";
$adbpass = "";
$adbname = "";

$alink = mysqli_connect($adbhost, $adbuser, $adbpass, $adbname);


$nav_fname= "";
$nav_lname= "";
$usertype;
$priv;
$nav_pic="img/empty.png";
if($alink === false){
    die("Could not connect!" . mysqli_connect_error());
}

function check_login($alink){

    if(isset($_SESSION['admin_user_id'])){
        $id = $_SESSION['admin_user_id']; 

        $sql = "SELECT * FROM info where user_id=$id";
    if($result = mysqli_query($alink, $sql)){
  if(mysqli_num_rows($result) > 0){ 
      while($row = mysqli_fetch_array($result)){  
        $GLOBALS['nav_fname']= $row['fname'];
        $GLOBALS['nav_lname']= $row['lname'];
        if($row['pic']!=""){
            $GLOBALS['nav_pic']= $row['pic'];
        }

        }
      mysqli_free_result($result);
  } else{
      echo "No records matching your query were found.";
  }
} else{
  echo "ERROR: Could not able to execute $sql. " . mysqli_error($alink);
}

        
        $query="select user_id,usertype,privilage from user where user_id = '$id'";    
        $result=mysqli_query($alink,$query);     
        if($result && mysqli_num_rows($result) > 0){   


            $user_data=mysqli_fetch_assoc($result); 
            $GLOBALS['usertype']=$user_data['usertype'];
            $GLOBALS['priv']=$user_data['privilage'];
            return $user_data['user_id'];

        }
    }   

    header("Location: ./login.html");

}

function check_root(){
        // if($GLOBALS['priv']!="admin"){
        //     header("Location: ./");
        // }

}
?>