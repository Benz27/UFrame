<?php

function count_product($link,$start,$end,$stat){
    
    $end.=' 23:59:59';
    $start.=' 00:00:00';
    
    $sql="SELECT count(item_id) as 'count' FROM inventory where dte >= '$start' and dte <= '$end'";
    if($result = mysqli_query($link, $sql)){ 
        $row = mysqli_fetch_array($result);
        return $row['count'];
    }
}
function get_dates($link){
    //oldest
    $old="";
    $sql="SELECT dte,dtedisp FROM transac order by dte LIMIT 1";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $old = $row['dte'];
    }else{
        $old=date('Y-m-d').' 00:00:00';
    }

    $new="";
    $sql="SELECT dte,dtedisp FROM transac order by dte DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $new = $row['dte'];
    }else{
        $new=date('Y-m-d').' 23:59:59';
    }
    return $old.','.$new;
}

function get_lst_by_date($link,$start,$end,$stat){
    // $sdte=$start.' 00:00:00';
    // $edte=$end.' 99:99:99';


    $end.=' 23:59:59';
    $start.=' 00:00:00';

    $sql="SELECT * FROM inventory where dte >= '$start' and dte <= '$end'";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
           


            // <th>ID</th>
            // <th>Name</th>
            // <th>Category</th>
            // <th>Quantity</th>
            // <th>Re-Stock Level</th>
            // <th>Tax</th>
            // <th>Selling Price</th>
            // <th>Date Added</th>
            if($row['tax']!=0){
                $tax=($row['tax']/$row['original_price'])/.01;
            }else{
                $tax=0;
            }
            

            $style="text-decoration: none;cursor:pointer;";
            $onclick="set_inputs(".$row['item_id'].");";
            echo '<tr>
                <td><input type="checkbox" onclick="chck('.$row['item_id'].')" id="'.$row['item_id'].'"></input></td> 
                <td><a class="text-dark" style="'.$style.'" href="product.html?item='.$row['item_id'].'">'.$row['item_id'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" href="product.html?item='.$row['item_id'].'">'.$row['name'].'</a></td>
                <td><a class="text-dark" style="'.$style.'" href="product.html?item='.$row['item_id'].'">'.$row['category'].'</a></td>
                <td><a class="text-dark" style="'.$style.'" href="product.html?item='.$row['item_id'].'">'.$row['location'].'</a></td>
                <td><a class="text-dark" style="'.$style.'" href="product.html?item='.$row['item_id'].'">'.$row['grp'].'</a></td>
                <td><a class="text-dark" style="'.$style.'" href="product.html?item='.$row['item_id'].'">'.$row['quantity'].'</a></td>
                <td><a class="text-dark" style="'.$style.'" href="product.html?item='.$row['item_id'].'">₱'.$row['selling_price'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" href="product.html?item='.$row['item_id'].'">'.$row['dtedisp'].'</a></td>
            </tr>';
    
    
        }
    
    }
}

function set_inputs($link,$item_id){
    $str1='{';
    $sql="SELECT * FROM inventory where item_id=$item_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['tax']!=0){
            $tax=($row['tax']/$row['original_price'])/.01;
        }else{
            $tax=0;
        }
        $descr=preg_replace( "/\r\n/", "931linebr931", $row['description']);
        $ad_details=preg_replace( "/\r\n/", "931linebr931", $row['ad_details']);
        $str2='
        "item_id": '. $row['item_id'] .',
        "product_name": "'. $row['name'] .'",
        "category": "'. $row['category'] .'",
        "location": "'. $row['location'] .'",
        "group": "'. $row['grp'] .'",
        "stock": '. $row['quantity'] .',
        "restock": '. $row['restockqnt'] .',
        "tax": '. $row['tax'] .',
        "tax_percentage": "'. round($tax, 2) .'%",
        "selling_price": '. $row['selling_price'] .',
        "supplier": "'. $row['supplier'] .'",
        "description": "'. $descr .'",
        "ad_details": "'. $ad_details .'",
        "note": "'. $row['note'] .'",
        "status": "'. $row['status'] .'",
        "weight": "'. $row['weight'] .'",
        "weight_unit": "'. $row['weight_unit'] .'",
        "height": "'. $row['height'] .'",
        "width": "'. $row['width'] .'",
        "length": "'. $row['length'] .'",
        "length_unit": "'. $row['length_unit'] .'",
        "upd_date": "'. $row['upddtedisp'] .'"
        ';
    }

    // round($tax, 2)
    $str3='}';
    $str=$str1.$str2.$str3;
    return $str;


}

function count_inventory($link){
    $sql="SELECT sum(quantity) as 'count' FROM inventory";
    if($result = mysqli_query($link, $sql)){ 
        $row = mysqli_fetch_array($result);
        return $row['count'];
    }
}



function del_product($link, $item_id){
$sql="delete from inventory where item_id=$item_id";
if ($link->query($sql) === TRUE) {
    echo count_inventory($link);
}else{
    echo $link->error;
}
}

function get_investment($link){
    $sql="SELECT sum(selling_price) as 'selling_price',sum(tax) as 'tax' FROM inventory";
    if($result = mysqli_query($link, $sql)){ 
        $row = mysqli_fetch_array($result);
        if($row['selling_price']!=NULL){

            return $row['selling_price'];
        }else{
            return 0;
        }

    }
}
function get_original_price($link){
    $sql="SELECT sum(original_price) as 'original_price' FROM inventory";
    if($result = mysqli_query($link, $sql)){ 
        $row = mysqli_fetch_array($result);
        if($row['original_price']!=NULL){

            return $row['original_price'];
        }else{
            return 0;
        }

    }
}

function get_tax($link){
    $sql="SELECT sum(tax) as 'tax' FROM inventory";
    if($result = mysqli_query($link, $sql)){ 
        $row = mysqli_fetch_array($result);
        if($row['tax']!=NULL){

            return $row['tax'];
        }else{
            return 0;
        }

    }
}




function get_profit($link){
    $sql="SELECT sum(charge) as 'charge',sum(tax) as 'tax' FROM inventory";
    if($result = mysqli_query($link, $sql)){ 
        $row = mysqli_fetch_array($result);
        if($row['charge']!=NULL){
            return $row['charge']-$row['tax'];
        }else{
            return 0;
        }
    }
}
//variant products


function v_set_inputs($link,$item_id){
    $str1='{';
    $sql="SELECT * FROM inventory where v_id=$item_id limit 1";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['tax']!=0){
            $tax=($row['tax']/$row['original_price'])/.01;
        }else{
            $tax=0;
        }
        $descr=preg_replace( "/\r\n/", "931linebr931", $row['description']);
        $ad_details=preg_replace( "/\r\n/", "931linebr931", $row['ad_details']);
        $str2='
        "item_id": '. $row['item_id'] .',
        "product_name": "'. $row['name'] .'",
        "category": "'. $row['category'] .'",
        "location": "'. $row['location'] .'",
        "group": "'. $row['grp'] .'",
        "tax": '. $row['tax'] .',
        "tax_percentage": "'. round($tax, 2) .'%",
        "supplier": "'. $row['supplier'] .'",
        "description": "'. $descr .'",
        "ad_details": "'. $ad_details .'",
        "note": "'. $row['note'] .'",
        "status": "'. $row['status'] .'",
        "weight": "'. $row['weight'] .'",
        "weight_unit": "'. $row['weight_unit'] .'",
        "height": "'. $row['height'] .'",
        "width": "'. $row['width'] .'",
        "length": "'. $row['length'] .'",
        "length_unit": "'. $row['length_unit'] .'",
        "upd_date": "'. $row['upddtedisp'] .'"
        ';
    }

    // round($tax, 2)
    $str3='}';
    $str=$str1.$str2.$str3;
    return $str;


}


function v_del_product($link, $item_id){
    $sql="delete from inventory where v_id=$item_id";
    if ($link->query($sql) === TRUE) {
        echo count_inventory($link);
    }else{
        echo $link->error;
    }
    }
    

