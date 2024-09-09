<?php
    $s_id=false;
    
function check_login($link){

    if(isset($_SESSION['user_id'])){       
        $GLOBALS['s_id']=true;
        
        $id = $_SESSION['user_id'];     
        $query="select user_id from user where user_id = '$id'";    
        $result=mysqli_query($link,$query);     
        if($result && mysqli_num_rows($result) > 0){    

            return $_SESSION['user_id'];      

        }
    }   
    return 0;  



}



