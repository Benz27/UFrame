<?php
    $errmsg="";

    if($_SERVER['REQUEST_METHOD']=='POST'){ 


        $fname=$_POST['fname'];     
        $lname=$_POST['lname'];     
        $email=$_POST['email'];   
        $pass=$_POST['pass'];     
        $usertype="user";

        $user_id=mt_rand(1000000, 9999999);    
       
        
        $sql="SELECT user_id FROM user where user_id=$user_id"; 
        $result=mysqli_query($link, $sql);     
        if($result && mysqli_num_rows($result) > 0){      
            $user_data=mysqli_fetch_assoc($result);      
            while($user_id ==  $user_data['user_id']){    
                $user_id=mt_rand(1000000,9999999);     
            }

        }
     
                    $sql="INSERT INTO user (user_id, email, password, usertype) values ($user_id,'$email','$pass','$usertype')"; 
                    if ($link->query($sql) === TRUE) {
                            $sql="INSERT INTO info (user_id, fname, lname) values ($user_id,'$fname','$lname')";
                            if ($link->query($sql) === TRUE) {
                                header("Location: ../login/");
                            }else{
                                $errmsg="Error : " . $link->error;
                            }
                    }else{
            
                        $errmsg="Error : " . $link->error;
                    }

                    

            
                    

    }

    
?>