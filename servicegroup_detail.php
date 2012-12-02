<?php
function dnl($i) {
	return sprintf("%02d", $i);
}

include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("main.servergroup_detail");
$layout= new Layout();
$layout->set_menu("main");
$layout->setTitle("ServiceGroup");

$servergroups=$btl->GetServiceGroups();
for($x=0; $x<count($servergroups); $x++) {
	if($servergroups[$x][servicegroup_id] == $_GET[servicegroup_id]) {
		$defaults=$servergroups[$x];
		break;	
	}
}

if(!$defaults) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}
$map=$btl->GetSVCMap();


if($defaults["servicegroup_notify"]==1) {
	$noti_en="true";
} else {
	$noti_en="false";
}
if($defaults["servicegroup_active"]==1) {
	$server_en="true";
} else {
	$server_en="false";
}




$info_box_title='ServiceGroup Info';  


$layout->create_box($info_box_title, $core_content, "servicegroup_detail_servicegroup_info", array(
										"servicegroup" => $defaults,
										"" => $isup,
										"notify_enabled" => $noti_en,
										"servicegroup_enabled" => $server_en
										
										),
			"servicegroup_detail_servicegroup_info");




while(list($k, $servs) = @each($map)) {
	$services_found=array();
	for($x=0; $x<count($servs); $x++) {
			if(strstr($defaults[servicegroup_members], "|" . $servs[$x][service_id] . "|")) {
				$svc_color=$btl->getColor($servs[$x][current_state]);
				$svc_state=$btl->getState($servs[$x][current_state]);
				$abc=$servs[$x][server_id];

				array_push($services_found, $servs[$x]);	
			}
	}
	$layout->create_box($cur_box_title, $cur_box_content, "server_box_" . $abc,
											array(
												"services" => $services_found,
												"state" => $svc_state,
												"color" => $svc_color,
												
												
											)
				
				,"service_list_element");
				
	
}
			


$r=$btl->getExtensionsReturn("_servicegroupDetails", $layout);

$layout->OUT .= $btl->getServiceGroupOptions($defaults, $layout);





$layout->display("servicegroup_detail");