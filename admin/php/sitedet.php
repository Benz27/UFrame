<?php
$about_id=$_SESSION['admin_user_id'];

if($_SERVER['REQUEST_METHOD']=='POST'){
  $ab_email = $_POST['email'];
  $ab_street= $_POST['street'];
  $ab_bar= $_POST['bar'];
  $ab_city= $_POST['city'];
  $ab_prov= $_POST['prov'];
  $ab_contno= $_POST['contno'];
  $ab_country=  $_POST['country'];
  $abt=  $_POST['abt'];

  $abt_name=$_POST['cpname'];
  $abt_pabt=$_POST['pabt'];
  $abt_sabt=$_POST['sabt'];

  $abt_mis=$_POST['mis'];
  $abt_vis=$_POST['vis'];


            $sql = "UPDATE about SET email='$ab_email', street='$ab_street',bar='$ab_bar',city='$ab_city',
            prov='$ab_prov',contact='$ab_contno',country='$ab_country',abt='$abt',
            pabt='$abt_pabt', sabt='$abt_sabt', mission='$abt_mis', vision='$abt_vis', name='$abt_name' 
            
            WHERE id=1";
            if ($link->query($sql) === TRUE) {
              
            } else {
              echo "Error updating record: " . $link->error;
            }


            
              

}

$sql = "SELECT * FROM about where id=1";
if($result = mysqli_query($link, $sql)){
  if(mysqli_num_rows($result) > 0){ 
      while($row = mysqli_fetch_array($result)){  
        $about_email= $row['email']  ;
        $about_contno= $row['contact']  ;
        $about_country=  $row['country']  ;
        $ab_street= $row['street'];
        $ab_bar= $row['bar'];
        $ab_city= $row['city'];
        $ab_prov= $row['prov'];
        $abt= $row['abt'];

        $abt_name=$row['name'];
        $abt_pabt=$row['pabt'];
        $abt_sabt=$row['sabt'];

        $abt_mis=$row['mission'];
        $abt_vis=$row['vision'];
          }
      mysqli_free_result($result);
  } else{
      echo "No records matching your query were found.";
  }
} else{
  echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}




// Close connection

                                       

?>

