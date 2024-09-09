<?php

function get_item_name($link, $item_id)
{
    $sql = "SELECT name,v_id,v_name FROM inventory where item_id=$item_id";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $v_id = $row['v_id'];
        $name = $row['name'];
        if ($v_id > 0) {

            $name = $row['name'] . ' - ' . $row['v_name'];
        }



        return $name;
    }
}

function get_item_catg($link, $item_id)
{
    $sql = "SELECT category FROM inventory where item_id=$item_id";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        return $row['category'];
    }
}

function get_item_price($link, $item_id)
{
    $sql = "SELECT selling_price FROM inventory where item_id=$item_id";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        return $row['selling_price'];
    }
}
function get_order_sales($link, $start, $end, $catg)
{

    $totprice=0;
    $totqnt=0;


    $str="";
    $start .= " 00:00:00";
    $end .= " 23:59:59";
    $scatg="";
    if($catg!=0){
            $scatg="and item_id IN (SELECT item_id from inventory where category = '$catg')";
  
    }


    $sql = "SELECT * FROM transac where r_id > 0 and d_id > 0 and d_stat > 0 and status >= 0 and dte between '$start' and '$end' $scatg order by dte DESC";

    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {


                    
                    $item_id = $row['item_id'];
                    $totprice+=$row['price'];
                    $totqnt+=$row['qnt'];


            if ($row['payment'] == 0) {
                $payment = "Cash on delivery";
            }
            $str.= '<tr>
            <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['dtedisp'] . '</a></td>
            <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['t_id'] . '</a></td>
            <td><a class="text-primary" href="user.html?id=' . $row['user_id'] . '" style="text-decoration: none;">' . $row['fname'] . '</a></td>
            <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . get_item_name($link, $item_id) . '</a></td>
            <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['qnt'] . '</a></td>
            <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $row['price'] . '</a></td>    
            <td><a class="text-dark" href="mnorders.html?id=' . $row['t_id'] . '" style="text-decoration: none;">' . $payment . '</a></td>
        </tr>';
        }
    }


    $arr=array();
    array_push($arr,$str);
    array_push($arr,$totprice); 
    array_push($arr,$totqnt); 
    array_push($arr,get_top_selling_item($link,$start,$end));
    
    echo json_encode($arr);



}



function get_catg_sales($link, $start, $end)
{

    $totprice=0;
    $totqnt=0;


    $str="";
    $start .= " 00:00:00";
    $end .= " 23:59:59";
    $sql = "SELECT * from categories";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {

            $cprice=0;
            $qnt=0;
            $sql2 = "SELECT * from inventory where category = '$row[name]'";
            $result2 = mysqli_query($link, $sql2);
            if ($result2 && mysqli_num_rows($result2) > 0) {
                while ($row2 = mysqli_fetch_array($result2)) {
                    
                    $item_id = $row2['item_id'];
                    $totprice+=get_dte_price($link, $item_id, $start, $end);
                    $totqnt+=get_dte_qnt($link, $item_id, $start, $end);

                    $cprice+=get_dte_price($link, $item_id, $start, $end);
                    $qnt+=get_dte_qnt($link, $item_id, $start, $end);


                }
            }        

            $str.= '<tr>
                <td><a class="text-dark" style="text-decoration: none;">' . $row['name'] . '</a></td>
                <td><a class="text-dark" style="text-decoration: none;">₱' . $cprice . '</a></td>
                <td><a class="text-dark" style="text-decoration: none;">' . $qnt . '</a></td>    
            </tr>';
        }
    }


    $arr=array();
    array_push($arr,$str);
    array_push($arr,$totprice); 
    array_push($arr,$totqnt); 
    array_push($arr,get_top_selling_item($link,$start,$end));
    
    echo json_encode($arr);



}

function get_item_tax($link, $item_id)
{
    $sql = "SELECT tax FROM inventory where item_id=$item_id";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        return $row['tax'];
    }
}
function get_sales($link, $start, $end)
{

    $totprice=0;
    $totqnt=0;


    $str="";
    $start .= " 00:00:00";
    $end .= " 23:59:59";
    $sql = "SELECT * from inventory";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {

            $item_id = $row['item_id'];
            $totprice+=get_dte_price($link, $item_id, $start, $end);
            $totqnt+=get_dte_qnt($link, $item_id, $start, $end);



            $str.= '<tr>
                <td><a class="text-dark" style="text-decoration: none;">' . $row['item_id'] . '</a></td>
                <td><a class="text-dark" style="text-decoration: none;">' . get_item_name($link, $item_id) . '</a></td>
                <td><a class="text-dark" style="text-decoration: none;">₱' . get_dte_price($link, $item_id, $start, $end) . '</a></td>
                <td><a class="text-dark" style="text-decoration: none;">' . get_dte_qnt($link, $item_id, $start, $end) . '</a></td>    
            </tr>';
        }
    }


    $arr=array();
    array_push($arr,$str);
    array_push($arr,$totprice); 
    array_push($arr,$totqnt); 
    array_push($arr,get_top_selling_item($link,$start,$end));
    
    echo json_encode($arr);



}
function get_sales_by_dte($link, $start, $end, $sort)
{
    $type = $sort;

    $start_date = date_create($start);
    $end_date   = date_create($end);

    $interval = DateInterval::createFromDateString('1 ' . $type);
    $daterange = new DatePeriod($start_date, $interval, $end_date);

    $start_arr = array();
    $end_arr = array();

    foreach ($daterange as $date1) {
        array_push($start_arr, $date1->format('Y-m-d') . ' 00:00:00');
        array_push($end_arr, date('Y-m-d', strtotime('-1 day', strtotime($date1->format('Y-m-d')))) . ' 23:59:59');
    }
    array_shift($end_arr);
    if ($type == "day") {
        array_push($end_arr, date('Y-m-d', strtotime('-1 day', strtotime($end))) . ' 23:59:59');
        array_push($start_arr, $end . ' 00:00:00');
        array_push($end_arr, $end . ' 23:59:59');
    } else {
        array_push($end_arr, $end . ' 23:59:59');
    }
$prce=array();
$quant=array();
$totprice=0;
$totqnt=0;
$str="";
    for ($x = 0; $x < count($start_arr); $x++) {
        $sql = "SELECT sum(`price`) as 'price', sum(`qnt`) as 'qnt' 
        FROM `transac` WHERE `dte` BETWEEN '$start_arr[$x]' AND '$end_arr[$x]' and d_stat > 0 and status >=0;";
        $result = mysqli_query($link, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $start = new DateTime($start_arr[$x]);
                $end = new DateTime($end_arr[$x]);


                $qnt=$row['qnt'];
                $price=$row['price'];
         
                if($row['qnt']==NULL){
                    $qnt=0;
                    $price=0;
                }
                $totprice+=$price;
                $totqnt+=$qnt;
                array_push($prce,$price);
                array_push($quant,date_format($start,"D, M n, Y"));
                $str.= '<tr>
              <td><a class="text-dark" style="text-decoration: none;">' . date_format($start,"D, M n, Y") . ' - ' . date_format($end,"D, M n, Y") . '</a></td>
              <td><a class="text-dark" style="text-decoration: none;">₱' . $price . '</a></td>    
              <td><a class="text-dark" style="text-decoration: none;">' . $qnt . '</a></td>
          </tr>';
            }
        }
    }

$arr=array();
$prce=implode("|",$prce);
$quant=implode("|",$quant);
array_push($arr,$str);
array_push($arr,$prce);
array_push($arr,$quant);
array_push($arr,$totprice); 
array_push($arr,$totqnt); 
echo json_encode($arr);

}
function get_dte_price($link, $item_id, $start, $end)
{
    $sql = "SELECT sum(`price`) as 'price' FROM `transac` WHERE `dte` 
    BETWEEN '$start' AND '$end' AND `item_id`= $item_id AND d_stat = 1;";


    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if ($row['price'] == NULL) {
            return 0;
        }
        return $row['price'];
    }
}

function get_dte_qnt($link, $item_id, $start, $end)
{
    $sql = "SELECT sum(`qnt`) as 'qnt' FROM `transac` WHERE `dte` 
    BETWEEN '$start' AND '$end' AND `item_id`= $item_id AND d_stat = 1;";


    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if ($row['qnt'] == NULL) {
            return 0;
        }
        return $row['qnt'];
    }
}



function get_sales0($link)
{

    $sql = "SELECT `item_id`, count(item_id) as 'count',sum(`price`) as 'price',`user_id` from `transac` where `item_id` in (SELECT item_id from transac) group by `item_id`";
    $result = mysqli_query($link, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {

            $item_id = $row['item_id'];
            echo '<tr>
            <td><a class="text-dark" style="text-decoration: none;">' . get_item_name($link, $item_id) . '</a></td>
            <td><a class="text-dark" style="text-decoration: none;">' . get_item_catg($link, $item_id) . '</a></td>
            <td><a class="text-dark" style="text-decoration: none;">' . $row['count'] . '</a></td>
            <td><a class="text-dark" style="text-decoration: none;">₱' . $row['price'] . '</a></td>    
            </tr>';
        }
    }
}
$sql = "SELECT `item_id`, count(*) as 'count',sum(`price`) as 'price',`user_id` from `transac` where `item_id` in (SELECT item_id from transac) group by `item_id`";

$sql = "SELECT `item_id`, count(`item_id`) as 'count' from `transac` where `item_id` in (SELECT `item_id` from `transac`) and `dte` >= '2022-05-19 00:00:00' group by `item_id`;";
$sql = "SELECT sum(`price`) FROM `transac` WHERE `dte` like '%2022-05-19%'";

function get_top_selling_item($link,$start,$end){
    $sql="Select item_id, count(*) as 'count' from transac where item_id in (SELECT item_id from transac) and d_stat = 1
    and `dte` BETWEEN '$start' AND '$end'
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

