<?php


function table_content0($link,$app_id){

$sql="SELECT * FROM transac WHERE t_id IN(SELECT t_id from delivery_request) and d_stat=0  and status >= 0";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
            $item_id=$row['item_id'];
            $r_id=$row['r_id'];
            $status="";

            if(chk_delv($link,$r_id)){
                $status="On the Way";
                $unassign='';
            }else{
                $status="Preparing";
                $unassign='<a class="text-danger" onclick="unassign('.$row['t_id'].','.$row['r_id'].');" style="text-decoration: none;cursor:pointer;">Un-assign</a>';
            }


            echo '<tr>
            <td><a class="text-dark" style="text-decoration: none;">'.$row['t_id'].'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.$row['fname'].'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.get_items($link, $item_id).'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.$row['qnt'].'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.get_weight($link, $item_id).'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">₱'.get_rate($link,$item_id, $app_id).'</a></td>   
            <td><a class="text-dark" style="text-decoration: none;">₱'.$row['price'].'</a></td>    
            <td><a class="text-dark" style="text-decoration: none;">'.$status.'</a></td>    
            <td><a class="text-dark" style="text-decoration: none;">'.$row['street'].', '.$row['city'].', '.$row['prov'].'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.$row['dtedisp'].'</a></td>
            <td>'.$unassign.'</td>

            </tr>';
    
    
        }

    }




}



function chk_delv($link,$r_id){
    $sql="SELECT d_id FROM delivery where d_id=(SELECT d_id from delivery_request where r_id=$r_id)";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 

        return true;    
        
    }
    return false;
}








function table_content1($link,$app_id){
   


$sql="SELECT * FROM transac WHERE t_id NOT IN(SELECT t_id from delivery_request) and status >= 0";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
            $item_id=$row['item_id'];

    
            echo '<tr>
            <td><a class="text-dark" style="text-decoration: none;">'.$row['t_id'].'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.$row['fname'].'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.get_items($link, $item_id).'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.$row['qnt'].'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.get_weight($link, $item_id).'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">₱'.get_rate($link,$item_id, $app_id).'</a></td>   
            <td><a class="text-dark" style="text-decoration: none;">₱'.$row['price'].'</a></td>    
            <td><a class="text-dark" style="text-decoration: none;">'.$row['street'].', '.$row['city'].', '.$row['prov'].'</a></td>
            <td><a class="text-dark" style="text-decoration: none;">'.$row['dtedisp'].'</a></td>
            <td><input type="checkbox" onclick="chck('.$row['t_id'].')" id="'.$row['t_id'].'"></input></td> 
            </tr>';
    
    
        }

    }






}

function get_rate($link,$item_id, $app_id){

    $sql="SELECT rate_per_kg FROM delivery_apps where app_id=$app_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $rate=$row['rate_per_kg'];

        $sql="SELECT weight,weight_unit FROM inventory where item_id=$item_id";
        $result = mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){ 
            $row = mysqli_fetch_array($result);
            if($row['weight_unit']=="oz"){
                $rte_unit=0.0283495;
            }else if($row['weight_unit']=="lb"){
                $rte_unit=0.453592;
            }else if($row['weight_unit']=="mg"){
                $rte_unit=0.000001;
            }else if($row['weight_unit']=="g"){
                $rte_unit=0.001;
            }else if($row['weight_unit']=="kg"){
                $rte_unit=1;
            }
            $total= ($rate*$rte_unit)*$row['weight'];
           return number_format((float)$total, 2, '.', '');
        }
    }
}



function get_weight($link, $item_id){
    $sql="SELECT weight,weight_unit FROM inventory where item_id=$item_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['weight'].$row['weight_unit'];
    }
}

function get_item_id($link, $t_id){
    $sql="SELECT item_id FROM transac where t_id=$t_id"; 
    $result=mysqli_query($link, $sql);     
    if($result && mysqli_num_rows($result) > 0){      
        $row=mysqli_fetch_assoc($result);      
        return "$row[item_id]";
  
    }

}

function get_items($link, $item_id){
    $sql="SELECT name,v_id,v_name FROM inventory where item_id=$item_id";
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
function get_name($link, $t_id){
    $sql="SELECT item_id FROM transac where t_id=$t_id"; 
    $result=mysqli_query($link, $sql);     
    if($result && mysqli_num_rows($result) > 0){      
        $row=mysqli_fetch_assoc($result);      
        $sql="SELECT name,v_id,v_name FROM inventory where item_id=$row[item_id]"; 
        $result=mysqli_query($link, $sql);     
        if($result && mysqli_num_rows($result) > 0){      
            $row=mysqli_fetch_assoc($result);


            $v_id=$row['v_id'];
            $name=$row['name'];
            if($v_id>0){
    
              $name=$row['name'].' - '.$row['v_name'];
            }
    
    
    
            return $name;
        }
  
    }

}

function get_price($link, $t_id){
    $sql="SELECT price FROM transac where t_id=$t_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['price'];
        
    }

}
function get_qnt($link, $t_id){
    $sql="SELECT qnt FROM transac where t_id=$t_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['qnt'];
    }
}
function assign($link,$t_ids,$app_id){

    $t_id_arr= explode(" ",$t_ids);

    $err=1;
for($x=0;$x<count($t_id_arr);$x++){
$t_id=intval($t_id_arr[$x]);


$r_id=mt_rand(1000000,9999999);   
  $sql="SELECT r_id FROM delivery_request where r_id=$r_id"; 
  $result=mysqli_query($link, $sql);     
  if($result && mysqli_num_rows($result) > 0){      
      $user_data=mysqli_fetch_assoc($result);      
      while($r_id ==  $user_data['r_id']){    
          $r_id=mt_rand(1000000,9999999);     
      }

  }

$fee=get_rate($link,get_item_id($link, $t_id), $app_id);

  $dte=date('Y-m-d').' '.date('H:i:s');
  $dtedisp=date('m/d/Y').' at '.date('g:iA');
  $dtenum=(date('Y')*10000)+(date('n')*100)+date('j');
$name=get_name($link, $t_id);
$qnt=get_qnt($link, $t_id);
$price=get_price($link, $t_id);
$units=get_measurements($link,$t_id);
$sql="INSERT INTO delivery_request values ($r_id,$app_id,0,
$t_id,'$name',$price,$qnt,$fee,'',$units,
0,'$dte','$dtedisp',$dtenum)"; 
if ($link->query($sql) === TRUE) {
    $sql="UPDATE transac SET r_id = $r_id WHERE t_id=$t_id"; 
    if ($link->query($sql) === TRUE) {
        $err.=1;
    }else{
        $err.= "Error upd($t_id): " . $link->error.", ";
    }
}else{
    $err.= "Error ($t_id): " . $link->error.", $units";
}

}

echo $err;
}

function unassign($link,$t_id,$r_id){
    $sql="DELETE FROM delivery_request WHERE r_id=$r_id"; 
    if ($link->query($sql) === TRUE) {
        $sql="UPDATE transac SET r_id = 0 WHERE t_id=$t_id"; 
        if ($link->query($sql) === TRUE) {
            echo 1;
        }else{
            echo "Error: " . $link->error.", ";
        }
    }else{
        echo "Error: " . $link->error.", ";
    }
}

function get_measurements($link,$t_id){
    $sql="SELECT item_id FROM transac where t_id=$t_id"; 
    $result=mysqli_query($link, $sql);     
    if($result && mysqli_num_rows($result) > 0){      
        $row=mysqli_fetch_assoc($result);      
        $sql="SELECT * FROM inventory where item_id=$row[item_id]"; 
        $result=mysqli_query($link, $sql);     
        if($result && mysqli_num_rows($result) > 0){      
            $row=mysqli_fetch_assoc($result);
            return "$row[weight], '$row[weight_unit]', $row[height], $row[width], $row[length], '$row[length_unit]'";
        }
  
    }
}


function get_min_max_rates($link){
    $max_id=0;
    $sql="SELECT app_id FROM delivery_apps where rate_per_kg = (SELECT max(rate_per_kg) as 'max' FROM delivery_apps) LIMIT 1";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $max_id=$row['app_id'];
    }

    $min_id=0;
    $sql="SELECT app_id FROM delivery_apps where rate_per_kg = (SELECT min(rate_per_kg) as 'min' FROM delivery_apps) LIMIT 1";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $min_id=$row['app_id'];
    }
    return $min_id.'|'.$max_id;

}