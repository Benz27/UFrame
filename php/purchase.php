<?php


// $sql="SELECT * FROM transac where t_id= $_POST[id]";
// $result = mysqli_query($link, $sql);
// if($result && mysqli_num_rows($result) > 0){ 
//     while($row = mysqli_fetch_array($result)){
//         $order_id=$row['t_id'];
//         $status=$row['status'];
//         $stat="On the Way";
//         if($status==1){
//             $stat="Delivered";
//         }
//         echo '<tr>
//         <td><a class="text-dark" style="text-decoration: none;">'.$row['d_id'].'</a></td>
//         <td><a class="text-dark" style="text-decoration: none;">'.$row['name'].'</a></td>
//         <td><a class="text-dark" style="text-decoration: none;">'.get_items($link, $order_id).'</a></td>
//         <td><a class="text-dark" style="text-decoration: none;">'.$row['charge'].'</a></td>
//         <td><a class="text-dark" style="text-decoration: none;">'.$stat.'</a></td>    
//         </tr>';
//     }

// }


function get_app($link,$id){
    $sql="SELECT r_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT app_id FROM delivery_request where r_id=$row[r_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                $sql="SELECT name FROM delivery_apps where app_id=$row[app_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                    return $row['name'];
        
            }
        
            }
        

    }

}

function get_app_phone($link,$id){
    $sql="SELECT r_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT app_id FROM delivery_request where r_id=$row[r_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                $sql="SELECT phone FROM delivery_apps where app_id=$row[app_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                    return $row['phone'];
        
            }
        
            }
        

    }

}

function get_fee($link,$id){
    $sql="SELECT r_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT fee FROM delivery_request where r_id=$row[r_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                return $row['fee'];
        
            }
        

    }

}

function get_price_fee($link,$id){
    $sql="SELECT price,r_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $price=$row['price'];
            if($row['r_id']!=0){
                $sql="SELECT fee FROM delivery_request where r_id=$row[r_id]";
                $result = mysqli_query($link, $sql);
                if($result && mysqli_num_rows($result) > 0){ 
                    $row = mysqli_fetch_array($result);
                    $price+=$row['fee'];

                    return '<span class="text-muted">Total: (With shipping fee: ₱'.$row['fee'].')₱'.$price.' </span>';

            
                }
            }
           
            return '<span class="text-muted">Sub Total: ₱'.$price.' </span>';

    }

}




function get_price($link,$id){
    $sql="SELECT price,qnt FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);    
            return $row['price']*$row['qnt'];
    }

}


function get_buyer($link,$id){
    $sql="SELECT fname,phone,street,city,prov FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);  

           return '<hr>
           <div class="col"><small>Name: '.$row['fname'].'</small></div>
           <div class="col"><small>Delivery Address: '.$row['street'].', '.$row['city'].', '.$row['prov'].'</small> </div>
           <div class="col"><small>Phone Number: '.$row['phone'].'</small> </div>
           <hr>';
            
    }

}


function get_courier($link,$id){
    $sql="SELECT d_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT name FROM delivery where d_id=$row[d_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                return $row['name'];
        
            }
        

    }

}

function get_courier_phone($link,$id){
    $sql="SELECT d_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT phone FROM delivery where d_id=$row[d_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                return $row['phone'];
        
            }
        

    }

}


function get_item_img($link,$id){
    $sql="SELECT item_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT media,item_id,v_id FROM inventory where item_id=$row[item_id]";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);
            $med=explode("|",$row['media']);

            $v_id=$row['v_id'];
            $src='../../images/products/'.$row['item_id'];
            if($v_id>0){
              $src='../../images/vproducts/'.$row['v_id'];
            }

            return $src.'/'.$med[0];
    }
}

function get_item_name($link,$id){
    $sql="SELECT item_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
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

function get_item_stock($link,$id){
    $sql="SELECT item_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT quantity FROM inventory where item_id=$row[item_id]";
            $result = mysqli_query($link, $sql);
            $row = mysqli_fetch_array($result);


            return $row['quantity'];
    }
}


function get_qnt($link,$id){
    $sql="SELECT qnt FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        return $row['qnt'];
            
    }
}

function get_delv_tracks($link,$id){
    $sql="SELECT d_id,d_stat,r_id,status FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $steps='<div class="step active"> <span class="icon"> <i class="fa fa-list"></i> </span> <span class="text"> Preparing Order</span> </div>';
        if($row['status']<0){




            $steps.='<div class="step active"> <span class="icon"> <i class="fa fa-exclamation-circle"></i> </span> <span class="text"> Canceled</span> </div>';
            return $steps;
        }
        if($row['r_id']>0){
            $steps.='<div class="step active"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text"> To Ship<span class="text-muted">('.get_ship_date($link,$id).')</span></span> </div>';
            if($row['d_id']>0){
                $steps.='<div class="step active"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> Departed<span class="text-muted">('.get_receive_date($link,$id).')</span></span> </div>';
                if($row['d_stat']>0){
                    $steps.='<div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text"> Completed<span class="text-muted">('.get_delivered_date($link,$id).')</span></span> </div>';
                }
      
            }
        }
        return $steps;
    }
}

function get_delv_stat($link,$id){
    $sql="SELECT d_id,d_stat,r_id,status FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['status']<0){

            if($row['status']==-1){
                $by="by you";
            }
            if($row['status']==-2){
                $by="by the seller"; 
            }


            return 'Canceled '.$by.'';
        }
        if($row['d_stat']==1){
            return 'Delivered';
        }
        if($row['d_id']>0){
            return 'To Receive';
        }
        if($row['r_id']>0){
            return 'To Ship';
        }
        return 'Preparing';
    }
}


function get_delv_button($link,$id){

    $sql="SELECT d_id,d_stat,r_id,status,t_id,item_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);


        if($row['d_stat']>0 || $row['status'] < 0){
            return '<button onclick="addto('.$row['item_id'].',0,'.get_item_stock($link,$row['t_id']).')" class="btn btn-warning">Buy Again</button>';
        }else{
            if($row['r_id']==0){
                return '<button class="btn btn-warning" id="'.$row['t_id'].'can" onclick="cancel('.$row['t_id'].')" style="float:right;">Cancel</button>';
            }
        }
        return '';
    }

}

function get_ship_date($link,$id){

    $sql="SELECT r_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT dtedisp FROM delivery_request where r_id=$row[r_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                return $row['dtedisp'];
        
            }
        

    }


}

function get_receive_date($link,$id){

    $sql="SELECT d_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT departed FROM delivery where d_id=$row[d_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                return $row['departed'];
        
            }
        

    }


}

function get_delivered_date($link,$id){

    $sql="SELECT d_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
            $sql="SELECT delivered FROM delivery where d_id=$row[d_id]";
            $result = mysqli_query($link, $sql);
            if($result && mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_array($result);
                    
                return $row['delivered'];
        
            }
        

    }


}


function get_shping($link,$id){

    $sql="SELECT r_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['r_id']>0){
            return '<div class="col"> <strong>Shipping BY:</strong> <br> '.get_app($link,$_POST['id']).', '.get_courier($link,$id).', | <i class="fa fa-phone"></i> '.get_courier_phone($link,$_POST['id']).' </div>';
        }
        return '';
    }

}


function get_purchase($link){
    echo '<div class="container">
    <article class="card">
    <div class="card-body">
    <h6>Order ID: '.$_POST['id'].'</h6>
    '.get_buyer($link,$_POST['id']).'
    <article class="card">
    <div class="card-body row">
    '.get_shping($link,$_POST['id']).'
    <div class="col"> <strong>Status:</strong> <br> '.get_delv_stat($link,$_POST['id']).' </div>

    </div>
    </article>
    <div class="track">
    '.get_delv_tracks($link,$_POST['id']).'    
    </div>
    <hr>
    <ul class="row">
    <li class="col-md-4">
    <figure class="itemside mb-3">
    <div class="aside"><img src="'.get_item_img($link,$_POST['id']).'" class="img-sm border"></div>
    <figcaption class="info align-self-center">
    <p class="title">'.get_item_name($link,$_POST['id']).'</p> <span class="text-muted">₱'.get_price($link,$_POST['id']).'</span><span class="text-black mx-1">&middot;</span><span class="text-muted">x'.get_qnt($link,$_POST['id']).'</span>
    </figcaption>
    </figure>
    </li>
    </ul>
    '.get_total($link,$_POST['id']).'
    <br>
    <hr>

    <a href="../orders/" class="btn btn-warning" data-abc="true"> <i class="fa fa-chevron-left"></i> Back to orders</a>
    '.get_delv_button($link,$_POST['id']).'
    </div>
    </article>
    </div>';
}

function cancel_purchase($link,$t_id){
$sql="update transac set status = -1 where t_id=$t_id";
if ($link->query($sql) === TRUE) {
    return 1;
}else{
    return $link->error;
}
}

function get_total($link,$t_id){
    $sql="SELECT t_id,price,r_id FROM transac where t_id=$t_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        $price=$row['price'];
            if($row['r_id']!=0){
                $sql="SELECT fee FROM delivery_request where r_id=$row[r_id]";
                $result = mysqli_query($link, $sql);
                if($result && mysqli_num_rows($result) > 0){ 
                    $row = mysqli_fetch_array($result);
                        $fee=$row['fee'];

                        
                    return '<label style="float:right;">Total: ₱'.$price+$fee.'(₱'.$fee.' Shipping fee)</label>';
            
                }
            }
            return '<label style="float:right;">Sub Total: ₱'.$price.'</label>';
    }
}
    