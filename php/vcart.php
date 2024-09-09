<?php 
session_start();

include("conn.php");
include("read.php");
if(isset($_SESSION['user_id'])){
    include("cart.php");
}
$arr=array();
$str="";
$price="";
$stc="";
$user_data=check_login($link);
$itm=$_GET['item'];
$sql = "SELECT * FROM inventory where item_id=$itm";
$qc=0;
                    $des="";
                    $at_det="";
                    if($result = mysqli_query($link, $sql)){

                        if(mysqli_num_rows($result) > 0){ 
                          while($row = mysqli_fetch_array($result)){  

                            $ky=0;
                            $a=false;
                            $stock=$row['quantity'];
                            $inh="Add to cart";
                            $dis="";
                            $price=$row['selling_price'];
                            if($s_id){
                            foreach (array_keys($s_cart, $row['item_id']) as $key) {
                                $ky=$key;
                                $a=true;
                            }
                            }
                            if($a){
                                if($s_qnt[$ky]==$stock){
                                    $inh="Max quantity on cart";
                                    $dis="disabled";
                                }
                                $qc=$s_qnt[$ky];
                            }
                            if($stock==0){
                                $dis="disabled";
                                $inh="Sold Out";
                            }

                                $med=explode("|",$row['media']);

                                
                                $price=$row['selling_price'];
                                $stc=$row['quantity'];

                                $str.='
                                <div class="d-flex">';
                                    if($s_id){
                                        $str.='
                                        <button id="min" class="btn-dark"style="width: 40px;" onclick="quan(-1)" '.$dis.'>-</button>
                                    <input class="form-control text-center" id="qnt" value="1" style="max-width: 3rem" '.$dis.' onkeypress="return numonly(event);" onkeyup="qnl(this.value);" />
                                    <button id="max" class="me-3 btn-dark" style="width: 40px;" onclick="quan(1)" '.$dis.'>+</button>
 
                                    <button class="btn btn-outline-dark flex-shrink-0" id="'.$row['item_id'].'" value="0" onclick="addto(this.id, this.value,1,'.$stock.')" type="button" '.$dis.'>
                                        <i class="bi-cart-fill me-1"></i>
                                        Add to cart
                                    </button>';
                                    }else{
                                        $str.='
 
                                    <a href="../login/"><button class="btn btn-outline-dark flex-shrink-0" id="'.$row['item_id'].'" value="0" type="button">
                                        <i class="bi-cart-fill me-1"></i>
                                        Log-in to access your cart
                                    </button></a>';
                                    }
                                    
                                    $str.='
                                </div>
                                <p class="mt-1" style="color: red;" id="alrt" hidden>You have reached the maximum quantity for this item</p>
                            </div>';

                }
            }
        }
             

array_push($arr,$str);
array_push($arr,$price);
array_push($arr,$stc);
array_push($arr,$qc);


echo json_encode($arr);