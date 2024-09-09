<?php


$about_id=$_SESSION['admin_user_id'];
$upd=0;
if($_SERVER['REQUEST_METHOD']=='POST'){
  $ab_id= $_SESSION['admin_user_id'];
  $ab_uname= $_POST['uname'];
  $ab_fname= $_POST['fname'];
  $ab_lname= $_POST['lname'];
  $ab_pic = "";
  $ab_email = $_POST['email'];
  $ab_street= $_POST['street'];

  $ab_city= $_POST['city'];
  $ab_prov= $_POST['prov'];
  $ab_contno= $_POST['contno'];
  $ab_country=  $_POST['country'];
  $ab_dte=  $_POST['dte'];
  $ab_exp= "";
  $ab_addet= "";

  if($ab_pic!=""){

    $fdname=$about_id;

    $structure = './img/profile/'.$fdname;
    // $structure2 = './img/profile/'.$fdname;
    if (!file_exists($structure)) {
      mkdir($structure, 0777, true);
    }
    
    
    
    
     $data = $ab_pic;
    
    list($type, $data) = explode(';', $data);
    list(, $data)      = explode(',', $data);
    $data = base64_decode($data);

    $ext=str_replace("data:image/",".",$type);
    $destdir = $structure.'/'.$about_id.$ext;

    $files = glob($structure.'/'.$about_id.'*'); 
    foreach($files as $file){ 
      if(is_file($file)) {
      unlink($file); 
      }
    }

    if(file_put_contents($destdir, $data)){
      $ab_pic=$destdir;

      $sql = "UPDATE info SET pic='$ab_pic' WHERE user_id=$ab_id";
      if ($alink->query($sql) === TRUE) {
        
      } else {
        echo "Error updating record: " . $alink->error;
      }
    };
    }



            $sql = "UPDATE info SET fname='$ab_fname', lname='$ab_lname',street='$ab_street',city='$ab_city',prov='$ab_prov',contno='$ab_contno',country='$ab_country',exp='$ab_exp',addet='$ab_addet',dte='$ab_dte' WHERE user_id=$ab_id";
            if ($alink->query($sql) === TRUE) {
              $upd=1;
            } else {
              echo "Error updating record: " . $alink->error;
            }
            $sql = "UPDATE user SET username='$ab_uname',email='$ab_email' WHERE user_id=$ab_id";
            if ($alink->query($sql) === TRUE) {
              $upd=1;
            } else {
              echo "Error updating record: " . $alink->error;
            }

            
              

}

// $arr=array();
// $sql = "SELECT * FROM info where user_id=$about_id";
// if($result = mysqli_query($alink, $sql)){
//   if(mysqli_num_rows($result) > 0){ 
//       while($row = mysqli_fetch_array($result)){  
//       array_push($arr,$row['fname']);
//       array_push($arr,$row['lname']);
//         if($row['pic']!=""){
//           array_push($arr,$row['pic']);
//         }else{
//           array_push($arr,"img/empty.png");
//         }
//         array_push($arr,$row['contno']);
//         array_push($arr,$row['country']);
//         array_push($arr,$row['street']);
//         array_push($arr,$row['bar']);
//         array_push($arr,$row['city']);
//         array_push($arr,$row['prov']);
//         array_push($arr,$row['dte']);
//           }
//       mysqli_free_result($result);
//   } else{
//       echo "No records matching your query were found.";
//   }
// } else{
//   echo "ERROR: Could not able to execute $sql. " . mysqli_error($alink);
// }


// $sql = "SELECT username, email FROM user where user_id=$about_id";
// if($result = mysqli_query($alink, $sql)){
//   if(mysqli_num_rows($result) > 0){ 
//       while($row = mysqli_fetch_array($result)){   
        
//         array_push($arr,$row[0]);
//         array_push($arr,$row[1]);
//           }
//       mysqli_free_result($result);
//   } else{
//       echo "No records matching your query were found.";
//   }
// } else{
//   echo "ERROR: Could not able to execute $sql. " . mysqli_error($alink);
// }


$sql = "SELECT * FROM info where user_id=$about_id";
if($result = mysqli_query($alink, $sql)){
  if(mysqli_num_rows($result) > 0){ 
      while($row = mysqli_fetch_array($result)){  
        $about_fname= $row['fname'];
        $about_lname= $row['lname'];
        if($row['pic']!=""){
          $about_pic= $row['pic'];
        }else{
          $about_pic="img/empty.png";
        }

        $about_contno= $row['contno']  ;
        $about_country=  $row['country']  ;
        $ab_street= $row['street'];
        $ab_city= $row['city'];
        $ab_prov= $row['prov'];
        $ab_dte= $row['dte'];
          }
      mysqli_free_result($result);
  } else{
      echo "No records matching your query were found.";
  }
} else{
  echo "ERROR: Could not able to execute $sql. " . mysqli_error($alink);
}


$sql = "SELECT username, email FROM user where user_id=$about_id";
if($result = mysqli_query($alink, $sql)){
  if(mysqli_num_rows($result) > 0){ 
      while($row = mysqli_fetch_array($result)){     
        $about_uname= $row[0]  ;
        $about_email= $row[1]  ;
          }
      mysqli_free_result($result);
  } else{
      echo "No records matching your query were found.";
  }
} else{
  echo "ERROR: Could not able to execute $sql. " . mysqli_error($alink);
}

// echo json_encode($arr);
                                       

?>

