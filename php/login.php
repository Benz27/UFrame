<?php
   $ers=0;
if($_SERVER['REQUEST_METHOD']=='POST'){     
    $userborder="";     
    $passborder="";   
    $alertp="";    
    $userval="";   
 
$uname=$_POST['email'];    
$pass=$_POST['pass'];   

$sql="SELECT * FROM user where email='$uname'"; 
$result=mysqli_query($link, $sql);  
if($result && mysqli_num_rows($result) > 0){    


    $user_data=mysqli_fetch_assoc($result);    
    if($user_data['password']===$pass){ 
        $_SESSION['user_id']=$user_data['user_id'];
        $_SESSION['usertype']=$user_data['usertype'];   
        $_SESSION['greet']=1; 
        header("Location: ../"); 

    }else{       
        $userval=$uname;    
        // $passborder="2px solid red";    
        // $alertp="Incorrect password!"; 
        $ers=1; 
        
    }

}else{   
    $userval=$uname;   
    // $userborder="2px solid red";   
    // $alertp="Username does not belong to an account!";  
    $ers=2;
}


}
?>