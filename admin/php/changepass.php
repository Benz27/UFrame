<?php
$errmsg="";
if($_SERVER['REQUEST_METHOD']=='POST'){

    $cpass= $_POST['cpass'];
    $npass= $_POST['npass'];
    $conpass= $_POST['conpass'];
    $er=0;
    $scc=0;
    $sql="select password from user where user_id=$_SESSION[admin_user_id]";
    if($result = mysqli_query($alink, $sql)){
        if(mysqli_num_rows($result) > 0){ 
            $row = mysqli_fetch_array($result);
            $pass=$row['password'];
        }
    }

        if($pass==$cpass){
            if($npass==$conpass){
            $sql = "UPDATE user SET password='$npass' WHERE user_id=$_SESSION[admin_user_id]";
            if ($alink->query($sql) === TRUE){
                $scc=1;
            }else{
                $er=3;
                $errmsg= "Error updating record: " . $alink->error;
            }

            }else{
                $er=2;
            }
        }else{
            $er=1;
        }
}