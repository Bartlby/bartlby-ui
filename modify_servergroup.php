<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";



$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}


$layout= new Layout();


//$layout->set_menu("client");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);




//$defaults=bartlby_get_servergroup_by_id($btl->RES, $_GET[server_id]);
$defaults = array();
$btl->servergroup_list_loop(function($grp, $shm) use(&$defaults) {
	global $_GET;
	
	if($grp[servergroup_id] == $_GET[servergroup_id]) {

		$defaults=$grp;

		return LOOP_BREAK;	
	}
});
if($defaults[servergroup_dead] != 0) {
	$svc_dead_marker = bartlby_get_service_by_id($btl->RES, $defaults[servergroup_dead]);
}


$optind=0;
$servers=array();
$btl->server_list_loop(function($srv, $shm) use(&$servers, &$defaults, &$optind) {
		if(($_GET[dropdown_term] &&  @preg_match("/" . $_GET[dropdown_term] . "/i", $srv[server_name])) || strstr($defaults[servergroup_members], "|" . $srv[server_id] . "|")) {
		
			$servers[$optind][c]="";
			$servers[$optind][v]=$srv[server_id];	
			$servers[$optind][k]="" . $srv[server_name];
		
			if(($_GET[dropdown_term] &&  @preg_match("/" . $_GET[dropdown_term] . "/i", $srv[server_name])) || strstr($defaults[servergroup_members], "|" . $srv[server_id] . "|")) {
				$servers[$optind][s]=1;
			}
		
			$optind++;
		}
	});
	
	

$servers_out=array();
$services_x=0;
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out, &$services_x, &$defaults) {
	if($svc[is_gone] != 0) {
	 return LOOP_CONTINUE;
	}
	if(($_GET[dropdown_term] &&  @preg_match("/" . $_GET[dropdown_term] . "/i", $svc[server_name] . "/" .  $svc[service_name])) || $svc[service_id] == $defaults[servergroup_dead]) {

		if(!is_array($servers_out[$svc[server_id]])) {
			$servers_out[$svc[server_id]]=array();
		}
		array_push($servers_out[$svc[server_id]], $svc);

		$services_x++;
		//if($services_x > 50) return LOOP_BREAK;
	}
});			
ksort($servers_out);


$map=&$servers_out;
$optind=0;
while(list($k, $servs) = @each($map)) {
		$displayed_servers++;
		
		for($x=0; $x<count($servs); $x++) {
			//$v1=bartlby_get_service_by_id($btl->RES, $servs[$x][service_id]);
			
			if($x == 0) {
				//$isup=$btl->isServerUp($v1[server_id]);
				//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }
				$alive_indicator[$optind][c]="";
				$alive_indicator[$optind][v]="s" . $servs[$x][server_id];	
				$alive_indicator[$optind][k]="" . $servs[$x][server_name] . "";
				$alive_indicator[$optind][is_group]=1;
				$optind++;
			} else {
				
			}
			if($servs[$x][is_gone] != 0) {
			 continue;
			}
			
			$state=$btl->getState($servs[$x][current_state]);
			if($servs[$x][service_id] == $defaults[servergroup_dead]) {
				$alive_indicator[$optind][s]=1;
			}
			$alive_indicator[$optind][c]="";
			$alive_indicator[$optind][v]=$servs[$x][service_id];	
			$alive_indicator[$optind][k]=$servs[$x][server_name] . "/" .  $servs[$x][service_name];
			
			$optind++;
		}
	}



$optind=0;




//error_reporting(E_ALL);


$fm_action="modify_servergroup";
$layout->setTitle("Modify Servergroup");
if($_GET["copy"] == "true") {
	$fm_action="add_servergroup";
	$btl->hasRight("action.copy_servergroup");
	$layout->setTitle("Copy Servergroup");
		
}
if($_GET["new"] == "true") {
	$fm_action="add_servergroup";
	$btl->hasRight("action.add_servergroup");
	$layout->setTitle("Add Servergroup");
	
	
	
}



$triggers = $btl->getTriggerDropdown($defaults);



if(is_int($defaults[servergroup_notify]) && $defaults[servergroup_notify] == 0) {
	$notenabled="";	
	
} else {
	
	$notenabled="checked";
}



if(is_int($defaults[servergroup_active]) && $defaults[servergroup_active] == 0) {
	$servactive="";
} else {
	$servactive="checked";
}


if($fm_action == "modify_servergroup") {
	$btl->hasRight("action.modify_servergroup");	
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$ov .= $layout->FormBox(
		array(
			0=>"Name",
			1=>$layout->Field("servergroup_name", "text", $defaults[servergroup_name]) . $layout->Field("action", "hidden", $fm_action) 
		)
,true);

$ov .= $layout->FormBox(

		array(
			0=>"Members",
			1=>$layout->DropDown("servergroup_members[]", $servers,"multiple","",false, "ajax_server_list_php")
		)
,true);


$ov .= $layout->FormBox(

		array(
			0=>" Enabled",
			1=>$layout->Field("servergroup_active", "checkbox", "1", "", "class='switch' " . $servactive) 
						
		)
,true);

$ov .= $layout->FormBox(

		array(
			0=>" Notify?",
			1=>$layout->Field("servergroup_notify", "checkbox", "1", "", "class='switch' " . $notenabled) . $layout->Field("servergroup_id", "hidden", $_GET[servergroup_id])
			
			
		)
,true);
$ov .= $layout->FormBox(

		array(
			0=>"Alive indicator",
			1=>$layout->DropDown("service_dead", $alive_indicator,"multiple","",false, "ajax_service_list_php") . ""
		)
,true);

$ov .= $layout->FormBox(

		array(
			0=>"Triggers:",
			1=>$layout->DropDown("servergroup_triggers[]", $triggers, "multiple") . " "
		)
,true);

$ov .= $layout->FormBox(

		array(
			0=>"Orchestra ID",
			1=>$layout->orchDropdown(true, $defaults[orch_id]) . $layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyServerGroup(xajax.getFormValues(\"fm1\"))'")
		)
,true);










$title="add servergroup";  
$content = "<span class=form-horizontal>" . $ov . "</span>";
$layout->create_box($layout->BoxTitle, $content);
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);
$layout->FormEnd();


//HIDE MAIN
$layout->boxes_placed[MAIN]=true;


$layout->display();