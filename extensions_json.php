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
	


	 $i=$btl->getOneExtensionReturn($ext, $func);
	 echo json_encode($i[out]);
	
?>