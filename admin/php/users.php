<?php
date_default_timezone_set("Asia/Manila");

// $type=$_GET['type'];

// switch ($type) {

//     case 0:
//         delv($link,"and status < 5");
//     break;
//     case 1:
//         pick($link,"and status = 5");
//     break;

// }
function get_spent($link,$user_id){
    $sql="SELECT sum(price) as 'price',status FROM transac where user_id=$user_id and status >=0 and d_stat = 1";
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

//functions
function get_user_email($link,$user_id){
    $sql="SELECT email FROM user where user_id=$user_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['email'];
    }
}


function get_user_orders($link,$type,$user_id){
    function get_items($link, $item_id){
        $sql="SELECT name FROM inventory where item_id=$item_id";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            $row = mysqli_fetch_array($result);
            return $row['name'];
            
        }
    
}

    if($type==0){
        $ord="";
    }else if($type==1){
        $ord="and r_id > 0 and d_id = 0 and d_stat = 0 and status >= 0";
    }else if($type==2){
        $ord="and r_id > 0 and d_id > 0 and d_stat = 0 and status >= 0";
    }else if($type==3){
        $ord="and r_id > 0 and d_id > 0 and d_stat > 0 and status >= 0";
    }else if($type==4){
        $ord="and status < 0";
    }
    else if($type==6){
        $ord="and r_id = 0 and d_id = 0 and d_stat = 0 and status >= 0";
    }
    $sql="SELECT * FROM transac where user_id=$user_id $ord order by dte DESC";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){


            if($row['status']>=0){
                $class="badge bg-light text-dark";
                $stat="Pending";
                if($row['r_id']>0 && $row['d_id']==0 && $row['d_stat']==0){
                    $stat="To Ship";
                    $class="badge bg-info text-dark";
                }else if($row['r_id']>0 && $row['d_id']>0 && $row['d_stat']==0){
                    $stat="On the Way";
                    $class="badge bg-primary text-light";
                }else if($row['r_id']>0 && $row['d_id']>0 && $row['d_stat']>0){
                    $stat="Completed";
                    $class="badge bg-success text-light";
                }
            }else{
                $stat="Canceled";
                $class="badge bg-warning text-dark";
            }
                if($row['payment']==0){
                    $payment="Cash on delivery";
                }
                
                $item_id=$row['item_id'];

            // <th>Date</th>
            // <th>Order ID</th>
            // <th>Items</th>
            // <th>Buyer</th>
            // <th>Email</th>
            // <th>Type</th>
            // <th>Price</th>
            echo '<tr>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['t_id'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['dtedisp'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.get_items($link, $item_id).'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['qnt'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">₱'.$row['price'].'</a></td>    
                <td><a class="'.$class.'" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$stat.'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$payment.'</a></td>
            </tr>';
    
    
        }
    
    }
}


function get_user_total_orders($link,$user_id){
    $sql="SELECT count(t_id) as 'count' FROM transac where user_id=$user_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['count'];
        
    }
}

function get_item_name($link,$id){
    $sql="SELECT item_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT name FROM inventory where item_id=$row[item_id]";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            return $row['name'];
    }
}


function get_frequent_order($link,$user_id){
    $sql="Select item_id, count(*) as 'count',user_id from transac where item_id in (SELECT item_id from transac) and user_id=$user_id
    group by item_id order by count DESC LIMIT 1;";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            if($row['count']>0){
                $sql="SELECT name,v_id,v_name FROM inventory where item_id=$row[item_id]";
                $result = mysqli_query($link, $sql);
                $row = mysqli_fetch_array($result);
                $v_id=$row['v_id'];
                $name=$row['name'];
                if($v_id>0){
    
                  $name=$row['name'].'<span class="text-secondary"> - '.$row['v_name'].'</span>';
                }
    
    
    
                return $name;
            }
           
    }

    return 0;

}