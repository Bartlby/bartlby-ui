<?php
	include "config.php";
	
	$e=explode("?", $_GET[script]);
	$s = explode("&" , $e[1]);
	for($x=0; $x<count($s); $x++) {
		$a=explode("=", $s[$x]);
		$_GET[$a[0]]=$a[1];	
	}
	$fname = "extensions/"  . $e[0];
	$path_info = realpath($fname);
	$cwd = getcwd() . "/extensions/"  . $e[0];
	
	if($path_info != $cwd) {
		echo "RFI detected exit...";
		exit;
	}
	include("extensions/"  . $e[0]);
?>