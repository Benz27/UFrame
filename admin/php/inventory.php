<?php

include("aconn.php");
include("read.php");

function getinv($link){
    $sql="select * from inventory";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_array($result)){

           
            echo '<tr>
            <td><a class="text-dark" href="mnorders.html?id='.$row['item_id'].'" style="text-decoration: none;">'.$row['fname'].'</a></td>
            <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['itmc'].'</a></td>
            <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['totprice'].'</a></td>
            <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['dtedisp'].'</a></td>
            <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">Delivery</a></td>
            <td><a href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;"><span class="badge bg-'." ".'">'." ".'</span></a></td>

        </tr>';
    }
        }
    }
    
}

function delv($link, $ord){
    $sql="SELECT * FROM transac where shp=0 $ord order by dte";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){
            $stat="";
            $clss="light text-dark";

            $dlvid=$row['d_id'];
    
                $type="Delivery";
                switch ($row['status']){
                    case 0:
                    $stat="Pending";
                      break;
                    case 1:
                    $stat="Confirmed";
                    $clss='info text-dark';
                      break;
                    case 2:
                    $stat="Waiting departure";
                    $clss='info text-dark';
                      break;
                      case 3:
                    $stat="On the Way";
                      break;
                      case 4:
                    $stat="Waiting confirmation";
                      break;
                      case 5:
                      $stat="Recieved";
                      $clss='success text-light';
                    break;
                  }
            
            if($row['status']<0){
                $clss='class="bg-danger"';
                $stat="Canceled";
            }
            if($dlvid==-1){
                $clss='class="bg-warning text-dark"';
                $stat="Shipment Failed"; 
            }
            echo '<tr>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['fname'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['itmc'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['totprice'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['dtedisp'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">Delivery</a></td>
                <td><a href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;"><span class="badge bg-'.$clss.'">'.$stat.'</span></a></td>
    
            </tr>';
    
    
        }
    
    }
}
