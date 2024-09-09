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



//functions
function get_items($link, $item_id)
    {
        $sql = "SELECT name,v_id,v_name FROM inventory where item_id=$item_id";
        $result = mysqli_query($link, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $v_id=$row['v_id'];
            $name=$row['name'];
            if($v_id>0){

              $name=$row['name'].'<span class="text-secondary"> - '.$row['v_name'].'</span>';
            }



            return $name;
        }
    }

    
function orders($link, $type, $catg)
{

   
    if ($type == 0) {
        $ord = "";
    } else if ($type == 1) {
        $ord = "where r_id > 0 and d_id = 0 and d_stat = 0 and status >= 0";
    } else if ($type == 2) {
        $ord = "where r_id > 0 and d_id > 0 and d_stat = 0 and status >= 0";
    } else if ($type == 3) {
        $ord = "where r_id > 0 and d_id > 0 and d_stat > 0 and status >= 0";
    } else if ($type == 4) {
        $ord = "where status < 0";
    } else if ($type == 6) {
        $ord = "where r_id = 0 and d_id = 0 and d_stat = 0 and status >= 0";
    }
    $scatg="";
    if($catg!=0){

        if($ord!=""){
            $scatg="and item_id IN (SELECT item_id from inventory where category = '$catg')";
        }else{
            $scatg="where item_id IN (SELECT item_id from inventory where category = '$catg')";
        }
    }


    $sql = "SELECT * FROM transac $ord $scatg order by dte DESC";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {


            if ($row['status'] >= 0) {
                $class = "badge bg-light text-dark";
                $stat = "Pending";
                if ($row['r_id'] > 0 && $row['d_id'] == 0 && $row['d_stat'] == 0) {
                    $stat = "To Ship";
                    $class = "badge bg-info text-dark";
                } else if ($row['r_id'] > 0 && $row['d_id'] > 0 && $row['d_stat'] == 0) {
                    $stat = "On the Way";
                    $class = "badge bg-primary text-light";
                } else if ($row['r_id'] > 0 && $row['d_id'] > 0 && $row['d_stat'] > 0) {
                    $stat = "Completed";
                    $class = "badge bg-success text-light";
                }
            } else {
                $stat = "Canceled";
                $class = "badge bg-warning text-dark";
            }
            if ($row['payment'] == 0) {
                $payment = "Cash on delivery";
            }

            $item_id = $row['item_id'];

            echo '<tr>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['dtedisp'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['t_id'] . '</a></td>
                <td><a class="' . $class . '" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $stat . '</a></td>
                <td><a class="text-primary" href="user.html?id=' . $row['user_id'] . '" style="text-decoration: none;">' . $row['fname'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . get_items($link, $item_id) . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['qnt'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['price'] . '</a></td>    
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['email'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['street'] . ', ' . $row['city'] . ', ' . $row['prov'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $payment . '</a></td>
            </tr>';
        }
    }
}





function delv($link, $ord)
{
    $sql = "SELECT * FROM transac where shp=0 $ord order by dte";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $stat = "";
            $clss = "light text-dark";

            $dlvid = $row['d_id'];

            switch ($row['status']) {
                case 0:
                    $stat = "Pending";
                    break;
                case 1:
                    $stat = "Confirmed";
                    $clss = 'info text-dark';
                    break;
                case 2:
                    $stat = "Waiting departure";
                    $clss = 'info text-dark';
                    break;
                case 3:
                    $stat = "On the Way";
                    break;
                case 4:
                    $stat = "Waiting confirmation";
                    break;
                case 5:
                    $stat = "Recieved";
                    $clss = 'success text-light';
                    break;
            }

            if ($row['status'] < 0) {
                $clss = 'class="bg-danger"';
                $stat = "Canceled";
            }
            if ($dlvid == -1) {
                $clss = 'class="bg-warning text-dark"';
                $stat = "Shipment Failed";
            }



            // <th>Date</th>
            // <th>Transaction ID</th>
            // <th>Iten Name</th>
            // <th>Buyer</th>
            // <th>Type</th>
            // <th>Price</th>
            // <th>Quantity</th>
            // <th>Status</th>


            echo '<tr>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['dtedisp'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['itmc'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['totprice'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['dtedisp'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">Delivery</a></td>
                <td><a href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;"><span class="badge bg-' . $clss . '">' . $stat . '</span></a></td>
    
            </tr>';
        }
    }
}

function total_orders($link)
{
    $pending=0;
    $moving=0;
    $completed=0;
    $arr=array();
    $sql = "SELECT count(t_id) as 'pending' FROM transac where r_id = 0 and d_id = 0 and d_stat = 0 and status >= 0";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['pending']!=NULL){
        $pending= $row['pending'];
        }
    }

    $sql = "SELECT count(t_id) as 'moving' FROM transac where r_id > 0 and d_id > 0 and d_stat = 0 and status >= 0";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['moving']!=NULL){
            $moving= $row['moving'];
        }

    }

    $sql = "SELECT count(t_id) as 'completed' FROM transac where r_id > 0 and d_id > 0 and d_stat > 0 and status >= 0";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['completed']!=NULL){
        $completed= $row['completed'];
        }
    }
    array_push($arr,$pending);
    array_push($arr,$moving);
    array_push($arr,$completed);
    array_push($arr,get_categories($link));
    echo json_encode($arr);

}

function total_orders_today($link)
{
    
    $arr=array();
    $rem_stock_value=0;

    $sql="SELECT selling_price, quantity FROM inventory where quantity > 0";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
            if($row['selling_price'] != NULL){
                $rem_stock_value+=$row['selling_price']*$row['quantity'];

            }

        }

   

    }
    $totprice=0;
    $totqnt=0;
    $sql = "SELECT sum(`price`) as 'price', sum(`qnt`) as 'qnt' 
    FROM `transac` WHERE d_stat > 0 and status >=0;";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {



            $qnt=$row['qnt'];
            $price=$row['price'];
     
            if($row['qnt']==NULL){
                $qnt=0;
                $price=0;
            }
            $totprice+=$price;
            $totqnt+=$qnt;


        }
    }



    $pending=0;
    $moving=0;
    $completed_today=0;
    $pending_today=0;
    $moving_today=0;
    $sql = "SELECT count(t_id) as 'pending' FROM transac where r_id = 0 and d_id = 0 and d_stat = 0 and status >= 0";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['pending']!=NULL){
        $pending= $row['pending'];
        }
    }

    $sql = "SELECT count(t_id) as 'moving' FROM transac where r_id > 0 and d_id > 0 and d_stat = 0 and status >= 0";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['moving']!=NULL){
            $moving= $row['moving'];
        }

    }

    $sql = "SELECT count(t_id) as 'completed' FROM transac where r_id > 0 and d_id > 0 and d_stat > 0 and status >= 0";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['completed']!=NULL){
        $completed= $row['completed'];
        }
    }
    $date = date('Y-m-d');
    $start=$date.' 00:00:00';
    $end=$date.' 23:59:59';

    //today
    $sql = "SELECT count(t_id) as 'pending' FROM transac where r_id = 0 and d_id = 0 and d_stat = 0 and status >= 0 and dte between '$start' and '$end'";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['pending']!=NULL){
        $pending_today= $row['pending'];
        }
    }

    $sql = "SELECT count(t_id) as 'moving' FROM transac where r_id > 0 and d_id > 0 and d_stat = 0 and status >= 0 and dte between '$start' and '$end'";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['moving']!=NULL){
            $moving_today= $row['moving'];
        }

    }

    $sql = "SELECT count(t_id) as 'completed' FROM transac where r_id > 0 and d_id > 0 and d_stat > 0 and status >= 0 and dte between '$start' and '$end'";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['completed']!=NULL){
        $completed_today= $row['completed'];
        }
    }


    array_push($arr,$rem_stock_value);
    array_push($arr,$totprice);
    array_push($arr,get_top_selling_item($link));
    array_push($arr,$pending);
    array_push($arr,$moving);
    array_push($arr,$pending_today);
    array_push($arr,$moving_today);
    array_push($arr,$completed_today);
    array_push($arr,count_low_stocks($link));
    array_push($arr,get_categories($link));
    
    echo json_encode($arr);
}

function count_low_stocks($link){
    $sql="SELECT count(item_id) as 'count' FROM inventory where quantity <= restockqnt order by quantity DESC";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['count'];

    }

}


function orders_today($link, $type, $catg)
{

    $date = date('Y-m-d');
    $start=$date.' 00:00:00';
    $end=$date.' 23:59:59';
    $scatg="";
    if($catg!=0){
        $scatg="and item_id IN (SELECT item_id from inventory where category = '$catg')";
    }
    if ($type == 0) {
        $ord = "";
    } else if ($type == 1) {
        $ord = "and r_id > 0 and d_id = 0 and d_stat = 0 and status >= 0";
    } else if ($type == 2) {
        $ord = "and r_id > 0 and d_id > 0 and d_stat = 0 and status >= 0";
    } else if ($type == 3) {
        $ord = "and r_id > 0 and d_id > 0 and d_stat > 0 and status >= 0";
    } else if ($type == 4) {
        $ord = "and status < 0";
    } else if ($type == 6) {
        $ord = "and r_id = 0 and d_id = 0 and d_stat = 0 and status >= 0";
    }
    $sql = "SELECT * FROM transac where dte between '$start' and '$end' $ord $scatg order by dte DESC";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {


            if ($row['status'] >= 0) {
                $class = "badge bg-light text-dark";
                $stat = "Pending";
                if ($row['r_id'] > 0 && $row['d_id'] == 0 && $row['d_stat'] == 0) {
                    $stat = "To Ship";
                    $class = "badge bg-info text-dark";
                } else if ($row['r_id'] > 0 && $row['d_id'] > 0 && $row['d_stat'] == 0) {
                    $stat = "On the Way";
                    $class = "badge bg-primary text-light";
                } else if ($row['r_id'] > 0 && $row['d_id'] > 0 && $row['d_stat'] > 0) {
                    $stat = "Completed";
                    $class = "badge bg-success text-light";
                }
            } else {
                $stat = "Canceled";
                $class = "badge bg-warning text-dark";
            }
            if ($row['payment'] == 0) {
                $payment = "Cash on delivery";
            }

            $item_id = $row['item_id'];

            echo '<tr>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['dtedisp'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['t_id'] . '</a></td>
                <td><a class="' . $class . '" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $stat . '</a></td>
                <td><a class="text-primary" href="user.html?id=' . $row['user_id'] . '" style="text-decoration: none;">' . $row['fname'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . get_items($link, $item_id) . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['qnt'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['price'] . '</a></td>    
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['email'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['street'] . ', ' . $row['city'] . ', ' . $row['prov'] . '</a></td>
                <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $payment . '</a></td>
            </tr>';
        }
    }
}


function get_top_selling_item($link){
    $sql="Select item_id, count(*) as 'count' from transac where item_id in (SELECT item_id from transac) and d_stat > 0
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


function get_categories($link){

    function get_cetg_count($link){
        $sql="SELECT count(c_id) as 'count' FROM categories";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            $row = mysqli_fetch_array($result);
            return $row['count'];
        }else{
            return 0;
        }
    }
    $count=0;
        $str2='';
            $str1='';
            $sql="SELECT * FROM categories";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                while($row = mysqli_fetch_array($result)){
                    $count+=1;
                    if($count==get_cetg_count($link)){
                        $str2.= $row['name'] ;
                    }else{
                        $str2.= $row['name'] .',';
                    }
    
                };
               
            }
        
            // round($tax, 2)
            $str3='';
            $str=$str1.$str2.$str3;
            return $str;
    
    
}
