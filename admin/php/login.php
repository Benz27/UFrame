<?php

    $ers=0;
if($_SERVER['REQUEST_METHOD']=='POST'){   
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "ufadmindbase";

    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if($link === false){
        die("Could not connect!" . mysqli_connect_error());
    }

    $userborder="";     
    $passborder="";   
    $alertp="";    
    $userval="";   

    $uname=$_POST['uname'];    
    $pass=$_POST['password'];   

    $sql="SELECT * FROM user where username='$uname'"; 
    $result=mysqli_query($link, $sql);  
    if($result && mysqli_num_rows($result) > 0){    
        $user_data=mysqli_fetch_assoc($result);    
        if($user_data['password']===$pass){ 
            $_SESSION['admin_user_id']=$user_data['user_id'];
            $_SESSION['usertype']=$user_data['usertype'];   
            header("Location: ./"); 
        }else{       
            $userval=$uname;    
            $ers=1; 
        }
    }else{   
        $userval=$uname;   
        $ers=2;
    }


}
?>