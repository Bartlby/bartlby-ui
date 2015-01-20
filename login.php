<?php
include "config.php";
include "layout.class.php";
include "bartlby-ui.class.php";

$_SESSION[username]=$_POST[login_username];
$_SESSION[password]=$_POST[password];
$_SESSION[instance_id] = $_POST[btl_instance_id];




$btl=new BartlbyUi($Bartlby_CONF);

//header("Location: overview.php");
?>