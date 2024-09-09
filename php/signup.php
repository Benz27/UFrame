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
        $dte=date('Y-m-d H:i:s');
        $dtedisp=date('m/d/Y');
        $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');


                    $sql="INSERT INTO user (user_id, email, password, usertype,dte,dtedisp,dtenum) values ($user_id,'$email','$pass','$usertype','$dte','$dtedisp',$dtenum)"; 
                    if ($link->query($sql) === TRUE) {
        
                            $sql="INSERT INTO info (user_id, fname, lname,email,sel) values ($user_id,'$fname','$lname','$email',-1)";
                            if ($link->query($sql) === TRUE){
                                $sql="INSERT INTO cart (user_id) values ($user_id)";
                                if ($link->query($sql) === TRUE){
                                    $_SESSION['user_id']=$user_id;
                                    $_SESSION['usertype']="user";  
                                    $_SESSION['greet']=1;  
                                    header("Location: ../"); 
                                }else{
                                    $errmsg="Error  : " . $link->error;
                                }
                            }else{
                                $errmsg="Error : " . $link->error;
                            }

                    }else{
            
                        $errmsg="Error : " . $link->error;
                    }

                    

            
                    

    }

    
?>