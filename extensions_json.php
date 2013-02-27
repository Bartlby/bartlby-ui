<?php
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	
	$e=explode("?", $_GET[script]);
	$s = explode("&" , $e[1]);
	for($x=0; $x<count($s); $x++) {
		$a=explode("=", $s[$x]);
		$_GET[$a[0]]=$a[1];	
	}

	$ext = $_GET[extension];
	$func = $_GET[action];
	
	include_once("extensions/$ext/" .$ext . ".class.php");
	eval("\$clh = new " . $ext . "();");

	if(method_exists($clh, $func)) {
		eval("\$o = \$clh->" . $func. "();");
		echo json_encode($o);
	}
	
	
	
?>