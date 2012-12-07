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
$layout->setTitle("ServerGroup");

if($_GET[all_servers] == 1) {
$layout->setTitle("Server List");	
}

$servergroups=$btl->GetServerGroups();
for($x=0; $x<count($servergroups); $x++) {
	if($servergroups[$x][servergroup_id] == $_GET[servergroup_id]) {
		$defaults=$servergroups[$x];
		break;	
	}
}

if(!$defaults && !$_GET[all_servers]) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$servers=$btl->getSVCMap($btl->CFG, NULL, NULL);


if($defaults["servergroup_notify"]==1) {
	$noti_en="true";
} else {
	$noti_en="false";
}
if($defaults["servergroup_active"]==1) {
	$server_en="true";
} else {
	$server_en="false";
}

$services_found = array();
while(list($k,$v)=@each($servers)) {
		$x=$k;
		
		
		for($y=0; $y<count($v); $y++) {
			
			if(strstr($defaults[servergroup_members], "|" . $v[$y][server_id] . "|") || $_GET[all_servers] == 1) {
				
				$qck[$v[$y][server_id]][$v[$y][current_state]]++;	
				$qck[$v[$y][server_id]][10]=$v[$y][server_id];
				$qck[$v[$y][server_id]][server_icon]=$v[$y][server_icon];
				$qck[$v[$y][server_id]][server_name]=$v[$y][server_name];
				if($v[$y][is_downtime] == 1) {
					$qck[$v[$y][server_id]][$v[$y][current_state]]--;
					$qck[$v[$y][server_id]][downtime]++;
					
				}
				
				$svc_color=$btl->getColor($v[$y][current_state]);
				$svc_state=$btl->getState($v[$y][current_state]);
				
				if($v[$y][is_downtime] == 1) {
					$svc_state="Downtime";
					$svc_color="silver";	
				}
				
				$v[$y][color]=$svc_color;
				$v[$y][state_readable]=$svc_state;
				
				array_push($services_found, $v[$y]);
			
			}
		
			
			$abc=$v[$y][server_id];
		
		
		}
			
		
		
		
	}
	

	$layout->create_box($cur_box_title, $cur_box_content, "server_box_" . $abc,
											array(
												"services" => $services_found,
												"state" => $svc_state,
												"color" => $svc_color,
												
												
											)
				
				,"service_list_element");
				
	
if($defaults[is_downtime] == 1) {

	$info_box_title='Downtime';  
	$core_content = "";
	$layout->create_box($info_box_title, $core_content, "service_detail_downtime_notice", array("service" => $defaults), "service_detail_downtime_notice");
	
}

$info_box_title='ServerGroup Info';  

if($_GET[all_servers] != 1) {
$layout->create_box($info_box_title, $core_content, "servergroup_detail_servergroup_info", array(
										"servergroup" => $defaults,
										"" => $isup,
										"notify_enabled" => $noti_en,
										"servergroup_enabled" => $server_en
										
										),
			"servergroup_detail_servergroup_info");
}



			
			$qv_title='Members';  
			$layout->create_box($qv_title, $core_content,"servergroup_detail_members", array(
				'quick_view' => $qck
			), "quick_view");
	


$r=$btl->getExtensionsReturn("_servergroupDetails", $layout);


if($_GET[all_servers] != 1) {
	$layout->OUT .= $btl->getServerGroupOptions($defaults, $layout);
}




$layout->display("servergroup_detail");