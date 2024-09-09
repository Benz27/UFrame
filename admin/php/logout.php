<?php
session_start();
if(isset($_SESSION['admin_user_id'])){ 
unset($_SESSION['admin_user_id']);
}
header("Location: ../login.html");
?>