<?php
function get_stocks($link,$id){
if($id==1){
    $sql="SELECT * FROM inventory where status = 1";
}
else if($id==2){
    $sql="SELECT * FROM inventory where quantity <= restockqnt and status = 1";
}
else{
    $sql="SELECT * FROM inventory where quantity = 0";
}

//functions
function get_last_restock($link,$item_id){
    $sql="SELECT * FROM stock_history where item_id = $item_id order by dte DESC LIMIT 1";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['type']==0){
            return "Have not re-stocked";
        }
        return $row['dtedisp'];
    }
    return "No data";
}
//

    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
           
            // <th>Item ID</th>
            // <th>Name</th>
            // <th>Stock Level</th>
            // <th>Last Re-Stock</th>
            $item_id=$row['item_id'];
            
            $var=$row['v_name'];
            $ext="";
            $href="product.html?item=".$row['item_id'];
            if($var!=""){
                $ext=' - <span class="text-secondary">'.$var.'</span>';
                $href="vproduct.html?item=".$row['v_id'];
            }
            $class="text-dark";
            if($row['quantity']<=$row['restockqnt']){
                $class="text-warning";
            }
            if($row['quantity']==0){
                $class="text-danger";
            }
            $style="text-decoration: none;cursor:pointer;";
            $onclick="set_stock_inputs(".$row['item_id'].");";
            // <td><input type="checkbox" onclick="chck('.$row['item_id'].')" id="'.$row['item_id'].'"></input></td> 
            // <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.get_last_restock($link,$item_id).'</a></td>
//          
            // $shop="Not on shop";

            // $shophref='';
            // if($row['shop_id']>0){
            //     $shop="On shop";

            //     $shophref='href="item.html?item='.$row['item_id'].'"';
            // }
            echo '<tr>
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['item_id'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['name'].$ext.'</a></td>
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['category'].'</a></td>
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">₱'.$row['selling_price'].'</a></td>
                <td><a class="'.$class.'" style="'.$style.'font-weight:bolder;" onclick="'.$onclick.'">'.$row['quantity'].'</a></td>
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['restockqnt'].'</a></td>
                <td><a href="'.$href.'"><i class="fa fa-edit"></i></a></td>
            </tr>';
    
    
        }

    }




}

function count_low_stocks($link){
    $sql="SELECT count(item_id) as 'count' FROM inventory where quantity <= restockqnt order by quantity DESC";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['count'];

    }

}

function set_stock_inputs($link,$item_id){


    function get_latest_restock($link,$item_id){
        $sql="SELECT * FROM stock_history where item_id = $item_id order by dte DESC LIMIT 1";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            $row = mysqli_fetch_array($result);
            if($row['type']==0){
                return 0;
            }
            return $row['dtedisp'];
        }
        return 0;
    }
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
        $str2='
        "item_id": '. $row['item_id'] .',
        "product_name": "'. $row['name'] .'",
        "category": "'. $row['category'] .'",
        "location": "'. $row['location'] .'",
        "group": "'. $row['grp'] .'",
        "stock": '. $row['quantity'] .',
        "restock": '. $row['restockqnt'] .',
        "tax": '. $row['tax'] .',
        "selling_price": '. $row['selling_price'] .',
        "supplier": "'. $row['supplier'] .'",
        "description": "'. $row['description'] .'",
        "note": "'. $row['note'] .'",
        "status": "'. $row['status'] .'",
        "latest_restock": "'. get_latest_restock($link,$item_id) .'",
        "upd_date": "'. $row['upddtedisp'] .'"
        ';
    }

    // round($tax, 2)
    $str3='}';
    $str=$str1.$str2.$str3;
    return $str;


}


function upd_restockqnt($link,$item_id,$qnt){

    $sql="UPDATE inventory SET restockqnt=$qnt WHERE item_id=$item_id"; 
     if ($link->query($sql) === TRUE) {

       return 1;
     }else{

       return "Error : " . $link->error;
     }



}



function save_stock($link,$item_id,$stock){

    $dte=date('Y-m-d').' '.date('H:i:s');
    $dtedisp=date('m/d/Y').' at '.date('g:iA');
    $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');

    $sql="SELECT quantity FROM inventory where item_id = $item_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $oldqnt=$row['quantity'];
        $newqnt=$stock;
        $qntdiff=$newqnt-$oldqnt;


    }

    if($oldqnt!=$newqnt){
        $stock_id=mt_rand(1000000,9999999);   
        $sql="SELECT s_id FROM stock_history where s_id=$stock_id"; 
        $result=mysqli_query($link, $sql);     
        if($result && mysqli_num_rows($result) > 0){      
            $user_data=mysqli_fetch_assoc($result);      
            while($stock_id ==  $user_data['s_id']){    
                $stock_id=mt_rand(1000000,9999999);     
            }
        }
      
      
      
        $sql="INSERT INTO stock_history values ($stock_id,$item_id,$stock,$oldqnt,$qntdiff,
        '$dte','$dtedisp',$dtenum,1)"; 
        if ($link->query($sql) === TRUE){
          return 1;
        }else{
          return "Error : " . $link->error;
        }
    }
    return 0;

} 


function upd_restock($link,$item_id,$qnt){
    save_stock($link,$item_id,$qnt);
    $sql="UPDATE inventory SET quantity=$qnt WHERE item_id=$item_id"; 
     if ($link->query($sql) === TRUE) {
        return 1;
     }else{

       return "Error : " . $link->error;
     }



}

function stock_adjustments($link){
   
        $sql="SELECT * FROM stock_history where type = 1";
   
   
    
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_array($result)){
               

                $style="text-decoration: none;cursor:pointer;";
                $onclick="set_stock_inputs(".$row['item_id'].");";
                $class="text-danger";
                    if($row['qntdiff']>0){
                        $class="text-success";
                    }
                echo '<tr>
                    <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['item_id'].'</a></td> 
                    <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.get_item_name($link,$row['item_id']).'</a></td>
                    <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['dtedisp'].'</a></td>
                    <td><a class="'.$class.'" style="'.$style.'" onclick="'.$onclick.'">'.$row['qntdiff'].'</a></td>
                </tr>';
        
        
            }
    
        }
    
    
    
    
    }
    function get_item_name($link,$id){
        
                $sql="SELECT name FROM inventory where item_id=$id";
                $result = mysqli_query($link, $sql);
                $row = mysqli_fetch_array($result);
                return $row['name'];
        
    }
    



    function get_card_details($link){

        $arr=array();
        $rem_stock_value=0;
        $rem_stock=0;
        $sql="SELECT selling_price, quantity FROM inventory where quantity > 0";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_array($result)){
                if($row['selling_price'] != NULL){
                    $rem_stock_value+=$row['selling_price']*$row['quantity'];
                    $rem_stock+=$row['quantity'];
                }

            }

       

        }

        array_push($arr,$rem_stock_value);
        array_push($arr,$rem_stock);
        echo json_encode($arr);


    }