<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}

$layout= new Layout();

$layout->setTitle("");

$layout->set_menu("downtimes");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);
$layout->Table("100%");

$default=false;
$optind=0;
$btl->downtime_list_loop(function($dt, $shm) use(&$default) {
	global $_GET;
	if($dt[downtime_id] == $_GET[downtime_id]) {
		$default=$dt;	
	}

});


if($default == false) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

switch($default[downtime_type]) {
	
	case 1:
		$dt_type="Service";
		$dt_hidden=1;
		$btl->hasServerorServiceRight($default[service_id]);
	break;
	
	case 2:
		$dt_type="Server";	
		$dt_hidden=2;
		$btl->hasServerRight($default[service_id]);
	break;
	
	case 3:
		$dt_type="Servergroup";	
		$dt_hidden=3;
	break;
	
	case 4:
		$dt_type="Servicegroup";	
		$dt_hidden=2;
	break;
	
}




$btl->hasRight("action.modify_downtime");



$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Reason",
			1=>$layout->Field("downtime_notice", "text", $default[downtime_notice]) . $layout->Field("action", "hidden", "modify_downtime")
		)
	)
,true);
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"From",
			1=>$layout->Field("downtime_from", "text", date("m/d/Y H:i", $default[downtime_from]), "", "class='datetimepicker'")  .  $layout->Field("downtime_type", "hidden", $dt_hidden)
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"To",
			1=>$layout->Field("downtime_to", "text", date("m/d/Y H:i", $default[downtime_to]), "", "class='datetimepicker'")  . $layout->Field("downtime_id", "hidden", $_GET[downtime_id])
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Orchestra ID",
			1=>$layout->orchDropdown(true, $default[orch_id])
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
			Array(
				0=>"Type",
				1=>$dt_type . $layout->Field("service_id", "hidden", $default[service_id])
			)
		)

,true);

$title="modify downtime";  
$content = "<table>" . $ov . "</table>";
$layout->push_outside($layout->create_box($title, $content));
	

$r=$btl->getExtensionsReturn("_PRE_modify_downtime", $layout);


$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "button", "next->", "" ," onClick='xajax_AddDowntime(xajax.getFormValues(\"fm1\"))'")
					)
			)
		)

,false);


$layout->TableEnd();
$layout->FormEnd();
$layout->display();