<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}

$btl->hasRight("action.add_downtime");
$layout= new Layout();

$layout->setTitle("");

$layout->set_menu("downtimes");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);

switch($_GET[downtime_type]) {
	case 1:
		$dt_hidden = 1;
		$dt_service = $_GET[service_id];
		$svc=bartlby_get_service_by_id($btl->RES, $_GET[service_id]);
		$ORCH_ID=$svc[orch_id]; //FIXME GET ORCH ID OF SERVER/SERVICE/..GROUP
		$dt_type = "Service (" .  $dt_service . ")";
	break;
	case 2:
		$dt_hidden = 2;
		$dt_service = $_GET[server_id];
		$dt_type = "Server (" .  $dt_service . ")";
		$svc=bartlby_get_server_by_id($btl->RES, $_GET[server_id]);
		$ORCH_ID=$svc[orch_id]; //FIXME GET ORCH ID OF SERVER/SERVICE/..GROUP
	break;
	case 3:
		$dt_hidden = 3;
		$dt_service = $_GET[servergroup_id];
		$dt_type = "Servergroup (" .  $dt_service . ")";
		$svc=bartlby_get_servergroup_by_id($btl->RES, $_GET[servergroup_id]);
		$ORCH_ID=$svc[orch_id]; //FIXME GET ORCH ID OF SERVER/SERVICE/..GROUP
	break;
	case 4:
		$dt_hidden = 4;
		$dt_service = $_GET[servicegroup_id];
		$dt_type = "Servicegroup (" .  $dt_service . ")";
		$svc=bartlby_get_servicegroup_by_id($btl->RES, $_GET[servicegroup_id]);
		$ORCH_ID=$svc[orch_id]; //FIXME GET ORCH ID OF SERVER/SERVICE/..GROUP
	break;
	
}


$optind=0;


$ov .= $layout->FormBox(
		array(
			0=>"Reason",
			1=>$layout->Field("downtime_notice", "text", "", "", "") . $layout->Field("action", "hidden", "add_downtime") . $layout->Field("service_id", "hidden", $dt_service)
		)
,true);
$ov .= $layout->FormBox(
		array(
			0=>"From",
			1=>$layout->Field("downtime_from", "text", date("m/d/Y 00:00", time()), "", "class='datetimepicker'")  . $layout->Field("downtime_type", "hidden", $dt_hidden)
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"To",
			1=>$layout->Field("downtime_to", "text", date("m/d/Y 23:59", time()), "", "class='datetimepicker'")
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Orchestra ID",
			1=>$layout->Field("orch_id", "text", $ORCH_ID)
		)
,true);

$ov .= $layout->FormBox(
			Array(
				0=>"Type",
				1=>$dt_type . $layout->Field("lappend", "hidden", $lappend) . $layout->Field("Subm", "button", "next->", "" ," onClick='xajax_AddDowntime(xajax.getFormValues(\"fm1\"))'")
			)
,true);

$title="add downtime";  
$content = "<span class=form-horizontal>" . $ov . "</span>";
$layout->create_box($title, $content);
	

$r=$btl->getExtensionsReturn("_PRE_add_downtime", $layout);


$layout->FormEnd();

$layout->boxes_placed[MAIN]=true;


$layout->display();