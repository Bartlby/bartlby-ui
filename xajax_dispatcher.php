<?
$do_not_merge_post_get=true;
include "layout.class.php";
include "bartlby-ui.class.php";
include "config.php";


$btl=new BartlbyUi($Bartlby_CONF);
$layout = new Layout();
$layout->setTheme(bartlby_config(getcwd() . "/ui-extra.conf", "theme"));

for($z=0; $z<count($layout->deprecated); $z++) {
			$depre .= '<div class="alert alert-error">
							<button type="button" class="close" data-dismiss="alert">×</button>
							Deprecated INFO: <strong>' .  $layout->deprecated[$z] . '</strong>

						</div>';

}
				
echo $depre;		
$xajax->processRequests();




function updateServiceDetail($svc_idx) {
	global $layout, $btl;
	$res=new xajaxResponse();
	
	$defaults=bartlby_get_service($btl->RES, $svc_idx);
	$svc_color=$btl->getColor($defaults[current_state]);
	$svc_state=$btl->getState($defaults[current_state]);

	if($defaults[check_starttime] != 0) {
		$currun=date("d.m.Y H:i:s", $defaults[check_starttime]) . " (PID: $defaults[check_is_running] )";
	} else {
		$currun="<i>Currently not running</i>";	
	}
	
	
	$res->AddAssign("service_status", "innerHTML", $defaults[service_retain_current] . " / " . $defaults[service_retain]);
	$res->AddAssign("service_next_check", "innerHTML", date("d.m.Y H:i:s", $defaults[last_check]+$defaults[check_interval]));
	$res->AddAssign("service_last_check", "innerHTML", date("d.m.Y H:i:s", $defaults[last_check]));
	$res->AddAssign("service_new_server_text", "innerHTML",  $defaults[new_server_text]);
	$res->AddAssign("service_currently_running", "innerHTML", $currun);
	$res->AddAssign("service_last_notify_send", "innerHTML", date("d.m.Y H:i:s", $defaults[last_notify_send]));
	$res->AddAssign("service_current_state", "innerHTML", '<font color="' .  $svc_color . '">' . $svc_state . '</font>');
	return $res;
	
}

$xajax->registerFunction("bulkForce");
$xajax->registerFunction("bulkEnableChecks");
$xajax->registerFunction("bulkEnableNotifys");
$xajax->registerFunction("bulkDisableChecks");
$xajax->registerFunction("bulkDisableNotifys");

function idToInt($ids) {
	
	for($x=0; $x<count($ids); $x++) {
		$ids[$x]=(int)$ids[$x];
	}
	return $ids;
}
function bulkEnableChecks($ids) {
	global $btl, $layout;
	$res = new xajaxresponse();
	$ids=idToInt($ids);
	
	if(count($ids) == 0) {
		$res->AddScript('noty({"text":"No Service Selected","timeout": 600, "layout":"center","type":"warning","animateOpen": {"opacity": "show"}})');
		return $res;
	}
	if(function_exists("bartlby_bulk_service_active")) {
		$counter = bartlby_bulk_service_active($btl->RES,$ids, 1,1);
	}
	$res->AddScript('noty({"text":"(' . $counter . ') Selected Services Enabled","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
	for($x=0; $x<count($ids); $x++) {
			$res->AddAssign("service_" . $ids[$x], "src", "themes/" . $layout->theme . "/images/enabled.gif");
	}
	
	return $res;
	
	
	

}
function bulkDisableChecks($ids) {
	global $btl, $layout;
	$res = new xajaxresponse();
	$ids=idToInt($ids);
	
	if(count($ids) == 0) {
		$res->AddScript('noty({"text":"No Service Selected","timeout": 600, "layout":"center","type":"warning","animateOpen": {"opacity": "show"}})');
		return $res;
	}
	if(function_exists("bartlby_bulk_service_active")) {
		$counter=bartlby_bulk_service_active($btl->RES,$ids, 0,1);
	}
	$res->AddScript('noty({"text":"(' . $counter . ') Selected Services Disabled","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
	for($x=0; $x<count($ids); $x++) {
			$res->AddAssign("service_" . $ids[$x], "src", "themes/" . $layout->theme . "/images/diabled.gif");
	}
	
	return $res;	
}
function bulkEnableNotifys($ids) {
	global $btl, $layout;
	$res = new xajaxresponse();
	$ids=idToInt($ids);
	
	if(count($ids) == 0) {
		$res->AddScript('noty({"text":"No Service Selected","timeout": 600, "layout":"center","type":"warning","animateOpen": {"opacity": "show"}})');
		return $res;
	}
	if(function_exists("bartlby_bulk_service_notify")) {
		$counter=bartlby_bulk_service_notify($btl->RES,$ids, 1,1);
	}
	$res->AddScript('noty({"text":"(' . $counter . ') Selected Services Notifications Enabled","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
	for($x=0; $x<count($ids); $x++) {
		$res->AddAssign("trigger_" . $ids[$x], "src", "themes/" . $layout->theme . "/images/trigger.gif");
	}
	
	
	return $res;	
}
function bulkDisableNotifys($ids) {
	global $btl, $layout;
	$res = new xajaxresponse();
	$ids=idToInt($ids);
	
	if(count($ids) == 0) {
		$res->AddScript('noty({"text":"No Service Selected","timeout": 600, "layout":"center","type":"warning","animateOpen": {"opacity": "show"}})');
		return $res;
	}
	if(function_exists("bartlby_bulk_service_notify")) {
		$counter = bartlby_bulk_service_notify($btl->RES, $ids, 0,1);
	}
	$res->AddScript('noty({"text":"(' . $counter . ') Selected Services Notifications Disabled","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
	
	for($x=0; $x<count($ids); $x++) {
		$res->AddAssign("trigger_" . $ids[$x], "src", "themes/" . $layout->theme . "/images/notrigger.gif");
	}
	
	
	return $res;	
}
function bulkForce($ids) {
	global $btl;
	$ids=idToInt($ids);
	
	$res = new xajaxresponse();
	
	if(count($ids) == 0) {
		$res->AddScript('noty({"text":"No Service Selected","timeout": 600, "layout":"center","type":"warning","animateOpen": {"opacity": "show"}})');
		return $res;
	}
	if(function_exists("bartlby_bulk_force_services")) {
		$counter=bartlby_bulk_force_services($btl->RES, $ids);
	}
	$res->AddScript('noty({"text":"(' . $counter . ') Selected Services Forced","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
	return $res;	
}

function setWorkerState($worker_id, $worker_state) {
	//Set worker ID to state -> STATE
		global $layout, $btl;
		$res=new xajaxResponse();
		
		//get shm id
		$servs=$btl->GetWorker();
		$optind=0;
		while(list($k, $v) = @each($servs)) {
				if($v[worker_id] == $worker_id) {
						$shm_place=$v[shm_place];
				}
		}
		
		bartlby_set_worker_state($btl->RES, $shm_place, $worker_state);
		
		
		
		switch( $worker_state) {
			case 0:
			$hrstate = "Inactive";
			break;
			case 1:
			$hrstate = "Active";	
			break;
			case 2:
			$hrstate = "Standby";
			break;
			default:
			$hrstate="unkown";
		}
		
		
		$res->AddAssign("wstate" . $worker_id, "innerHTML", "State set to: $hrstate ");
		
		return $res;
}

function toggle_extension($ext) {
	global $layout;
	//FIXME Change to Button :)
	$res=new xajaxResponse();
	$fn = "extensions/" . $ext . ".disabled";
	if(!file_exists($fn)) {
		@touch($fn);
		//enable	
		$res->AddAssign("extension_img_" . $ext, "src", "themes/" . $layout->theme . "/images/extension_disable.gif");
		
		$res->AddAssign("extension_button_" . $ext, "className", "btn btn-mini btn-danger");
		$res->AddAssign("extension_button_" . $ext, "innerHTML", "Disabled");
		//$res->AddAssign("extension_img_" . $ext, "title", "enable extension");
	} else {
		@unlink($fn);
		$res->AddAssign("extension_img_" . $ext, "src", "themes/" . $layout->theme . "/images/extension_enable.gif");
		
		$res->AddAssign("extension_button_" . $ext, "className", "btn btn-mini btn-success");
		$res->AddAssign("extension_button_" . $ext, "innerHTML", "Enabled");
		//$res->AddAssign("extension_img_" . $ext, "title", "disable extension");
		//disable extension_disable.gif
	}
	return $res;
}


function toggle_servicegroup_notify_check($service_id, $service_id1) {
	global $btl;
	global $layout;
	$res = new xajaxresponse();
	
		
			$btl->servicegroup_list_loop(function($srvgrp, $shm) use(&$defaults, &$service_id) {
				if($srvgrp[servicegroup_id] == $service_id) {
					$defaults=$srvgrp;
					$defaults[shm_place]=$shm;
					return LOOP_BREAK;	
				}
			});
			
			$cur=bartlby_toggle_servicegroup_notify($btl->RES, $defaults[shm_place], 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[service_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("trigger_" . $service_id, "src", "themes/" . $layout->theme . "/images/trigger.gif");
				//$res->AddAssign("trigger_" . $service_id, "title", "disable notifications");
			} else {
				//$res->addAlert("Check disabled on:" . $gsm[service_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("trigger_" . $service_id, "src", "themes/" . $layout->theme . "/images/notrigger.gif");
				//$res->AddAssign("trigger_" . $service_id, "title", "enable trigger");
			}
			
			
			
			
		
	
	
	return $res;
}


function toggle_servicegroup_check($service_id, $service_id1) {
	global $btl;
	global $layout;
	$res = new xajaxresponse();
	
		
			$btl->servicegroup_list_loop(function($srvgrp, $shm) use(&$defaults, &$service_id) {
				if($srvgrp[servicegroup_id] == $service_id) {
					$defaults=$srvgrp;
					$defaults[shm_place]=$shm;
					return LOOP_BREAK;	
				}
		});
			
			$cur=bartlby_toggle_servicegroup_active($btl->RES, $defaults[shm_place], 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[service_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("servicegroup_" . $service_id, "src", "themes/" . $layout->theme . "/images/enabled.gif");
				//$res->AddAssign("service_" . $service_id, "title", "Disable Checks");
			} else {
				//$res->addAlert("Check disabled on:" . $gsm[service_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("servicegroup_" . $service_id, "src", "themes/" . $layout->theme . "/images/diabled.gif");
				//$res->AddAssign("service_" . $service_id, "title", "Enable Checks");
			}
			
			
			
			
		
	
	
	return $res;
}


function toggle_servergroup_notify_check($server_id, $service_id) {
	global $btl;
	global $layout;
	$res = new xajaxresponse();
	
		$btl->servergroup_list_loop(function($srvgrp, $shm) use(&$defaults, &$server_id) {
				if($srvgrp[servergroup_id] == $server_id) {
					$defaults=$srvgrp;
					$defaults[shm_place]=$shm;
					return LOOP_BREAK;	
				}
		});
					
			$cur=bartlby_toggle_servergroup_notify($btl->RES, $defaults[shm_place], 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("trigger_" . $server_id, "src", "themes/" . $layout->theme . "/images/trigger.gif");
				//$res->AddAssign("trigger_" . $server_id, "title", "disable notifications");
			} else {
				//$res->addAlert("Check disabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("trigger_" . $server_id, "src", "themes/" . $layout->theme . "/images/notrigger.gif");
				//$res->AddAssign("trigger_" . $server_id, "title", "enable trigger");
			}
			
			
			
			
		
	
	
	return $res;
}


function toggle_servergroup_check($server_id, $service_id) {
	global $btl;
	global $layout;
	$res = new xajaxresponse();
	
		
			$btl->servergroup_list_loop(function($srvgrp, $shm) use(&$defaults, &$server_id) {
				if($srvgrp[servergroup_id] == $server_id) {
					$defaults=$srvgrp;
					$defaults[shm_place]=$shm;
					return LOOP_BREAK;	
				}
		});
			
			$cur=bartlby_toggle_servergroup_active($btl->RES, $defaults[shm_place], 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("servergroup_" . $server_id, "src", "themes/" . $layout->theme . "/images/enabled.gif");
				//$res->AddAssign("server_" . $server_id, "title", "Disable Checks");
			} else {
				//$res->addAlert("Check disabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("servergroup_" . $server_id, "src", "themes/" . $layout->theme . "/images/diabled.gif");
				//$res->AddAssign("server_" . $server_id, "title", "Enable Checks");
			}
			
			
			
			
		
	
	
	return $res;
}


function toggle_server_check($server_id, $service_id) {
	global $btl;
	global $layout;
	$res = new xajaxresponse();
	if(!preg_match("/^XML.*$/i", $server_id)) {
		if($btl->hasServerorServiceRight($server_id, false)) {
			$gsm=bartlby_get_server_by_id($btl->RES, $server_id);
			
			$cur=bartlby_toggle_server_active($btl->RES, $gsm[server_shm_place], 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("server_" . $server_id, "src", "themes/" . $layout->theme . "/images/enabled.gif");
				//$res->AddAssign("server_" . $server_id, "title", "Disable Checks");
			} else {
				//$res->addAlert("Check disabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("server_" . $server_id, "src", "themes/" . $layout->theme . "/images/diabled.gif");
				//$res->AddAssign("server_" . $server_id, "title", "Enable Checks");
			}
			
			
			
			
		} else{
			$res->addAlert("permission denied");
		}
	
	} else {
		 $res->addAlert("action not possible on xml remote instances");
	}	
	return $res;
}




function toggle_server_notify_check($server_id, $service_id) {
	global $btl;
	global $layout;

	$res = new xajaxresponse();
	if(!preg_match("/^XML.*$/i", $server_id)) {
		if($btl->hasServerorServiceRight($server_id, false)) {
			$gsm=bartlby_get_server_by_id($btl->RES, $server_id);
			
			$cur=bartlby_toggle_server_notify($btl->RES, $gsm[server_shm_place], 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("trigger_" . $server_id, "src", "themes/" . $layout->theme . "/images/trigger.gif");
				//$res->AddAssign("trigger_" . $server_id, "title", "disable notifications");
			} else {
				//$res->addAlert("Check disabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("trigger_" . $server_id, "src", "themes/" . $layout->theme . "/images/notrigger.gif");
				//$res->AddAssign("trigger_" . $server_id, "title", "enable trigger");
			}
			
			
			
			
		} else{
			$res->addAlert("permission denied");
		}
	
	} else {
		 $res->addAlert("action not possible on xml remote instances");
	}	
	return $res;
}
function toggle_service_handled($server_id, $service_id) {
	global $btl, $layout;
	$res = new xajaxresponse();
	if($btl->hasServerorServiceRight($service_id, false)) {
			$gsm=bartlby_get_service_by_id($btl->RES, $service_id);
			$idx=$btl->findSHMPlace($service_id);
			$cur=bartlby_toggle_service_handled($btl->RES, $idx, 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("handled_" . $service_id, "src", "themes/" . $layout->theme . "/images/handled.png");
				
				//$res->AddAssign("trigger_" . $service_id, "title", "disable notifications");
			} else { 
				//$res->addAlert("Check disabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("handled_" . $service_id, "src", "themes/" . $layout->theme . "/images/unhandled.png");
				
				//$res->AddAssign("trigger_" . $service_id, "title", "enable trigger");
			}
			
			
			
			
		} else{
			$res->addAlert("permission denied");
		}
	
	return $res;
}

function toggle_service_notify_check($server_id, $service_id) {
	global $btl, $layout;
	$res = new xajaxresponse();
	if(!preg_match("/^XML.*$/i", $service_id)) {
		if($btl->hasServerorServiceRight($service_id, false)) {
			$gsm=bartlby_get_service_by_id($btl->RES, $service_id);
			$idx=$btl->findSHMPlace($service_id);
			$cur=bartlby_toggle_service_notify($btl->RES, $idx, 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("trigger_" . $service_id, "src", "themes/" . $layout->theme . "/images/trigger.gif");
				//$res->AddAssign("trigger_" . $service_id, "title", "disable notifications");
			} else {
				//$res->addAlert("Check disabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("trigger_" . $service_id, "src", "themes/" . $layout->theme . "/images/notrigger.gif");
				//$res->AddAssign("trigger_" . $service_id, "title", "enable trigger");
			}
			
			
			
			
		} else{
			$res->addAlert("permission denied");
		}
	
	} else {
		 $res->addAlert("action not possible on xml remote instances");
	}	
	return $res;
}

function toggle_service_check($server_id, $service_id) {
	global $btl, $layout;
	$res = new xajaxresponse();
	if(!preg_match("/^XML.*$/i", $service_id)) {
		if($btl->hasServerorServiceRight($service_id, false)) {
			$gsm=bartlby_get_service_by_id($btl->RES, $service_id);
			$idx=$btl->findSHMPlace($service_id);
			$cur=bartlby_toggle_service_active($btl->RES, $idx, 1);
			
			if($cur == 1) { //Active
				//$res->addAlert("Check enabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddAssign("service_" . $service_id, "src", "themes/" . $layout->theme . "/images/enabled.gif");
				//$res->AddAssign("service_" . $service_id, "title", "Disable Checks");
			} else {
				//$res->addAlert("Check disabled on:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);	
				$res->AddAssign("service_" . $service_id, "src", "themes/" . $layout->theme . "/images/diabled.gif");
				//$res->AddAssign("service_" . $service_id, "title", "Enable Checks");
			}
			
			
			
			
		} else{
			$res->addAlert("permission denied");
		}
	
	} else {
		 $res->addAlert("action not possible on xml remote instances");
	}	
	return $res;
}

function updatePerfHandler($script_after, $srv_id, $svc_id) {
	global $btl;
	$res = new xajaxResponse();
	$btl->updatePerfHandler($srv_id, $svc_id);
	$res->addScript($script_after);
	return $res;
}

function ExtensionAjax($ext, $func) {
	$res = new xajaxResponse();
	@include_once("extensions/$ext/" .$ext . ".class.php");
	@eval("\$clh = new " . $ext . "();");
	if(method_exists($clh, $func)) {
		eval("\$o = \$clh->" . $func. "();");
		$ex[ex_name]=$file;
		$ex[out] = $o;	
	}
	
	return $o;
		
}

function removeDIV($div) {
	$res = new xajaxResponse();
	$res->addClear($div, 'innerHTML', '');
	return $res;
}



	
function forceCheck($server, $service) {
	global $btl;
	$res = new xajaxresponse();
	if(!preg_match("/^XML.*$/i", $service)) {
		if($service) {
			if($btl->hasServerorServiceRight($service, false)) {
				$gsm=bartlby_get_service_by_id($btl->RES, $service);
				$idx=$btl->findSHMPlace($service);
				$cur=bartlby_check_force($btl->RES, $idx);
				//$res->addAlert("immediate check scheduled for:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
				$res->AddScript('noty({"text":"Check has been forced","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
			} else {
				$res->addAlert("permission denied to force:" . $gsm[server_name] . ":" . $gsm[client_port] . "/" . $gsm[service_name]);
			}
		} else {                                     
		 	$res->addAlert("missing service_id");
		}  
	} else {
	 	$res->addAlert("force check isnt possible on xml remote services");
	}   
	return $res;
}


function AddDowntime($av) {
	$res = new xajaxResponse();
	$al = "";
	if(!bartlbize_field($av[downtime_notice])){
		$al="1";
		$res->addAssign("error_downtime_notice", "innerHTML", "required field");
	} else {
		$res->addAssign("error_downtime_notice", "innerHTML", "");
	}
	if(!bartlbize_field($av[downtime_from])){
		$al="1";
		$res->addAssign("error_downtime_from", "innerHTML", "required field");
	} else {
		$res->addAssign("error_downtime_from", "innerHTML", "");
	}
	if(!bartlbize_field($av[downtime_to])){
		$al="1";
		$res->addAssign("error_downtime_to", "innerHTML", "required field");
	} else {
		$res->addAssign("error_downtime_to", "innerHTML", "");
	}
	if($al == "") $res->AddScript("document.fm1.submit()");
	return $res;
}
function CreatePackage($av) {
	$res = new xajaxResponse();
	
	$al="";
	
	
	if(!bartlbize_field($av[package_name])){
		$al="1";
		$res->addAssign("error_package_name", "innerHTML", "required field");
	} else {
		$res->addAssign("error_package_name", "innerHTML", "");
		if(!$av[package_overwrite]) {
			if(file_exists("pkgs/" . $av[package_name])) {
				$res->addAssign("error_package_name", "innerHTML", "Package already exists");
				$al="1";
			} else {
				$res->addAssign("error_package_name", "innerHTML", "");
			}
		}
		
	}
	
	
	if(!is_array($av[services])) {
		$al="1";
		$res->addAssign("error_services[]", "innerHTML", "select services");
	} else {
		$res->addAssign("error_services[]", "innerHTML", "");
	}	
	
	if($al=="")
		$res->AddScript("document.fm1.submit()");
	
	return $res;	
}





function PluginSearch($what) {
	global $btl;
	$res = new xajaxResponse();
	
	$optind=0;
	$y=0;
	
	
	$optind=0;
	$plgs=bartlby_config($btl->CFG, "agent_plugin_dir");
	$dh=opendir($plgs);
	while ($file = readdir ($dh)) { 
	   if ($file != "." && $file != "..") { 
	   	clearstatcache();
	   	if((preg_match("/\.exe$/i", $file)) || (is_executable($plgs . "/" . $file) && !is_dir($plgs . "/" . $file))) {
	   			if(preg_match("/" . $what . "/i", $file)) {
	       			$output .= "<a href=\"javascript:void(0);\" onClick=\"document.getElementById('service_plugin').value='" . $file . "';xajax_removeDIV('plugin_search_suggest');\">$file</a><br>";
	       			$y++;
	       		}
	       	}
	   }
	   if($y>20) {
			break;	
	   } 
	}
	closedir($dh); 
	
	$output = "<a href='javascript:void(0);' onClick=\"xajax_removeDIV('plugin_search_suggest');\">close</A><br><br>" . $output;
	$res->addAssign("plugin_search_suggest", "innerHTML", $output);
	return $res;	
}




function QuickLook($what) {
	global $btl, $layout, $rq;
	//compat for extensions
	$_GET[search] = $what;
	$ss = $what;

	$res = new xajaxResponse();
	
	//$servers=$btl->GetSVCMap();	
	$_GET["servers"]=$servers;
	
	//Search Servers
	$rq = '<table class="table table-bordered table-striped table-condensed" id=quick_look_table>
							  <thead>
								  <tr>
									  <th>Group</th>
									  <th>Element</th>
									  <th>Options</th>
									  
									  
								  </tr>
							  </thead>   ';


	$svcgrpfound=false;
	$btl->worker_list_loop(function($wrk, $shm) use(&$what, &$rq, &$svcgrpfound, &$btl, &$layout) {
		if(@preg_match("/" . $what . "/i", $wrk[name])) {
			
				$rq .= "<tr><td>Worker</td><td><a href='worker_detail.php?worker_id=" . $wrk[worker_id] . "'><font size=1>" . $wrk[name] . "</A></font></td><td>" . $btl->getWorkerOptionsBTN($wrk, $layout) . "</td>";	
				$wrkfound=true;
		}

	});


    $svcfound_counter=0;
    $btl->server_list_loop(function($srv, $shm)  {
		global $rq, $sfound, $svcfound, $btl, $_GET, $svcfound_counter;


		
		if(@preg_match("/" . $_GET[search] . "/i", $srv[server_name] )) {
			$rq .= "<tr><td>Server</td><td><a href='server_detail.php?server_id=" . $srv[server_id] . "'><font size=1>" . $srv[server_name] . "</font></A>(<a href='services.php?server_id=" . $srv[server_id] . "'><font size=1>Services</font></A>)</td><td>" . $btl->getserveroptions($srv, $layout) . "</td></tr>";        
            $svcfound=true;
			$svcfound_counter++;
			if($svcfound_counter >= 25) return -1;
		}
		

	});
	$sfound=false;	
	$svcfound=false;
	$svcfound_counter=0;
	$btl->service_list_loop(function($svc, $shm)  {
		global $rq, $sfound, $svcfound, $btl, $_GET, $svcfound_counter;


		
		if(@preg_match("/" . $_GET[search] . "/i", $svc[server_name] . "/" . $svc[service_name])) {
			$rq .= "<tr><td>Service</td><td><a href='service_detail.php?service_place=" . $shm . "'><font size=1>" . $svc[server_name] . "/" . $svc[service_name] . "</A></font></td><td>" . $btl->getServiceOptions($svc, $layout) . "</td>";	
			$svcfound=true;
			$svcfound_counter++;
			if($svcfound_counter >= 25) return -1;
		}
		

	});
	
	
	$srvgrpfound=false;
	$btl->servergroup_list_loop(function($srvgrp, $shm) use(&$what, &$rq, &$srvgrpfound, &$btl, &$layout) {
		if(@preg_match("/" . $what . "/i", $srvgrp[servergroup_name])) {
			
				$rq .= "<tr><td>ServerGroup</td><td><a href='servergroup_detail.php?servergroup_id=" . $srvgrp[servergroup_id] . "'><font size=1>" . $srvgrp[servergroup_name] . "</A></font></td><td>" . $btl->getServerGroupOptions($srvgrp, $layout) . "</td>";	
				$srvgrpfound=true;
		}

	});
	
	$svcgrpfound=false;
	$btl->servicegroup_list_loop(function($srvgrp, $shm) use(&$what, &$rq, &$svcgrpfound, &$btl, &$layout) {
		if(@preg_match("/" . $what . "/i", $srvgrp[servicegroup_name])) {
			
				$rq .= "<tr><td>ServiceGroup</td><td><a href='servicegroup_detail.php?servicegroup_id=" . $srvgrp[servicegroup_id] . "'><font size=1>" . $srvgrp[servicegroup_name] . "</A></font></td><td>" . $btl->getServiceGroupOptions($srvgrp, $layout) . "</td>";	
				$svcgrpfound=true;
		}

	});

	
	


	$rq .= "</table>";
	$qckb = $rq;
	
	@reset($servers);
	$rq = "";
	$btl->getExtensionsReturn("_quickLook", false);
	if($rq == "") {
		$rq = "<tr><td colspan=2><i>no extension returned results</i></td></tr>";	
	}
	$rq = "<table width=100%>" . $_GET[rq];
	$rq .= "</table>";
	//Search Services	
		
	
	//Search Workers
	//Call n get return of Extensions
	$output .=  $layout->create_box("Extensions", $rq, "search_extensions");
	$cl_button = "<a href='javascript:void(0);' onClick=\"xajax_removeDIV('quick_suggest');\">close</A><br>";
	
	$output = $cl_button . $qckb . $layout->boxes[search_extensions];
	
	$res->addAssign("quick_suggest", "innerHTML", $output);
	$res->AddScript("quick_look_group()");
				
	return $res;	
}
function CreateReport($aFormValues) {
	global $_GET, $_POST;
	
	$av = $aFormValues;
	
	$al="";
	
	$res = new xajaxResponse();
	
	if(!bartlbize_field($av[report_start])){
		$al="1";
		$res->addAssign("error_report_start", "innerHTML", "required field");
	} else {
		$res->addAssign("error_report_start", "innerHTML", "");
	}
	if(!bartlbize_field($av[report_end])){
		$al="1";
		$res->addAssign("error_report_end", "innerHTML", "required field");
	} else {
		$res->addAssign("error_report_end", "innerHTML", "");
	}
	if($av[report_service] == "") {
		$al="1";
		$res->addAssign("error_report_service", "innerHTML", "choose a service");
	} else {
		$res->addAssign("error_report_service", "innerHTML", "");
	
	}
	if($al == "")  {
		$res->addScript("document.fm1.submit()");
	}
	
	return $res;	
	
	
	
}
function AddModifyServiceGroup($aFormValues) {
	global $_GET, $_POST;
	
	$av = $aFormValues;
	
	$res = new xajaxResponse();
	
	
	
	$al="";
	
	if(!bartlbize_field($av[servicegroup_name])) {
		$res->addAssign("error_servicegroup_name", "innerHTML", "You must specify a correct group name");
		$al="1";
	} else {
		$res->addAssign("error_servicegroup_name", "innerHTML", "");
	}
		
	
	if($al == "")  {
		$res->addScript("document.fm1.submit()");
	}
	
	return $res;	
}

function AddModifyServerGroup($aFormValues) {
	global $_GET, $_POST;
	
	$av = $aFormValues;
	
	$res = new xajaxResponse();
	
	
	
	$al="";
	
	if(!bartlbize_field($av[servergroup_name])) {
		$res->addAssign("error_servergroup_name", "innerHTML", "You must specify a correct group name");
		$al="1";
	} else {
		$res->addAssign("error_servergroup_name", "innerHTML", "");
	}
		
	
	if($al == "")  {
		$res->addScript("document.fm1.submit()");
	}
	
	return $res;	
}


function AddModifyClient($aFormValues) {
	global $_GET, $_POST;
	
	$av = $aFormValues;
	
	$res = new xajaxResponse();
	
	
	
	$al="";
	
	if(!bartlbize_field($av[server_name])) {
		$res->addAssign("error_server_name", "innerHTML", "You must specify a correct server name");
		$al="1";
	} else {
		$res->addAssign("error_server_name", "innerHTML", "");
	}
		
	if(!bartlbize_field($av[server_ip])) {
		$al="1";
		$res->addAssign("error_server_ip", "innerHTML", "You must specify a correct Server IP-Address");
	}else{
		$res->addAssign("error_server_ip", "innerHTML", "");
	}
		
	if(!bartlbize_int($av[server_flap_seconds])) {
		$al="1";
		$res->addAssign("error_server_flap_seconds", "innerHTML", "required field");
	} else {
		$res->addAssign("error_server_flap_seconds", "innerHTML", "");
	}
	
	if(!bartlbize_int($av[server_port])){
		$al="1";
		$res->addAssign("error_server_port", "innerHTML", "required field");
	} else {
		$res->addAssign("error_server_port", "innerHTML", "");
	}
	if($al == "")  {
		$res->addScript("document.fm1.submit()");
	}
	
	return $res;	
}


function AddModifyWorker($aFormValues) {
	global $_GET, $_POST;
	
	$av = $aFormValues;
	
	$res = new xajaxResponse();
	
	
	
	$al="";
	
	if(!bartlbize_field($av[worker_name])){
		$res->addAssign("error_worker_name", "innerHTML", "You must specify a correct worker name");
		$al="1";
	} else {
		$res->addAssign("error_worker_name", "innerHTML", "");
	}
	
	if($av[action] == 'add_worker' || ($av[worker_password] != "" && $av[worker_password1] != "")) {	
		if(!bartlbize_field($av[worker_password])){
			$res->addAssign("error_worker_password", "innerHTML", "You must specify a correct password");
			$al="1";
		} else {
			$res->addAssign("error_worker_password", "innerHTML", "");
		}
	}
	
	if($av[worker_password] != $av[worker_password1]) {
		$res->addAssign("error_worker_password1", "innerHTML", "Passwords dont match");
		$al="1";
	} else {
		$res->addAssign("error_worker_password1", "innerHTML", "");
	}
	
	if(!bartlbize_field($av[worker_mail], true)){
		$res->addAssign("error_worker_mail", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_worker_mail", "innerHTML", "");
	}
	
	if(!bartlbize_int($av[worker_icq], true)){
		$res->addAssign("error_worker_icq", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_worker_icq", "innerHTML", "");
	}
	
	if(!bartlbize_int($av[escalation_limit])){
		$res->addAssign("error_escalation_limit", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_escalation_limit", "innerHTML", "");
	}
		
		
	if(!bartlbize_int($av[escalation_minutes])){
		$res->addAssign("error_escalation_minutes", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_escalation_minutes", "innerHTML", "");
	}
	
	
	
	if($al == "")  {
		$res->addScript("document.fm1.submit()");
	}
	
	return $res;	
}

function AddModifyService($aFormValues) {
	global $_GET, $_POST;
	
	$av = $aFormValues;
	
	$res = new xajaxResponse();
	
	
	
	$al="";
	
	if(!bartlbize_field($av[service_name])){
		$res->addAssign("error_service_name", "innerHTML", "You must specify a correct service name");
		$al="1";
	} else {
		$res->addAssign("error_service_name", "innerHTML", "");
	}
	
	if(!bartlbize_int($av[service_interval])){
		$res->addAssign("error_service_interval", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_service_interval", "innerHTML", "");
	}
	
	if(!bartlbize_int($av[service_retain])){
		$res->addAssign("error_service_retain", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_service_retain", "innerHTML", "");
	}

	if(!bartlbize_int($av[flap_seconds])){
		$res->addAssign("error_flap_seconds", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_flap_seconds", "innerHTML", "");
	}
	
	if(!bartlbize_int($av[escalate_divisor])){
		$res->addAssign("error_escalate_divisor", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_escalate_divisor", "innerHTML", "");
	}
	if(!bartlbize_int($av[renotify_interval])){
		$res->addAssign("error_renotify_interval", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_renotify_interval", "innerHTML", "");
	}
	if(!$av["service_server[]"] && !$av["service_server"]) {
		$res->addAssign("error_service_server[]", "innerHTML", "required field");
		$al="1";
	} else {
		$res->addAssign("error_service_server[]", "innerHTML", "");
	}
	
	switch($av[service_type]) {
		case 1:
		case 6:
		case 7:
		case 8:
		case 9:
		case 4:
		case 2:
			if(!bartlbize_int($av[service_check_timeout])){
				$res->addAssign("error_service_check_timeout", "innerHTML", "required field");
				$al="1";
			} else {
				$res->addAssign("error_service_check_timeout", "innerHTML", "");
			}
	
			if(!bartlbize_field($av[service_plugin])){
				$res->addAssign("error_service_plugin", "innerHTML", "required field");
				$al="1";
			} else {
				$res->addAssign("error_service_plugin", "innerHTML", "");
			}
			
			if(!bartlbize_field($av[service_args], true)){
				$res->addAssign("error_service_args", "innerHTML", "required field");
				$al="1";
			} else {
				$res->addAssign("error_service_args", "innerHTML", "");
			}
			
			if($av[service_type] == 2) {
				if(!bartlbize_int($av[service_passive_timeout])){
					$res->addAssign("error_service_passive_timeout", "innerHTML", "required field");
					$al="1";
				} else {
					$res->addAssign("error_service_passive_timeout", "innerHTML", "");
				}
			}	
			
		
				
		break;
		case 3:
			if(!bartlbize_field($av[service_var])){
				$res->addAssign("error_service_var", "innerHTML", "required field");
				$al="1";
			} else {
				$res->addAssign("error_service_var", "innerHTML", "");
			}
		break;	
		case 5:
			if(!bartlbize_field($av[snmp_community])){
				$res->addAssign("error_snmp_community", "innerHTML", "required field");
				$al="1";
			} else {
				$res->addAssign("error_snmp_community", "innerHTML", "");
			}
				
			
			
			if(!bartlbize_field($av[snmp_objid])){
				$res->addAssign("error_snmp_objid", "innerHTML", "required field");
				$al="1";
			} else {
				$res->addAssign("error_snmp_objid", "innerHTML", "");
			}
			
			if(!bartlbize_int($av[snmp_warning])){
				$res->addAssign("error_snmp_warning", "innerHTML", "required field");
				$al="1";
			} else {
				$res->addAssign("error_snmp_warning", "innerHTML", "");
			}
			if(!bartlbize_int($av[snmp_critical])){
				$res->addAssign("error_snmp_critical", "innerHTML", "required field");
				$al="1";
			} else {
				$res->addAssign("error_snmp_critical", "innerHTML", "");
			}
					
		break;
		
	
		
	}
	
	
	if($al == "")  {
		$res->addScript("document.fm1.submit()");
	}
	
	return $res;	
}
function bartlbize_date($v) {
	if($v == "") {
		return false;
	}
	if(!preg_match("/[0-9].+:[0-9].+:[0-9].+/i", $v)) {
		
		return false;	
	}
	return true;
	
}
function bartlbize_int($v, $n = false) {
	if($v == "") {
		return $n;
	}
	
	if(!preg_match("/^[0-9]+$/i", $v)) {
		return false;	
	}
	return true;
	
}


function bartlbize_field($v, $n=false) {
	if(!$n) {
		if($v == "") {
			return false;
		}
	}
	if(preg_match("/'/i", $v)) {
		return false;	
	}
	return true;
	
}

?>
