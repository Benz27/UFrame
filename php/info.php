<?php


function savinf($link){

    $fname=$_POST['fname'];
    $lname=$_POST['lname'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];

    $s_user_id = $_SESSION['user_id']; 
    $query="select user_id,email,phone from info";    
    $s_result=mysqli_query($link, $query);
    if($s_result && mysqli_num_rows($s_result) > 0){  
        while($s_row = mysqli_fetch_array($s_result)){
            if($s_row['user_id']!=$s_user_id){

                if($email==$s_row['email']){
                    echo 2;
                    return;
                }
                if($phone==$s_row['phone']){
                    echo 3;
                    return;
                }

            }
        };
    }



        $sql="UPDATE info SET fname = '$fname', lname= '$lname', email='$email', phone='$phone' WHERE user_id=$s_user_id"; 
        if ($link->query($sql) === TRUE) {
            echo 1;
        } else {
            echo 'update error: '.$link->error;
        }
    



}

