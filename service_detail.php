<?
function dnl($i) {
	return sprintf("%02d", $i);
}
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("main.service_detail");
$layout= new Layout();
$layout->do_auto_reload=true;
$layout->set_menu("main");
$layout->setTitle("Actions");
$layout->setMainTabName("Details");
if($_GET[service_id]) {
	$_GET[service_place] = $btl->findSHMPlace($_GET[service_id]);	
}




$defaults=bartlby_get_service($btl->RES, $_GET[service_place]);


$btl->hasServerorServiceRight($defaults[service_id]);

if(!$defaults) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

if(!$btl->hasRight("view_service_output", false)) {
	$defaults[current_output] = "you are missing: view_service_output right";	
} 



$svc_color=$btl->getColor($defaults[current_state]);
$svc_state=$btl->getColorSpan($defaults[current_state]);

switch($defaults[service_ack_current]) {
	
	case 2:
		$needs_ack="outstanding <input type=button class='btn btn-success' value='Acknowledge this problem' onClick=\"document.location.href='ack_service.php?service_id=" . $defaults[service_id]  . "';\">";
	break;
}
if($defaults[service_ack_enabled] == 1) {
		$needs_ack = "<input type=checkbox class='switch'  disabled checked> $needs_ack";
} else {
	$needs_ack = "<input type=checkbox class='switch'  disabled >";
}



if($defaults[service_type] == 1) {
	$svc_type="Active";
}

if($defaults[service_type] == 2) {
	$svc_type="Passive";
}

if($defaults[service_type] == 3) {
	$svc_type="Group";
}
if($defaults[service_type] == 4) {
	$svc_type="Local";
}
if($defaults[service_type] == 5) {
	$svc_type="SNMP";
}
if($defaults[service_type] == 6) {
	$svc_type="NRPE";
}
if($defaults[service_type] == 7) {
	$svc_type="NRPE(ssl)";
}

if($defaults[service_type] == 8) {
	$svc_type="AgentV2";
}
if($defaults[service_type] == 9) {
	$svc_type="AgentV2(no-SSL)";
}
if($defaults[service_type] == 10) {
	$svc_type="SSH";
}

if($defaults[service_type] == 11) {
	$svc_type="TRAP";
}
if($defaults[service_type] == 12) {
	$svc_type="JSON";
}

if($defaults["notify_enabled"]==1) {
	$noti_en="<input type=checkbox class='switch'  disabled checked>";
} else {
	$noti_en="<input type=checkbox class='switch'  disabled>";
}

if($defaults["script_enabled"]==1) {
	$script_enabled="<input type=checkbox class='switch'  disabled checked>";
} else {
	$script_enabled="<input type=checkbox class='switch'  disabled>";
}

if($defaults["service_active"]==1) {
	$serv_en="<input type=checkbox class='switch'  disabled checked>";
} else {
	$serv_en="<input type=checkbox class='switch'  disabled>";
}

switch($defaults["fires_events"]) {
		case 0:
			$events_en="false";
		break;
		case 1:
			$events_en="true (HARD)";
		break;
		case 2:
			$events_en="true (SOFT)";
		break;
		case 3:
			$events_en="true (HARD|SOFT)";
		break;
}








//echo $defaults[last_notify_send] . "<br>";

if( $defaults[service_delay_sum] > 0 && $defaults[service_delay_count] > 0) {
	$svcDEL=round($defaults[service_delay_sum] / $defaults[service_delay_count], 2);
} else {
	$svcDEL=0;	
}



if( $defaults[service_time_sum] > 0 && $defaults[service_time_count] > 0) {
	$svcMS=round($defaults[service_time_sum] / $defaults[service_time_count], 2);
} else {
	$svcMS=0;	
}


$server_enabled="";

if($defaults[server_enabled] != 1) {
	$server_enabled="<span class='label label-primary'>server disabled</span>";	
}

$server_noti_enabled="";

if($defaults[server_notify] != 1) {
	$server_noti_enabled="<span class='label label-primary'>disabled via server</span>";	
}


//Check if checks/notify's are disabled through a group
for($x=0; $x<count($defaults[servicegroups]); $x++) {
	if($defaults[servicegroups][$x][servicegroup_active] == 0) {
		$server_enabled = ";<i>servicegroup disabled (<a href='servicegroup_detail.php?servicegroup_id=" . $defaults[servicegroups][$x][servicegroup_id] . "'>" . $defaults[servicegroups][$x][servicegroup_name] . "</A>)";
	}
	if($defaults[servicegroups][$x][servicegroup_notify] == 0) {
		$server_noti_enabled = ";<i>servicegroup disabled (<a href='servicegroup_detail.php?servicegroup_id=" . $defaults[servicegroups][$x][servicegroup_id] . "'>" . $defaults[servicegroups][$x][servicegroup_name] . "</A>)";
	}
}

for($x=0; $x<count($defaults[servergroups]); $x++) {
	if($defaults[servergroups][$x][servergroup_active] == 0) {
		$server_enabled = ";<i>servergroup disabled (<a href='servergroup_detail.php?servergroup_id=" . $defaults[servergroups][$x][servergroup_id] . "'>" . $defaults[servergroups][$x][servergroup_name] . "</A>)";
	}
	if($defaults[servergroups][$x][servergroup_notify] == 0) {
		$server_noti_enabled = ";<i>servergroup disabled (<a href='servergroup_detail.php?servergroup_id=" . $defaults[servergroups][$x][servergroup_id] . "'>" . $defaults[servergroups][$x][servergroup_name] . "</A>)";
	}
}


if($defaults[check_starttime] != 0) {
	$currun=date("d.m.Y H:i:s", $defaults[check_starttime]) . " (PID: $defaults[check_is_running] )";
} else {
	$currun="<i>Currently not running</i>";	
}

$plan_box = $btl->resolveServicePlan($defaults[exec_plan]);



if($defaults[renotify_interval] != 0) {
		$renot_en="every " . $defaults[renotify_interval] . " runs ( " . $btl->intervall(($defaults[check_interval]*$defaults[renotify_interval])) . ") ";
} else {
		$renot_en="not enabled";
}
if($defaults[escalate_divisor] != 0) {
		$escal_en="every " . $defaults[escalate_divisor] . " runs (" . $btl->intervall(($defaults[check_interval]*$defaults[escalate_divisor])) . ")";
} else {
		$escal_en="not enabled";
}

$triggers = $btl->getTriggerString( $defaults[enabled_triggers] );
$handled = "UNHANDLED";
if($defaults[handled] == 1) $handled = "HANDLED";



$info_box_title='Timing';  
$layout->create_box($info_box_title, $core_content, "service_detail_timing", array(
											"service" => $defaults,
											"service_ms" => $svcMS,
											"service_delay" => $svcDEL,
											"currently_running" => $currun,
											"check_plan" => $plan_box
											)
											
		, "service_detail_timing", false, true);


$info_box_title='Notifications';  
$layout->create_box($info_box_title, $core_content, "service_detail_notifications", array(
											"service" => $defaults,
											"renotify" => $renot_en,
											"escalate" => $escal_en,
											"server_notifications" => $server_noti_enabled,
											"notify_enabled" => $noti_en,
											"triggers" => $triggers
										
											
											
											)
											
		, "service_detail_notifications", false, true);

$info_box_title='Orchestra/Cluster';  
$layout->create_box($info_box_title, $core_content, "service_detail_orch", array(
											"service" => $defaults,
											"renotify" => $renot_en,
											"escalate" => $escal_en,
											"server_notifications" => $server_noti_enabled,
											"notify_enabled" => $noti_en,
											"triggers" => $triggers
										
											
											
											)
											
		, "service_detail_orch", false, true);

if(strlen($defaults[script]) > 3) {
	$info_box_title='Script';  
	$layout->create_box($info_box_title, $core_content, "service_detail_script", array(
												"service" => $defaults,
												"script_enabled" => $script_enabled,
												)
												
			, "service_detail_script", false, false);

}


$info_box_title='History';  
$layout->create_box($info_box_title, $core_content, "service_detail_service_history", array(
											"service" => $defaults,											
											)											
		, "service_detail_service_history", false, false);

$layout->Tab("History", $layout->disp_box("service_detail_service_history"));



$info_box_title='Service Info';  
$layout->create_box($info_box_title, $core_content, "service_detail_service_info", array(
											"service" => $defaults,
											"service_type" => $svc_type,
											"map" => $map,
											"server_enabled" => $server_enabled,
											"server_enabled" => $server_enabled,
											"service_enabled" => $serv_en,
											"fires_events" => $events_en,
											
											"needs_ack" => $needs_ack,
											"color" => $svc_color,
											"state" => "<span style='font-size:25px'>" . $svc_state . "</span>",
											"handled" => $handled,
											"dead_marker" => $btl->resolveDeadMarker($defaults[server_dead])
											)
											
		, "service_detail_service_info", false, true);
			
if(is_array($defaults[servicegroups])) {

	$info_box_title='Group Info';  
	$layout->create_box($info_box_title, $core_content, "service_detail_group_info", array(
												"service_groups" => $defaults[servicegroups]
				)
												
			, "service_detail_group_info", false, true);
}
if(file_exists("gauglets/" . $defaults[plugin]  . ".php") && ($defaults[service_type] == 2 || $defaults[service_type] == 11 || $defaults[service_type] == 10  || $defaults[service_type] == 1 || $defaults[service_type] == 4 || $defaults[service_type] == 6 || $defaults[service_type] == 7|| $defaults[service_type] == 8  || $defaults[service_type] == 9)) {
	
	
	
	
	$info_box_title='Gauglets';  
	$layout->create_box($info_box_title, $core_content, "service_detail_gauglets", array(
												"gauglets_path" => "gauglets/" . $defaults[plugin]  . ".php",
												"service" => $defaults
				)
												
			, "service_gauglets");
}

if($defaults[is_downtime] == 1) {
	$info_box_title='Downtime';  
	$core_content = "";
	$layout->create_box($info_box_title, $core_content, "service_detail_downtime_notice", array("service" => $defaults), "service_detail_downtime_notice", false,true);
	
}

$info_box_title='Last Output';  
$layout->create_box($info_box_title, $core_content, "service_detail_status_text", array("service" => $defaults), "service_detail_status_text", false, true);

if($defaults[service_type] == 2 || $defaults[service_type] == 10  || $defaults[service_type] == 1 || $defaults[service_type] == 4 || $defaults[service_type] == 6 || $defaults[service_type] == 7|| $defaults[service_type] == 8  || $defaults[service_type] == 9){
	$info_box_title='Plugin settings';  
	$core_content = "";
	
	$layout->create_box($info_box_title, $core_content, "service_detail_plugin_info", array("service" => $defaults), "service_detail_plugin_info", false,true);
}


if($defaults[service_type] == 5){
	if($defaults[snmp_type]  == 1) {
		$snmp_type = "Lower";
	} 
	if($defaults[snmp_type]  == 2) {
		$snmp_type = "Greater";
	} 
	if($defaults[snmp_type]  == 3) {
		$snmp_type = "Equal";
	}
	if($defaults[snmp_type]  == 4) {
		$snmp_type = "Not-Equal";
	}
	if($defaults[snmp_type]  == 5) {
		$snmp_type = "Contains";
	}
	$info_box_title='SNMP Service';  
	$layout->create_box($info_box_title, $core_content, "service_detail_snmp", array("service"=>$defaults, "snmp_type"=>$snmp_type), "service_detail_snmp", false,true);
}


if($defaults[service_type] == 2){
	
		
		$ibox[0][c]="green";
		$ibox[0][v]=0;	
		$ibox[0][k]="OK";
		$ibox[1][c]="orange";        
		$ibox[1][v]=1;	  
		$ibox[1][k]="Warning";
		$ibox[2][c]="red";        
		$ibox[2][v]=2;	  
		$ibox[2][k]="Critical";
		
		$ibox[$defaults[current_state]][s]=1;
		$state_dropdown=$layout->DropDown("passive_state", $ibox);
		$info_box_title='Passive Service';  
		$layout->create_box($info_box_title, $core_content, "service_detail_passive", array("service" => $defaults, "state_dropdown" => $state_dropdown), "service_detail_passive", false,true);
}
if($defaults[service_type] == 3){
	$info_box_title='Group Service';  
	$layout->create_box($info_box_title, $core_content, "service_detail_group_check", array("service" => $defaults), "service_detail_group_check", false,true);
}

$ibox[0][c]="green";
$ibox[0][v]=0;
$ibox[0][k]="OK";
$ibox[1][c]="orange";
$ibox[1][v]=1;
$ibox[1][k]="Warning";
$ibox[2][c]="red";
$ibox[2][v]=2;
$ibox[2][k]="Critical";

$ibox[3][c]="grey";
$ibox[3][v]=4;
$ibox[3][k]="Info";






$ibox[$defaults[current_state]][s]=1;

$state_dropdown=$layout->DropDown("passive_state", $ibox);
$info_box_title='Manual state change';  
$layout->create_box($info_box_title, $core_content, "service_detail_manual", array("service" => $defaults, "state_dropdown" => $state_dropdown), "service_detail_manual",false,false);


$odefaults=$defaults;
$r=$btl->getExtensionsReturn("_serviceDetail", $layout);

$defaults=$odefaults;

$layout->OUT .= $btl->getserviceOptions($defaults, $layout, "btn-lg");


$defaults[svc_options]=$btl->getserviceOptions($defaults, $layout);
$defaults[svc_state]=$svc_state;
$defaults[svc_color]=$svc_color;
$layout->SVC_DETAIL=$defaults;


$layout->display("service_detail");

