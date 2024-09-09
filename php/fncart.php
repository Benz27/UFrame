<?php
session_start();
include("conn.php");





$id=$_GET['id'];
$x=$_GET['x'];
$st=$_GET['st'];
$qnt=0;
$errmsg=array();
$cart;
$quant;
$uid=false;

    $uid=true;
    $user_id = $_SESSION['user_id']; 
    $query="select * from cart where user_id = $user_id";    
    $result=mysqli_query($link, $query);     
    if($result && mysqli_num_rows($result) > 0){  
        $row = mysqli_fetch_array($result);

        if($row['item_id'] == null){
            $cart=array();
            $quant=array();
        }else{
            $cart=explode(",", $row['item_id']);
            $quant=explode(",", $row['qnt']);
        }
    }
    






if($x==0){

    $q=1;
    $a=true;
    $k;
if(isset($_GET['q'])){
    $q=$_GET['q'];
}

foreach (array_keys($cart, $id) as $key) {
    $a=false;
    $k=$key;
}

if($a){
    array_push($cart, $id);
    array_push($quant, $q);
    updc($cart, $quant, $link);
    dc($link);
    if(array_search($id, $cart) !== false){
        if($quant[count($quant)-1]==$st){
            echo 4;
        }else{
            echo 1;
        }
        $qnt=$quant[count($quant)-1];
    }else{
        echo 0;
    }


}else{
    $quant[$k]=$quant[$k]+$q;
    updc($cart, $quant, $link);
    dc($link);
    
    if($quant[$k]==$st){
        echo 4;
    }else{
        echo 1;
    }
    $qnt=$quant[$k];
}



}else if($x==2){



$targ;
$a=$cart;
$b=$quant;


foreach (array_keys($a, $id) as $key) {
    $targ=$key;
}


$cart=array_merge(array_slice($a,0,$targ), array_slice($a,$targ+1));
$quant=array_merge(array_slice($b,0,$targ), array_slice($b,$targ+1));

updc($cart, $quant, $link);
dc($link);

if(array_search($id, $cart) !== true){
    echo 3;
}else{
    echo 2;
}


}else if($x==3){

    foreach (array_keys($cart, $id) as $key) {
        $k=$key;
    }
    $q=$_GET['q'];
    $quant[$k]=$q;
    updc($cart, $quant, $link);
    dc($link);

    $qnt=$quant[$k];
    echo 5;
}
// $_SESSION['cart']=array();
// $_SESSION['qnt']=array();
echo ','.count($cart);
echo ','.$qnt;

// echo ','.count($_SESSION['cart']);
// echo ','.implode("|",$_SESSION['cart']);
// echo ','.implode("|",$_SESSION['qnt']);

//functions


function updc($c, $qt, $link){


    $user_id = $_SESSION['user_id']; 
    if($c == null || count($c)==0){
        $i_ids=null;
        $qnt=null;
    }else{
        $i_ids=implode(",",$c);
        $qnt=implode(",",$qt);
    }

    $query="update cart set item_id = '$i_ids', qnt = '$qnt' where user_id = $user_id";    
    if ($link->query($query) === TRUE) {
        
    }else{
        array_push($errmsg, $link->error);
    }




}

function dc($link){

  
        $user_id = $_SESSION['user_id']; 
        $query="select * from cart where user_id = $user_id";    
        $result=mysqli_query($link, $query);     
        if($result && mysqli_num_rows($result) > 0){  
            $row = mysqli_fetch_array($result);
            if($row['item_id'] == null){
                $GLOBALS['cart']=array();
                $GLOBALS['quant']=array();
            }else{
                $GLOBALS['cart']=explode(",", $row['item_id']);
                $GLOBALS['quant']=explode(",", $row['qnt']);
            }

    
        }
      
    

}