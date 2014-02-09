<?
/*
storage layout

array 
	0 => array(element_info)
	2 => array(element_info)


*/
	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/OcL/OcL.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("ocl_view");
	$ocl = new OcL();
	
	
	
	$layout= new Layout();
	$layout->setTitle("OcL: Schedule");
	$layout->SetMainTabName("Schedule");
	
	
	

	$layout->AddScript("<link href='extensions/OcL/ocl.css' rel='stylesheet' />");
	$layout->AddScript("<script src='extensions/OcL/ocl.js'></script>");
	

	$layout->OUT .= "<div id='ocl_schedule'></div>";
		


	$layout->display();
	
	
	
?>