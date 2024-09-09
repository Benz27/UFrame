<?php

session_start();

include("conn.php");

$sql="SELECT status FROM transac where t_id=$id";  
            if($result=mysqli_query($link, $sql));          
            $row=mysqli_fetch_assoc($result);  

            echo $row['status'];