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
$layout->setMainTabName("Details");
$defaults=array();
$btl->servicegroup_list_loop(function($grp, $shm) use (&$defaults) {
		global $_GET;
		if($grp[servicegroup_id] == $_GET[servicegroup_id]) {
			$defaults=$grp;
			return LOOP_BREAK;
		}
});



if(!$defaults) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}



if($defaults["servicegroup_notify"]==1) {
	$noti_en="<input type=checkbox class=switch checked disabled>";
} else {
	$noti_en="<input type=checkbox class=switch disabled>";
}
if($defaults["servicegroup_active"]==1) {
	
	$server_en="<input type=checkbox class=switch checked disabled>";
} else {
	
	$server_en="<input type=checkbox class=switch disabled>";
}


$triggers = $btl->getTriggerString( $defaults[enabled_triggers] );

$info_box_title='ServiceGroup Info';  


$layout->create_box($info_box_title, $core_content, "servicegroup_detail_servicegroup_info", array(
										"servicegroup" => $defaults,
										"" => $isup,
										"notify_enabled" => $noti_en,
										"servicegroup_enabled" => $server_en,
										"servicegroup_dead" => $defaults[servicegroup_dead],
										
										"triggers" => $triggers
										
										),
			"servicegroup_detail_servicegroup_info");





if($defaults[is_downtime] == 1) {

	$info_box_title='Downtime';  
	$core_content = "";
	$layout->create_box($info_box_title, $core_content, "service_detail_downtime_notice", array("service" => $defaults), "service_detail_downtime_notice");
	
}

					
$layout->create_box("Mass Actions", "", "mass_actions",
											array("a"=>"b")				
				,"service_list_mass_actions", false);
	

$r=$btl->getExtensionsReturn("_servicegroupDetails", $layout);

$layout->OUT .= $btl->getServiceGroupOptions($defaults, $layout, "btn-lg");





$layout->display("servicegroup_detail");