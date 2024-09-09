<?php
session_start();
include("conn.php");
isset($_GET['e']);
$em=$_GET['e'];
if(isset($_GET['v'])){

    $query="select user_id from t_user where email = '$em'";    
    $result=mysqli_query($link,$query);     
    if($result && mysqli_num_rows($result) > 0){   
        $row = mysqli_fetch_array($result);
        $_SESSION['track_id']=$row['user_id'];
        echo 1;

    }else{
        echo $link->error;
    }

}else{
  

    $query="select email from transac where email = '$em'";    
            $result=mysqli_query($link,$query);     
            if($result && mysqli_num_rows($result) > 0){   
    
                echo 1;
    
            }else{
                echo 0;
            }
}
