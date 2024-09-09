<?php

session_start();
if(isset($_SESSION['track_id'])){
    unset($_SESSION['track_id']);
};

if(!isset($_SESSION['track_id'])){
    echo 1;
};