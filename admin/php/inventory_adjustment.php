<?php
//discrepancy
function get_table_content0($link,$items){

    $ids= explode(" ",$items);


for($x=0;$x<count($ids);$x++){
$intv=intval($ids[$x]);

$sql="SELECT * FROM inventory where item_id = $intv";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){

            $style="text-decoration: none;color:black;";
            $onclick="";
            echo '<tr>
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['item_id'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['name'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['quantity'].'</a></td>
                <td><input type="text" id="'.$row['item_id'].'inp"></input></td>
            </tr>';
    
    
        }

    }

}


}

function get_table_content1($link,$items){

    $ids= explode(" ",$items);


for($x=0;$x<count($ids);$x++){
$intv=intval($ids[$x]);

$sql="SELECT * FROM inventory where item_id = $intv";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){

            $style="text-decoration: none;color:black;";
            $onclick="";
            echo '<tr>
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['item_id'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['name'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['quantity'].'</a></td>
                <td><input type="text" id="'.$row['item_id'].'inp"></input></td>
            </tr>';
    
    
        }

    }

}


}

function get_table_content2($link,$items){

    $ids= explode(" ",$items);


for($x=0;$x<count($ids);$x++){
$intv=intval($ids[$x]);

$sql="SELECT * FROM inventory where item_id = $intv";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
            $style="text-decoration: none;color:black;";
            $onclick="";
            echo '<tr>
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['item_id'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">'.$row['name'].'</a></td> 
                <td><a class="text-dark" style="'.$style.'" onclick="'.$onclick.'">₱'.$row['selling_price'].'</a></td>
                <td><input type="text" id="'.$row['item_id'].'inp"></input></td>
            </tr>';
        }

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


function purchase_stock($link,$items,$qnt){
    $ids= explode("+",$items);
    $qntarr= json_decode($qnt);
    $str='';
for($x=0;$x<count($ids);$x++){
$intv=intval($ids[$x]);
$qnts=intval($qntarr[$x])+count_stocks($link,$intv);
    $sql="UPDATE inventory SET quantity=$qnts WHERE item_id=$intv"; 
     if ($link->query($sql) === TRUE) {

        $str.=save_stock($link,$intv,$qnts);
     }else{

        $str.="Error : " . $link->error;
     }
}
echo $str;

}

function count_stocks($link,$item_id){
    $sql="SELECT quantity FROM inventory where item_id = $item_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['quantity'];

    }

}


function inv_adjustment_log($link,$item_id,$tpe,$reason,$comment){
    $a_id=mt_rand(1000000,9999999);   
    $sql="SELECT a_id FROM inventory_adjustments where a_id=$a_id"; 
    $result=mysqli_query($link, $sql);     
    if($result && mysqli_num_rows($result) > 0){      
        $user_data=mysqli_fetch_assoc($result);      
        while($a_id ==  $user_data['a_id']){    
            $a_id=mt_rand(1000000,9999999);     
        }
    }
  
  $dte=date('Y-m-d').' '.date('H:i:s');
  $dtedisp=date('m/d/Y').' at '.date('g:iA');
  $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');
  
    $sql="INSERT INTO inventory_adjustments values ($a_id,$item_id,$tpe,$reason,$comment,
    '$dte','$dtedisp',$dtenum)"; 
    if ($link->query($sql) === TRUE){
      return 1;
    }else{
      return "Error inv_adj: " . $link->error;
    }
}

function save_stock($link,$item_id,$stock){
    $stock_id=mt_rand(1000000,9999999);   
    $sql="SELECT s_id FROM stock_history where s_id=$stock_id"; 
    $result=mysqli_query($link, $sql);     
    if($result && mysqli_num_rows($result) > 0){      
        $user_data=mysqli_fetch_assoc($result);      
        while($stock_id ==  $user_data['s_id']){    
            $stock_id=mt_rand(1000000,9999999);     
        }
    }
  
  $dte=date('Y-m-d').' '.date('H:i:s');
  $dtedisp=date('m/d/Y').' at '.date('g:iA');
  $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');
  
    $sql="INSERT INTO stock_history values ($stock_id,$item_id,$stock,
    '$dte','$dtedisp',$dtenum,1)"; 
    if ($link->query($sql) === TRUE){
      return 1;
    }else{
      return "Error : " . $link->error;
    }
}



