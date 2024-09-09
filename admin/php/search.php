<?php 
                    session_start();
                    $srch=$_GET['x'];
                    $z=$_GET['z'];
                    $s=$_GET['s'];
                    include("conn.php");
                    if($z==0){
                        $sql = "SELECT * FROM items where item_id like '%$srch%' or name like '%$srch%' or details like '%$srch%' or atdet like '%$srch%' or price like '%$srch%' order by dte DESC";

                    }else if($z==2){
                      $sql = "SELECT * FROM items where item_id=$s";
                    }else{
                        $sql = "SELECT * FROM items order by $s";

                    }
                    if($result = mysqli_query($link, $sql)){

                        if(mysqli_num_rows($result) > 0){ 
                          while($row = mysqli_fetch_array($result)){  
                            $k;
                            $a=false;
                            $stock=$row['stock'];
                            $inh="Add to cart";
                            $dis="";
                            foreach (array_keys($_SESSION['cart'], $row['item_id']) as $key) {
                                $k=$key;
                                $a=true;
                            }
                            if($a){
                                if($_SESSION['qnt'][$k]==$stock){
                                    $inh="Max quantity on cart";
                                    $dis="disabled";
                                }
                            }
                            
                            $med=explode("|",$row['filename']);
                            $det=explode("|*|",$row['details']);
                                $med=explode("|",$row['filename']);
                                $size=explode("x",$row['size']);
                                $det1=$det[0];
                                $det2=$det[1];
                                $medcnt=count($med);
                                $atdet=$row['atdet'];

            echo '<div class="card col-md-3 mb-3 p-2">
            <img class="img-fluid" style="width: 100%;height: 40%;" src="'. '..'.$row['mdir'].$med[0] .'" alt="Card image cap">
            <div class="card-body">
              <strong class="card-title"><b>Name: '.$row['name'].'</b></strong><br>
              <small class="card-title">Price: ₱'.$row['price'] .'</small><br>
              <small class="card-title">Stock: '.$row['stock'] .'</small><br>
              <small class="card-title">Category: '.$det1.'/'.$det2.'</small><br>
              <small class="card-title">Width: '.$size[0].'</small><br>
              <small class="card-title">Length: '.$size[1].'</small><br>
              <small class="card-title">Height: '.$size[2].'</small><br>
              <small class="card-title">Images: '.$medcnt.'</small><br>';

                if($atdet!=""){
                    echo '<small class="card-title">Additional Details: '.$atdet.'</small><br>';
                }
                
                if($row['updq']>0){
                  echo '<hr>';
                  echo '<small class="card-title">Last Updated: '.$row['upddisp'].'</small><br>';
               }

            echo '</div>
            <div class="card-footer">
              <small class="text-muted">Posted '.$row['dtedisp'].'</small> <strong style="float: right;"><a href="edititem.html?id='.$row['item_id'].'">Edit</a></strong>
              
            </div>
          </div>';  





                          }
                        }
                    }

                    