<?php

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

