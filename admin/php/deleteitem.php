<?php

session_start();
include("conn.php");
$id=$_GET['id'];

$sql="delete from items where item_id=$id";
if ($link->query($sql) === TRUE) {
      
    echo 1;

  } else {
    echo $link->error;
  }
