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




			
			


$r=$btl->getExtensionsReturn("_servicegroupDetails", $layout);

$layout->OUT .= $btl->getServiceGroupOptions($defaults, $layout);





$layout->display("servicegroup_detail");