<?php



function cancel_purchase($link,$t_id){
    $sql="update transac set status = -2 where t_id=$t_id";
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
    $sql="SELECT fname,phone,street,city,prov,payment FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);  
            if($row['payment']==0){
                $payment="Cash on delivery";
            }
           return '
           <p class="strong mb-2">Name: '.$row['fname'].'</p>
           <p class="strong mb-2">Delivery Address: '.$row['street'].', '.$row['city'].', '.$row['prov'].'</p>
           <p class="strong mb-2">Phone Number: '.$row['phone'].'</p>
           <p class="strong mb-2">Payment type: '.$payment.'</p>
           ';


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
        $src='../images/products/'.$row['item_id'];
        if($v_id>0){
          $src='../images/vproducts/'.$row['v_id'];
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

function get_delv_details($link,$id){
   
    $sql="SELECT r_id,d_id FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['r_id']>0 && $row['d_id']==0){
            return '<div class="col-12">
                        <p class="strong mb-2">Delivery Company: '.get_app($link,$id).'</p>
                    </div>
            ';
        }else if($row['r_id']>0 && $row['d_id']>0){
            return '<div class="col-12">
                        <p class="strong mb-2">Delivery Company: '.get_app($link,$id).'</p>
                        <p class="strong mb-2">Courier: '.get_courier($link,$id).'</p>
                        <p class="strong mb-2">Contact: '.get_courier_phone($link,$id).'</p>
                    </div>
            ';
        }
        return '';
    }
}

function get_delv_stat($link,$id){
    $sql="SELECT d_id,d_stat,r_id,status FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['status']<0){

            if($row['status']==-1){
                $by="by the customer";
            }
            if($row['status']==-2){
                $by="by you"; 
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

            if($row['r_id']==0 && $row['status']>=0){
                return '<button class="btn btn-warning" id="'.$row['t_id'].'can" onclick="cancel('.$row['t_id'].')">Cancel</button>';
            }
     
        return '';
    }

}

function get_assign_button($link,$id){

    // $sql="SELECT d_id,d_stat,r_id,status,t_id,item_id FROM transac where t_id=$id";
    // $result = mysqli_query($link, $sql);
    // if($result && mysqli_num_rows($result) > 0){ 
    //     $row = mysqli_fetch_array($result);

    //         if($row['r_id']==0 && $row['status']>=0){
    //             return '<button class="btn btn-warning" id="'.$row['t_id'].'can" onclick="cancel('.$row['t_id'].')">Cancel</button>';
    //         }
     
    //     return '';
    // }

    $sql="SELECT d_id,d_stat,r_id,status FROM transac where t_id=$id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);
        if($row['status']<0){
            return '';
        }
        if($row['d_stat']==1){
            return '';
        }
        if($row['d_id']>0){
            return '';
        }
        if($row['r_id']>0){
            return '<span class="text-danger" onclick="unassign('.$id.','.$row['r_id'].')" style="cursor:pointer;">Un-assign order from '.get_app($link,$id).'</span>';
        }
        return '<span class="text-warning" onclick="cancel('.$id.')" style="cursor:pointer;">Cancel</span> | <span class="text-info" onclick="assign('.$id.')" style="cursor:pointer;">Assign order to </span>
<select name="delvapps" id="delvapps">
  '.get_apps($link).'
</select>';
    }


}


function get_apps($link){
    $sql="SELECT * FROM delivery_apps";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $str="";
        while($row = mysqli_fetch_array($result)){
            $str.= '<option value="'.$row['app_id'].'">'.$row['name'].'</option>';
        }
        
        return $str;
    }
    return '';

}





function get_pay_button($link,$id){

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


function get_transac($link,$t_id){


echo '<div id="crt"><div class="pb-5">
    <div class="container">
      <div class="row">

        <div class="col-md-12">
            <div class="card border-0 ">
                <div class="row mt-4">
                
                    <div class="col-12">
                            <p class="strong mb-2"><span class="strong mb-2">'.get_assign_button($link,$t_id).'</span></p>

                            <hr class="mt-0">
                    </div>
                    <br>
                    <div class="col-12">
                        <p class="strong mb-2">ORDER ID: '.$t_id.'<span class="strong mb-2" style="float:right;">'.get_delv_stat($link,$t_id).'</span></p>

                        <hr class="mt-0">
                    </div>
                    <div class="col-12">

                    '.get_buyer($link,$t_id).'
                    <hr class="mt-0">
                    </div>
                    '.get_delv_details($link,$t_id).'


                    
                </div>
                
            </div>
        </div>


        




        <div class="col-lg-12 p-5 bg-white rounded shadow-sm mb-5">
            <div class="col">
                <p class="text-muted mb-2">ORDER DETAILS</p>
                <hr class="mt-0">
            </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>

                  <th scope="col" class="border-0 bg-light">
                    <div class="p-2 px-3 text-uppercase">Item</div>
                  </th>
                  <th scope="col" class="border-0 bg-light">
                    <div class="py-2 text-uppercase">Quantity</div>
                  </th>
                  <th scope="col" class="border-0 bg-light">
                    <div class="py-2 text-uppercase">Sub Price</div>
                  </th>


                </tr>
              </thead>
              <tbody>
               

                            <tr id="'.$t_id.'tr">

                            <th scope="row">
                               <img src="'.get_item_img($link,$t_id).'" class="img-sm border" height="80px" width="80px">
                               <div class="ml-3 d-inline-block align-middle">     

                                  <h5 class="mb-0"><a class="text-dark d-inline-block align-middle">'.get_item_name($link,$t_id).'</a></h5>
                                </div>
                            
                            </th>
                            <td class="align-middle">
                              <p id="'.$t_id.'p" style="width: 8%;margin: 1%;text-align: center;" type="text">'.get_qnt($link,$t_id).'</p>
                            </td>
                            <td class="border-0 align-middle"><strong>₱<span id="'.$t_id.'pr">'.get_price($link,$t_id).'</span></strong></td>

                          </tr>
                        
          
            </tbody>
            
            </table>
            <div class="col-12">

            '.get_price_fee($link,$t_id).'
            <hr class="mt-0">
            </div>
          </div>
        
        </div>
 

        
        
                           

                            

                           
                            
                            
                           
                            <div class="card shadow mb-4 col-md-12 mt-2" id="del">


                            </div>

                            <div class="col-md-12">

                                <div class="track">
    
                                       '.get_delv_tracks($link,$t_id).'
                               
                                </div>

                            </div>';
}