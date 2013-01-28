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
$layout->Table("100%");


switch($_GET[downtime_type]) {
	case 1:
		$dt_hidden = 1;
		$dt_service = $_GET[service_id];
		$dt_type = "Service (" .  $dt_service . ")";
	break;
	case 2:
		$dt_hidden = 2;
		$dt_service = $_GET[server_id];
		$dt_type = "Server (" .  $dt_service . ")";
	break;
	case 3:
		$dt_hidden = 3;
		$dt_service = $_GET[servergroup_id];
		$dt_type = "Servergroup (" .  $dt_service . ")";
	break;
	case 4:
		$dt_hidden = 4;
		$dt_service = $_GET[servicegroup_id];
		$dt_type = "Servicegroup (" .  $dt_service . ")";
	break;
	
}

$map = $btl->GetSVCMap();
$optind=0;
//$res=mysql_query("select srv.server_id, srv.server_name from servers srv, rights r where r.right_value=srv.server_id and r.right_key='server' and r.right_user_id=" . $poseidon->user_id);



$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Reason",
			1=>$layout->Field("downtime_notice", "text", "", "", "") . $layout->Field("action", "hidden", "add_downtime") . $layout->Field("service_id", "hidden", $dt_service)
		)
	)
,true);
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"From",
			1=>$layout->Field("downtime_from", "text", date("m/d/Y 00:00", time()), "", "class='datetimepicker'")  . $layout->Field("downtime_type", "hidden", $dt_hidden)
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"To",
			1=>$layout->Field("downtime_to", "text", date("m/d/Y 23:59", time()), "", "class='datetimepicker'")
		)
	)
,true);
$ov .= $layout->Tr(
	$layout->Td(
			Array(
				0=>"Type",
				1=>$dt_type . $layout->Field("lappend", "hidden", $lappend)
			)
		)

,true);

$title="add downtime";  
$content = "<table>" . $ov . "</table>";
$layout->push_outside($layout->create_box($title, $content));
	

$r=$btl->getExtensionsReturn("_PRE_add_downtime", $layout);


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