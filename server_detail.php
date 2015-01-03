<?php
function dnl($i) {
	return sprintf("%02d", $i);
}
function getGeoip($ip) {
	$fp=popen("geoiplookup $ip", "r");
	while(!feof($fp)) {
		$rmsg .= fgets($fp, 1024);	
	}
	$exi=pclose($fp);
	if($exi == 127) {
		return "(maybe you dont have 'geoiplookup' not installed or it is not in your PHP path)";
	} else {
		$a=explode(":",$rmsg);
		return $a[1];	
	}
		
}
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("main.server_detail");
$layout= new Layout();
	$layout->do_auto_reload=true;
$layout->set_menu("main");
$layout->setTitle("Actions");
$layout->setMainTabName("Details");
$defaults=bartlby_get_server_by_id($btl->RES, $_GET[server_id]);
$btl->hasServerRight($_GET[server_id]);

if(!$defaults) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

//$map=$btl->getSVCMap($btl->RES, NULL, NULL);
//$server_map=$map[$_GET[server_id]];




$isup="<span class='label label-success' style='font-size:25px;'>UP</span>";

$services_assigned="";


$dmp_info[0] = 0;
$dmp_info[1] = 0;
$dmp_info[2] = 0;
$is_up_down=-1;
$server_service_count=0;
$btl->service_list_loop(function($svc, $shm) use (&$dmp_info, &$server_service_count, &$is_up_down) {
	global $_GET;
	if($svc[server_id] != $_GET[server_id]) return LOOP_CONTINUE;
	$server_service_count++;
	if($svc[current_state] == 0) $is_up_down=1;
	$dmp_info[$svc[current_state]] += 1;
});



if($is_up_down < 0) 	$isup="<span class='label label-danger' class='font-size:25px;'>DOWN</span>";
	
	

while(list($k, $v) = @each($dmp_info)) {
		if($v <= 0) continue;
		$services_assigned .= "<a href='services.php?server_id=" . $_GET[server_id] . "&expect_state=" . $k . "'>" . $btl->getColorSpan($k) . "</A>: " .  $v . "<br>";
}

if($defaults["server_notify"]==1) {
	$noti_en="<input type=checkbox class=switch checked disabled>";
} else {
	$noti_en="<input type=checkbox class=switch disabled>";
}

if($defaults["server_enabled"]==1) {
	$server_en="<input type=checkbox class=switch checked disabled>";
} else {
	$server_en="<input type=checkbox class=switch disabled>";
}
$triggers = "";
if(strlen($defaults[enabled_triggers]) > 2) {
	$tr_array = explode("|", $defaults[enabled_triggers]);
	for($x=0; $x<count($tr_array); $x++) {
			if($tr_array[$x] != "") {
				$triggers .= $tr_array[$x] . ",";
			}
	}
	
	
}
if($triggers == "") $triggers="all";

$svc_type="UNKOWN";
if($defaults[default_service_type] == 1) {
	$svc_type="Active";
}

if($defaults[default_service_type] == 2) {
	$svc_type="Passive";
}

if($defaults[default_service_type] == 3) {
	$svc_type="Group";
}
if($defaults[default_service_type] == 4) {
	$svc_type="Local";
}
if($defaults[default_service_type] == 5) {
	$svc_type="SNMP";
}
if($defaults[default_service_type] == 6) {
	$svc_type="NRPE";
}
if($defaults[default_service_type] == 7) {
	$svc_type="NRPE(ssl)";
}

if($defaults[default_service_type] == 8) {
	$svc_type="AgentV2";
}
if($defaults[default_service_type] == 9) {
	$svc_type="AgentV2(no-SSL)";
}
if($defaults[default_service_type] == 10) {
	$svc_type="SSH";
}





for($x=0; $x<count($defaults[servergroups]); $x++) {
	if($defaults[servergroups][$x][servergroup_active] == 0) {
		$server_en .= " <span class='label label-primary'>servergroup disabled</span> (<a href='servergroup_detail.php?servergroup_id=" . $defaults[servergroups][$x][servergroup_id] . "'>" . $defaults[servergroups][$x][servergroup_name] . "</A>)";
	}
	if($defaults[servergroups][$x][servergroup_notify] == 0) {
		$noti_en .= " <span class='label label-primary'>servergroup disabled</span> (<a href='servergroup_detail.php?servergroup_id=" . $defaults[servergroups][$x][servergroup_id] . "'>" . $defaults[servergroups][$x][servergroup_name] . "</A>)";
	}
}


$info_box_title='Server Info';  

$layout->create_box($info_box_title, $core_content, "server_detail_server_info", array(
										"service" => $defaults,
										"isup" => $isup,
										"server_enabled" => $server_en,
										"default_service_type" => $svc_type
										),
			"server_detail_server_info", false,true);



if($defaults[server_ssh_keyfile] != " ") {
	$info_box_title='SSH Options';  
	$layout->create_box($info_box_title, $core_content, "service_detail_ssh_info", array(
											"service" => $defaults),
				"service_detail_ssh_info",false,true);
}			
			
$info_box_title='Services';  
$layout->create_box($info_box_title, $core_content, "server_detail_services", array(
									"services_assigned" => $services_assigned,
									"server_service_count" => $server_service_count									
									)
			, "server_detail_services", false,true);
	

if(is_array($defaults[groups])) {
	
	$layout->create_box("Group", $core_content, "server_detail_server_group_info", array(
											"server_groups" => $defaults[groups]
											
											),
				"server_detail_server_group_info", false,true);
	
}


$info_box_title='Notifications';  
$layout->create_box($info_box_title, $core_content, "server_detail_notifications", array(
											"notify_enabled" => $noti_en,
											
											"triggers" => $triggers, 
										
											
											
											)
											
		, "server_detail_notifications", false, true);

$info_box_title='Timing';  
$layout->create_box($info_box_title, $core_content, "server_detail_timing", array(
											"service" => $defaults,
											"service_ms" => $svcMS,
											"service_delay" => $svcDEL,
											"currently_running" => $currun,
											"check_plan" => $plan_box
											)
											
		, "server_detail_timing", false, true);

if(strlen($defaults[web_hooks]) > 3) {
	$info_box_title='Web Hooks';  
	$layout->create_box($info_box_title, $core_content, "server_detail_web_hooks", array(
												"server" => $defaults										
												)
												
			, "server_detail_web_hooks", false, true);

}



$info_box_title='Orchestra/Cluster';  
$layout->create_box($info_box_title, $core_content, "server_detail_orch", array(
											"service" => $defaults,
											"renotify" => $renot_en,
											"escalate" => $escal_en,
											"server_notifications" => $server_noti_enabled,
											"notify_enabled" => $noti_en,
											"triggers" => $triggers
										
											
											
											)
											
		, "server_detail_orch", false, true);





if($defaults[is_downtime] == 1) {
	$info_box_title='Downtime';  
	$core_content = "";
	$layout->create_box($info_box_title, $core_content, "service_detail_downtime_notice", array("service" => $defaults), "service_detail_downtime_notice", false,true);
	
}

$layout->create_box("Mass Actions", "", "mass_actions",
											array("a"=>"b")				
				,"service_list_mass_actions", false);
	



$r=$btl->getExtensionsReturn("_serverDetail", $layout);

$layout->OUT .= $btl->getServerOptions($defaults, $layout, "btn-lg");





$layout->display("server_detail");
