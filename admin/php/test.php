<?php

function sendmssg($name,$email,$sub,$mesage){
    $_SERVER['REQUEST_METHOD']="POST";
    $_POST['name']=$name;

    $_POST['email']=$email;
    
    $_POST['subject']=$sub;

    $_POST['message']=$mesage;
    include("./contactform/submit.php");

}
sendmssg("Benz","samson.benz18@gmail.com","obob","hdddddddddddddddddddddddddddddddddddi");