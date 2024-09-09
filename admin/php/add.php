<?php
    if($_SERVER['REQUEST_METHOD']=='POST'){ 

        $uname=$_POST['uname'];
        $fname=$_POST['fname'];     
        $lname=$_POST['lname'];     
        $email=$_POST['email'];   
     
        $street=$_POST['street'];   
        $city=$_POST['city'];   
        $prov=$_POST['prov'];   
        $country=$_POST['country']; 
        $contno=$_POST['contno']; 
        $dte=$_POST['dte']; 
        $pass=$_POST['pass']; 
        $priv=$_POST['priv'];  
        $user_type;
        if($priv==0){
            $user_type="admin";
        }else{
            $user_type="assisstant";
        }

        $user_id=mt_rand(1000000, 9999999);    
       
        
        $sql="SELECT user_id FROM user where user_id=$user_id"; 
        $result=mysqli_query($link, $sql);     
        if($result && mysqli_num_rows($result) > 0){      
            $user_data=mysqli_fetch_assoc($result);      
            while($user_id ==  $user_data['user_id']){    
                $user_id=mt_rand(1000000,9999999);     
            }

        }
     
                    $sql="INSERT INTO user (user_id, email, password, usertype, privilage) values ($user_id,'$email','$pass','$user_type',$priv)"; 
                    if ($link->query($sql) === TRUE) {
                            $sql="INSERT INTO info (user_id, fname, lname,address,contno,street,city,prov,country,dte) values ($user_id,'$fname','$lname')";
                            if ($link->query($sql) === TRUE) {
                                header("Location: ../lstacc.html");
                            }else{
                                $errmsg="Error : " . $link->error;
                            }
                    }else{
            
                        $errmsg="Error : " . $link->error;
                    }

                    

            
                    

    }

    