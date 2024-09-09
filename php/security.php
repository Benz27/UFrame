<?php


function chngpass($link){

    $cpass=$_POST['cpass'];
    $npass=$_POST['npass'];

    $s_user_id = $_SESSION['user_id']; 
    $query="select password from user where user_id = $s_user_id";    
    $s_result=mysqli_query($link, $query);
    if($s_result && mysqli_num_rows($s_result) > 0){  
        $s_row = mysqli_fetch_array($s_result);
    

                if($cpass!=$s_row['password']){
                    echo 2;
                    return;
                }

       
    }



        $sql="UPDATE user SET password = '$npass' WHERE user_id=$s_user_id"; 
        if ($link->query($sql) === TRUE) {
            echo 1;
        } else {
            echo 'update error: '.$link->error;
        }
    



}

function delacc($link){
    $sql="DELETE FROM user WHERE user_id=$_SESSION[user_id]"; 
    if ($link->query($sql) === TRUE) {
        unset($_SESSION['user_id']);
       echo 1;
    } else {
        echo 'update error: '.$link->error;
    }
}
