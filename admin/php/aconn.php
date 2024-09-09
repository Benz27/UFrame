<?php
$adbhost = "";
$adbuser = "";
$adbpass = "";
$adbname = "";

$alink = mysqli_connect($adbhost, $adbuser, $adbpass, $adbname);

if($alink === false){
    die("Could not connect!" . mysqli_connect_error());
}
date_default_timezone_set("Asia/Manila");
?>