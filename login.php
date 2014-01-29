<?php

session_start();

$_SESSION[username]=$_POST[login_username];
$_SESSION[password]=$_POST[password];
$_SESSION[instance_id] = $_POST[btl_instance_id];


header("Location: overview.php");
?>