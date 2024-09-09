<?php


function table_content1_delvlist($link, $app_id){
    $str="";
    $arr=array();
    $sql="SELECT * FROM delivery where app_id = $app_id";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_array($result)){
                $order_id=$row['t_id'];
                $status=$row['status'];
                $stat="On the Way";
                if($status==1){
                    $stat="Delivered";
                }
                $str.='<tr>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">'.$row['d_id'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">'.$row['name'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">'.get_items($link, $order_id).'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">₱'.$row['fee'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">'.$stat.'</a></td>    
                </tr>';
            }
        }

array_push($arr,$str);
array_push($arr,get_tot_del($link,$app_id));
array_push($arr,get_tot_price($link,$app_id));
echo json_encode($arr);
}

function get_tot_del($link,$id){

    $sql="SELECT count(d_id) as 'count' FROM delivery where app_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            
            return $row['count'];

    }

}

function get_tot_price($link,$id){

    $sql="SELECT sum(fee) as 'fee' FROM delivery where app_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            if($row['fee']!=NULL){
                return $row['fee'];
            }

            return 0;
    }
    return 0;
}


function get_app($link,$id){

            $sql="SELECT name FROM delivery_apps where app_id=$id";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                    return $row['name'];
        
            }
        
}


function table_content2_delvlist($link){
$str="";
$arr=array();
    $sql="SELECT * FROM delivery";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_array($result)){
                $order_id=$row['t_id'];
                $status=$row['status'];
                $stat="On the Way";
                $app_id=$row['app_id'];
                if($status==1){
                    $stat="Delivered";
                }
                $str.= '<tr>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">'.get_items($link, $order_id).'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">₱'.$row['fee'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">'.$row['delivered'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$order_id.'" style="text-decoration: none;">'.get_app($link,$app_id).'</a></td>
                </tr>';
            }
        }

array_push($arr,$str);
array_push($arr,get_top_delivery($link));
echo json_encode($arr);
}

function get_top_delivery($link){
    $sql="Select app_id, count(*) as 'count' from delivery where app_id in (SELECT app_id from delivery) and delivered <> ''
    group by app_id order by count DESC LIMIT 1;";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);

        $id=$row['app_id'];
        if($row['app_id']!=NULL){
            return get_app($link,$id);
        }

           
    }

    return 0;

}


function get_items($link, $order_id){
    $sql="SELECT item_id FROM transac where t_id=$order_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $sql="SELECT name,v_id,v_name FROM inventory where item_id=$row[item_id]";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            $row = mysqli_fetch_array($result);
            $v_id=$row['v_id'];
            $name=$row['name'];
            if($v_id>0){
    
              $name=$row['name'].'<span class="text-secondary"> - '.$row['v_name'].'</span>';
            }
    
    
    
            return $name;
            
        }
    }


 

}

function get_items_id($link, $order_id){
    $sql="SELECT item_id FROM transac where t_id=$order_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['item_id'];
    }
}
