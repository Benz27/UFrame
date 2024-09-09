<?php


function get_apps($link){

    $sql="SELECT * FROM delivery_apps";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){

            echo '<div class="row justify-content-between">
            <div class="col-auto col-md-7">
                <div class="media flex-column flex-sm-row"> <img class="m-2 img-fluid" src="'.$row['logo'].'" width="62" height="62">
                    <div class="media-body my-auto">
                        <div class="row ">
                            <div class="col-auto">
                                <p class="mb-0"><b>'.$row['name'].'</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-2 pl-0 flex-sm-col col-auto my-auto">
            <a class="boxed-1 text-dark" style="text-decoration:none;">Rate per kg: ₱'.$row['rate_per_kg'].'</a>
            </div>
            <div class="m-2 pl-0 flex-sm-col col-auto my-auto">
                <a href="delvlist.html?id='.$row['app_id'].'" class="boxed-1">SELECT</a>
            </div>
            </div>
            <hr class="my-2">';
    
    
        }
    
    }
}

function get_app_details($link,$app_id){
    $sql="SELECT * FROM delivery_apps where app_id=$app_id";
    $result = mysqli_query($link, $sql);
    if($result && mysqli_num_rows($result) > 0){ 
        $row = mysqli_fetch_array($result);

    echo '<div class="row justify-content-between">
            <div class="col-auto col-md-7">
                <div class="media flex-column flex-sm-row"> <img class="m-3" src="'.$row['logo'].'"  height="62">
                    <div class="media-body my-auto">
                        <div class="row ">
                            <div class="col-auto">
                                <h1 class="h3 m-2 text-gray-800" id="app_name">'.$row['name'].'</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    
    
        
    
    }

}

function get_list($link){

    $sql="SELECT * FROM delivery";
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
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['dtedisp'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['itmc'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['totprice'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">'.$row['dtedisp'].'</a></td>
                <td><a class="text-dark" href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;">Delivery</a></td>
                <td><a href="mnorders.html?id='.$row['t_id'].'" style="text-decoration: none;"><span class="badge bg-'.$clss.'">'.$stat.'</span></a></td>
    
            </tr>';
    
    
        }
    
    }
}