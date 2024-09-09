<?php

function get_spent($link,$user_id){
    $sql="SELECT sum(price) as 'price' FROM transac where user_id=$user_id";
    $result = mysqli_query($link, $sql);

    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['price']!=NULL){
            return $row['price'];
        }
    

    }

    return 0;
}

function get_item_count($link,$user_id){
    $sql="SELECT count(t_id) as 'cnt' FROM transac where user_id=$user_id";
    $result = mysqli_query($link, $sql);

    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);

            return $row['cnt'];
     
    }

    
}

function get_name($link,$user_id){
    $sql="SELECT fname, lname FROM info where user_id=$user_id";
    $result = mysqli_query($link, $sql);

    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['fname'].' '.$row['lname'];

    }
}


function get_users($link){

    $sql="SELECT * FROM user";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_array($result)){
               
                $style="text-decoration: none;cursor:pointer;";
                $onclick="user.html?id=$row[user_id]";

                echo '<tr>
                    <td><a class="text-dark" style="'.$style.'" href="'.$onclick.'">'.get_name($link,$row['user_id']).'</a></td> 
                    <td><a class="text-dark" style="'.$style.'" href="'.$onclick.'">'.$row['dtedisp'].'</a></td>
                    <td><a class="text-dark" style="'.$style.'" href="'.$onclick.'">'.get_item_count($link,$row['user_id']).'</a></td>
                    <td><a class="text-dark" style="'.$style.'" href="'.$onclick.'">₱'.get_spent($link,$row['user_id']).'</a></td>
                </tr>';
        
        
            }
    
        }
    
    
    
    
    }
    

