<?php

session_start();
include("conn.php");

$d_id=$_GET['id'];
$x=$_GET['x'];
$a=$_GET['a'];
$stat=0;
$cont=true;
$errmsg=array();
$arr=array();
    $sql="SELECT * FROM delivery where d_id=$d_id";
    if($result=mysqli_query($link, $sql)){
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $i_ids=explode(" ",$row['i_ids']);
            $icnt=count($i_ids);
            $stat=$row['stat'];
        }
    }else{
        array_push($errmsg,"Error (select 1):".$link->error);
    };

if($a==0){
            if($x==1){
                for($x=0;$x<$icnt;$x++){
                    upd($i_ids[$x]);
                }
            }else if($x==2){
                fin($d_id);
            }
            
            
            if($cont){
                upd2($d_id);
            }
        
}else if($a==2){

        canc($d_id);

}else if($a==3){
    
    $sql="DELETE FROM delivery WHERE d_id=$d_id"; 
    if ($link->query($sql) === TRUE) {

    } else {
        $GLOBALS['cont']=false;
        array_push($GLOBALS['errmsg'],"Error (function delete()):".$link->error);
    }

}




$red=0;
$status=-9;
$sql="SELECT * FROM delivery where d_id=$d_id";
    if($result=mysqli_query($link, $sql)){
        
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $status=$row['stat'];
            $id=explode(" ",$row['i_ids']);
            $idcnt=count($id);

        }else{
            $red=1;
        }
    }else{
        array_push($errmsg,"Error (select 2):".$link->error);
    };


    $str0 = '

            <tbody>';
    
             $str1="";
             for($x=0;$x<$idcnt;$x++){
          
                               
                $sql="select * from transac where t_id=$id[$x]";
                $result = mysqli_query($link, $sql);
                if($result && mysqli_num_rows($result) > 0){ 
                    $row = mysqli_fetch_array($result);
                    $stat="";
                    $clss="text-primary";
                    $clss2="text-secondary";
                    $shp=$row['shp'];
                    $dlvid=$row['d_id'];
                    $type="";
                    if($shp==0){
                        
                        switch ($row['status']){
                            case 2:
                            $stat="Waiting departure";
                              break;
                              case 3:
                            $stat="On the Way";
                            $type="Shipment Recieved";
                              break;
                              case 4:
                            $stat="Waiting confirmation";
                              break;
                              case 5:
                              $stat="Recieved";
                              $clss='class="text-success"';
                            break;
                        }
                        if($dlvid==-1){
                            $stat="Failed";
                              $clss='class="text-warning"';
                        }
                    }
                         $str1.= '<tr>
                             <td><a class="text-primary" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['fname'].'</a></td>
                                    <td><a class="text-primary" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['itmc'].'</a></td>
                                    <td><a class="text-primary" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['totprice'].'</a></td>
                                    <td><a class="text-primary" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['street'].'</a></td>
                                    <td><a class="text-primary" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['city'].'</a></td>
                                    <td><a class="text-primary" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['prov'].'</a></td>
                                    <td><a class="text-primary" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;font-weight: bold;">'.$row['dtedisp'].'</a></td>
                                    <td><a '.$clss.' href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;font-weight: bold;">'.$stat.'</a></td>
                        </tr>';
                }
            }
    
         $str2='</tbody>';
    
    
    $str=$str0.$str1.$str2;









    
    $errmsg=implode(" ",$errmsg);
   
    array_push($arr,$status,$errmsg,$x,$red,$str);
    echo json_encode($arr);



// functions:

function upd($id){

        $link=$GLOBALS['link'];
        $x=$_GET['x']+2;
    
            $sql="SELECT * FROM transac where t_id=$id";  
                $result=mysqli_query($link, $sql);    
            if($result && mysqli_num_rows($result) > 0){        
                $row=mysqli_fetch_assoc($result);  
                $sdte=explode("|",$row['statdte']);
                $sdtedisp=explode("|",$row['statdtedisp']);
                $sdtenum=explode("|",$row['statdtenum']);
                $email=$row['email'];
            }
    
           
                array_push($sdte,date("Y-m-d"));
                array_push($sdtedisp,date('m/d/Y').' at '.date('g:iA'));
                array_push($sdtenum,(date('Y')*10000)+(date('n')*100)+date('j'));
        
                $sdte=implode("|",$sdte);
                $sdtedisp=implode("|",$sdtedisp);
                $sdtenum=implode("|",$sdtenum);

    
        $sql="UPDATE transac SET status=$x, statdte='$sdte', statdtedisp='$sdtedisp',statdtenum='$sdtenum' WHERE t_id=$id"; 
        if ($link->query($sql) === TRUE) {


            getord($id,$link);
            sendmssg("Rose`s Bed Accessories",$email,"Order Update","Your shipment is on its way.".getord($id,$link));

            
        } else {
            $GLOBALS['cont']=false;
            array_push($GLOBALS['errmsg'],"Error (function upd($id)):".$link->error);
        }
}

function fin($id){

    $link=$GLOBALS['link'];
    $x=$_GET['x'];

    $sql="SELECT * FROM delivery where d_id=$id";
    if($result=mysqli_query($link, $sql)){
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $i_ids=explode(" ",$row['i_ids']);
            $icnt=count($i_ids);
        }
    }else{
        array_push($errmsg,"Error (function fin:select 1):".$link->error);
    };

  
    for($y=0;$y<$icnt;$y++){
                
        $sql="SELECT * FROM transac where t_id=$i_ids[$y]";  
            $result=mysqli_query($link, $sql);    
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $sdte=explode("|",$row['statdte']);
            $sdtedisp=explode("|",$row['statdtedisp']);
            $sdtenum=explode("|",$row['statdtenum']);
            $stt=$row['status'];
            $email=$row['email'];
        }

        if($stt<4){

        
  
            $sdte=array_slice($sdte,0,1);
            $sdtedisp=array_slice($sdtedisp,0,1);
            $sdtenum=array_slice($sdtenum,0,1);
            
            $sdte=implode("",$sdte);
            $sdtedisp=implode("",$sdtedisp);
            $sdtenum=implode("",$sdtenum);



        $sql="UPDATE transac SET status=1, statdte='$sdte', statdtedisp='$sdtedisp',statdtenum='$sdtenum',d_id=-1 WHERE t_id=$i_ids[$y]"; 
        if ($link->query($sql) === TRUE) {

            getord($i_ids[$y],$link);
            sendmssg("Rose`s Bed Accessories",$email,"Order Update","Delivery has failed to reach your location. Shop will attempt to set another delivery.".getord($i_ids[$y],$link));
        } else {
            $GLOBALS['cont']=false;
            array_push($GLOBALS['errmsg'],"Error (function fin:transac($id)):".$link->error);
        }

        }

    }



    $sql="UPDATE delivery SET stat=$x WHERE d_id=$id"; 
    if ($link->query($sql) === TRUE) {
    
    } else {
        $GLOBALS['cont']=false;
        array_push($GLOBALS['errmsg'],"Error (function canc:delivery($id)):".$link->error);
    }


}


function upd2($id){

    $link=$GLOBALS['link'];
    $x=$_GET['x'];

        $sql="SELECT * FROM delivery where d_id=$id";  
            $result=mysqli_query($link, $sql);
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $sdte=explode("|",$row['statdte']);
            $sdtedisp=explode("|",$row['statdtedisp']);
            $sdtenum=explode("|",$row['statdtenum']);
        }

        if($sdte[0]){
            array_push($sdte,date("Y-m-d"));
            array_push($sdtedisp,date('m/d/Y').' at '.date('g:iA'));
            array_push($sdtenum,(date('Y')*10000)+(date('n')*100)+date('j'));
    
            $sdte=implode("|",$sdte);
            $sdtedisp=implode("|",$sdtedisp);
            $sdtenum=implode("|",$sdtenum);
        }else{
            $sdte=date("Y-m-d");
            $sdtedisp=date('m/d/Y').' at '.date('g:iA');
            $sdtenum=strval((date('Y')*10000)+(date('n')*100)+date('j'));
        }


    $sql="UPDATE delivery SET stat=$x, statdte='$sdte', statdtedisp='$sdtedisp',statdtenum='$sdtenum' WHERE d_id=$id"; 
    if ($link->query($sql) === TRUE) {
        
    } else {
        $GLOBALS['cont']=false;
        array_push($GLOBALS['errmsg'],"Error (function upd2($id)):".$link->error.', '.$x.', '.$sdte.', '.$sdtedisp.', '.$sdtenum);
    }
}

function canc($id){
    $link=$GLOBALS['link'];
    $x=$_GET['x'];
    if($x==0){
        $x=-9;
    }

    $sql="SELECT * FROM delivery where d_id=$id";
    if($result=mysqli_query($link, $sql)){
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $i_ids=explode(" ",$row['i_ids']);
            $icnt=count($i_ids);
        }
    }else{
        array_push($errmsg,"Error (function canc:select 1):".$link->error);
    };

  
    for($y=0;$y<$icnt;$y++){
                
        $sql="SELECT * FROM transac where t_id=$i_ids[$y]";  
            $result=mysqli_query($link, $sql);    
        if($result && mysqli_num_rows($result) > 0){        
            $row=mysqli_fetch_assoc($result);  
            $sdte=explode("|",$row['statdte']);
            $sdtedisp=explode("|",$row['statdtedisp']);
            $sdtenum=explode("|",$row['statdtenum']);
            $email=$row['email'];
            $sdte=array_slice($sdte,0,1);
            $sdtedisp=array_slice($sdtedisp,0,1);
            $sdtenum=array_slice($sdtenum,0,1);
            
            $sdte=implode("",$sdte);
            $sdtedisp=implode("",$sdtedisp);
            $sdtenum=implode("",$sdtenum);



        $sql="UPDATE transac SET status=1, statdte='$sdte', statdtedisp='$sdtedisp',statdtenum='$sdtenum',d_id=0 WHERE d_id=$id"; 
        if ($link->query($sql) === TRUE) {

            
            sendmssg("Rose`s Bed Accessories",$email,"Order Update","Delivery has been canceled. Shop will attempt to set another delivery.".getord($i_ids[$y],$link));
    
        } else {
            $GLOBALS['cont']=false;
            array_push($GLOBALS['errmsg'],"Error (function canc:transac($id)):".$link->error);
        }
 
        }

     
  
            
    }



    $sql="UPDATE delivery SET stat=$x WHERE d_id=$id"; 
    if ($link->query($sql) === TRUE) {
    
    } else {
        $GLOBALS['cont']=false;
        array_push($GLOBALS['errmsg'],"Error (function canc:delivery($id)):".$link->error);
    }


}



function sendmssg($name,$email,$sub,$mesage){
    $_SERVER['REQUEST_METHOD']="POST";
    $_POST['name']=$name;

    $_POST['email']=$email;
    
    $_POST['subject']=$sub;

    $_POST['message']=$mesage;
    include("./contactform/submit.php");

}


function getord($t_id,$link){
    $sql="select * from transac where t_id = $t_id";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){ 

      $row=mysqli_fetch_assoc($result); 
      $id=explode(" ",$row['i_ids']);
      $qnt=explode(" ",$row['qnt']);
      $prce=explode(" ",$row['price']);

      $tott=0;
        $sfee="";
        if($row['status']>0 && $row['shp']==0){
        $tott+=$row['fee'];
          $sfee=' with shipping fee(₱'.$row['fee'].')';
        }

        $ord="";
        $idcnt=count($id);
        for($x=0;$x<$idcnt;$x++){

            
        

            $sql = "SELECT * FROM items where item_id=$id[$x]";  
            
            
            if($result = mysqli_query($link, $sql)){
                
                if(mysqli_num_rows($result) > 0){ 
                   
                  $row=mysqli_fetch_assoc($result); 
                  
                    

                  $pqnt=$qnt[$x];

                  $price=$prce[$x];
                    
                  $tott+=$price;
                  $ord.=" #1: ".$row['name']." X".$pqnt."(₱".$price."). ";
                 return "\r\n
                  Your order: ".$ord."\r\n
                  Total Price:".$tott.$sfee."\r\n
                  ---------------------------------------------------------------------------------------------\r\n
                  © 2022 Rose`s Bed Accessories";

                }
            }
        }

    }
}
}
// if($sdte[0]){
//     $z=$x-1;
//     $sep="";
//     array_slice($sdte,0,$z);
//     array_slice($sdtedisp,0,$z);
//     array_slice($sdtenum,0,$z);

//     // array_push($sdte,date("Y-m-d"));
//     // array_push($sdtedisp,date('m/d/Y').' at '.date('g:iA'));
//     // array_push($sdtenum,(date('Y')*10000)+(date('n')*100)+date('j'));
//     if($sdtecnt>1){
//         $sep="|";
//     }
//     $sdte=implode($sep,$sdte);
//     $sdtedisp=implode($sep,$sdtedisp);
//     $sdtenum=implode($sep,$sdtenum);
// }else{
//     $sdte="";
//     $sdtedisp="";
//     $sdtenum="";
// }