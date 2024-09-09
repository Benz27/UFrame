
<?php

include("conn.php");    
$q = $_GET['q'];    
$qu = $_GET['qu'];      


$sql="SELECT * FROM user where $qu='$q'";
$result=mysqli_query($link, $sql);
if($result && mysqli_num_rows($result) > 0){


$user_data=mysqli_fetch_assoc($result);
echo "1";      


}else{

    echo "0";
}
 
mysqli_close($link);
?>
