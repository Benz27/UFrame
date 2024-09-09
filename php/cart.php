<?php



    $s_uid=true;
    $s_user_id = $_SESSION['user_id']; 
    $query="select * from cart where user_id = $s_user_id";    
    $s_result=mysqli_query($link, $query);
    if($s_result && mysqli_num_rows($s_result) > 0){  
        $s_row = mysqli_fetch_array($s_result);

        if($s_row['item_id'] == null){
            $s_cart=array();
            $s_quant=array();
        }else{
            $s_cart=explode(",", $s_row['item_id']);
            $s_qnt=explode(",", $s_row['qnt']);
        }

    }

function trc($link){

            $s_user_id = $_SESSION['user_id']; 
            $s_query="select * from cart where user_id = $s_user_id";    
            $s_result=mysqli_query($link, $s_query);     
            if($s_result && mysqli_num_rows($s_result) > 0){  
                $s_row = mysqli_fetch_array($s_result);
                
                if($s_row['item_id'] == null){
                    $cart=array();
                    $qnt=array();
                }else{
                    $cart=explode(",", $s_row['item_id']);
                    $qnt=explode(",", $s_row['qnt']);
                }
        
            }


            for($x=0;$x<count($_SESSION['cart']);$x++){

                foreach (array_keys($cart, $_SESSION['cart'][$x]) as $key) {
                    $targ=$key;
                }

                if(isset($targ)){
                    $qnt[$targ]+=$_SESSION['qnt'][$x];
                    $tt=0;
                    $sql="select * from inventory where item_id = $cart[$targ]";
                    $result=mysqli_query($link, $sql);  
                    if($result && mysqli_num_rows($result) > 0){
                        $row = mysqli_fetch_array($result);
                        $tt=$row['stock'];

                        if($qnt[$targ] > $tt){
                            $qnt[$targ]=$tt;
                        }
                    }
                }else{
                    array_push($cart,$_SESSION['cart'][$x]);
                    array_push($qnt,$_SESSION['qnt'][$x]);
                }
            }


    updc($cart, $qnt, $link);
    dc($link);
    
    $_SESSION['cart']=array();
    $_SESSION['qnt']=array();
}

function updc($c, $qt, $link){

  
    
        if($c == null || count($c)==0){
            $i_ids=null;
            $qnt=null;
        }else{
            $i_ids=implode(",",$c);
            $qnt=implode(",",$qt);
        }

        $s_user_id = $_SESSION['user_id']; 
        $i_ids=implode(",",$c);
        $qnt=implode(",",$qt);
        $s_query="update cart set item_id = '$i_ids', qnt = '$qnt' where user_id = $s_user_id";    
        if ($link->query($s_query) === TRUE) {
            
        }else{
            array_push($errmsg, $link->error);
        }
    
    
    
    
}
    
function dc($link){
    
   
            $s_user_id = $_SESSION['user_id']; 
            $s_query="select * from cart where user_id = $s_user_id";    
            $s_result=mysqli_query($link, $s_query);     
            if($s_result && mysqli_num_rows($s_result) > 0){  
                $s_row = mysqli_fetch_array($s_result);
                
                if($s_row['item_id'] == null){
                    $GLOBALS['s_cart']=array();
                    $GLOBALS['s_qnt']=array();
                }else{
                    $GLOBALS['s_cart']=explode(",", $s_row['item_id']);
                    $GLOBALS['s_qnt']=explode(",", $s_row['qnt']);
                }

        
            }
            
        
}
