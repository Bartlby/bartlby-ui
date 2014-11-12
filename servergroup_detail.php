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
$layout->setMainTabName("Details");
if($_GET[all_servers] == 1) {
$layout->setTitle("Server List");	
}

$defaults=array();
$btl->servergroup_list_loop(function($grp, $shm) use (&$defaults) {
		global $_GET;
		if($grp[servergroup_id] == $_GET[servergroup_id]) {
			$defaults=$grp;
			return LOOP_BREAK;
		}
});

$qck = array();
$btl->service_list_loop(function($svc, $shm) use (&$qck){
	global $defaults, $_GET;
	
	
	if(strstr($defaults[servergroup_members], "|" . $svc[server_id] . "|") || $_GET[all_servers] == 1) {

		$qck[$svc[server_id]][$svc[current_state]]++;        
        $qck[$svc[server_id]][10]=$svc[server_id];
        $qck[$svc[server_id]][server_icon]=$svc[server_icon];
        $qck[$svc[server_id]][server_name]=$svc[server_name];
        if($svc[is_downtime] == 1) {
            $qck[$svc[server_id]][$svc[current_state]]--;
        	$qck[$svc[server_id]][downtime]++;
                                
    	}
    	if($svc[handled] == 1) {
            $qck[$svc[server_id]][$svc[current_state]]--;
        	$qck[$svc[server_id]][handled]++;
                                
    	}

    	if($svc[service_ack_current] == 2) {
    		$qck[$svc[server_id]][acks]++;        
	   		
                            
    	}
	}
});


if(!$defaults && !$_GET[all_servers]) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

//$servers=$btl->getSVCMap($btl->RES, NULL, NULL);


if($defaults["servergroup_notify"]==1) {
	$noti_en="<input type=checkbox class=switch checked disabled>";
} else {
	$noti_en="<input type=checkbox class=switch disabled>";
}
if($defaults["servergroup_active"]==1) {
	$server_en="<input type=checkbox class=switch checked disabled>";
} else {
	$server_en="<input type=checkbox class=switch  disabled>";
}

$services_found = array();
				
	
if($defaults[is_downtime] == 1) {

	$info_box_title='Downtime';  
	$core_content = "";
	$layout->create_box($info_box_title, $core_content, "service_detail_downtime_notice", array("service" => $defaults), "service_detail_downtime_notice");
	
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

if($triggers == "") $triggers = "all";

$info_box_title='ServerGroup Info';  

if($_GET[all_servers] != 1) {
$layout->create_box($info_box_title, $core_content, "servergroup_detail_servergroup_info", array(
										"servergroup" => $defaults,
										"" => $isup,
										"notify_enabled" => $noti_en,
										"servergroup_enabled" => $server_en,
										"servergroup_dead" => $defaults[servergroup_dead],
										
										"triggers" => $triggers
										),
			"servergroup_detail_servergroup_info");
}



			
			$qv_title='Members';  
			$layout->create_box($qv_title, $core_content,"servergroup_detail_members", array(
				'quick_view' => $qck
			), "quick_view");
	
$layout->create_box("Mass Actions", "", "mass_actions",
											array("a"=>"b")				
				,"service_list_mass_actions", false);
	

$r=$btl->getExtensionsReturn("_servergroupDetails", $layout);


$layout->OUT .= $btl->getServerGroupOptions($defaults, $layout, "btn-lg");




$layout->display("servergroup_detail");