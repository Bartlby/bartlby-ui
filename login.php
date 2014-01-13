<?php

session_start();
$_SESSION[username]=$_POST[login_username];
$_SESSION[password]=$_POST[password];
sleep(1);

header("Location: overview.php");
?>