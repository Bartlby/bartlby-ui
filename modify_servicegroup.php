<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";



$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false ) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}

$layout= new Layout();


//$layout->set_menu("client");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);



//$defaults=bartlby_get_servergroup_by_id($btl->RES, $_GET[server_id]);

$defaults = array();
$btl->servicegroup_list_loop(function($grp, $shm) use(&$defaults) {
	global $_GET;
	
	if($grp[servicegroup_id] == $_GET[servicegroup_id]) {

		$defaults=$grp;

		return LOOP_BREAK;	
	}
});
if($defaults[servicegroup_dead] != 0) {
	$svc_dead_marker = bartlby_get_service_by_id($btl->RES, $defaults[servicegroup_dead]);
}


$servers_out=array();
$services_x=0;
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out, &$services_x, &$defaults) {
	if($svc[is_gone] != 0) {
	 return LOOP_CONTINUE;
	}
	if(($_GET[dropdown_term] &&  @preg_match("/" . $_GET[dropdown_term] . "/i", $svc[server_name] . "/" .  $svc[service_name])) || $svc[service_id] == $defaults[servicegroup_dead] || strstr($defaults[servicegroup_members], "|" . $svc[service_id] . "|")) {

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
$servers=array();

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

				$servers[$optind][c]="";
				$servers[$optind][v]="s" . $servs[$x][server_id];	
				$servers[$optind][k]="" . $servs[$x][server_name] . "";
				$servers[$optind][is_group]=1;

				$optind++;
			} else {
				
			}
			if($servs[$x][is_gone] != 0) {
			 continue;
			}
			
			$state=$btl->getState($servs[$x][current_state]);
			if($servs[$x][service_id] == $defaults[servicegroup_dead]) {
				$alive_indicator[$optind][s]=1;
			}
			

			$alive_indicator[$optind][c]="";
			$alive_indicator[$optind][v]=$servs[$x][service_id];	
			$alive_indicator[$optind][k]=$servs[$x][server_name] . "/" .  $servs[$x][service_name];


			$servers[$optind][c]="";
			$servers[$optind][v]=$servs[$x][service_id];	
			$servers[$optind][k]=$servs[$x][server_name] . "/" .  $servs[$x][service_name];
			
			if(strstr($defaults[servicegroup_members], "|" . $servs[$x][service_id] . "|")) {
				$servers[$optind][s]=1;				
			}

			
			$optind++;
		}
	}


//error_reporting(E_ALL);


$fm_action="modify_servicegroup";
$layout->setTitle("Modify Servicegroup");
if($_GET["copy"] == "true") {
	$fm_action="add_servicegroup";
	$btl->hasRight("action.copy_servicegroup");
	$layout->setTitle("Copy Servicegroup");
}
if($_GET["new"] == "true") {
	$fm_action="add_servicegroup";
	$btl->hasRight("action.add_servicegroup");
	$layout->setTitle("Add Servicegroup");
	
	
	
}

$optind=0;
$plgs=bartlby_config($btl->CFG, "trigger_dir");
$dh=opendir($plgs);
while ($file = readdir ($dh)) { 
   if ($file != "." && $file != "..") { 
   	clearstatcache();
   	if(is_executable($plgs . "/" . $file) && !is_dir($plgs . "/" . $file)) {
   		
       		$triggers[$optind][c]="";
       		$triggers[$optind][v]=$file;
       		$triggers[$optind][k]=$file;
       		/*if($defaults[plugin] == $file) {
       			$plugins[$optind][s]=1;	
       		}*/
       		
       		if(strstr((string)$defaults[enabled_triggers],"|" . $file . "|")) {
				$triggers[$optind][s]=1;	
			}
       		
       		$optind++;
       	}
   } 
}
closedir($dh); 



if(is_int($defaults[servicegroup_notify]) && $defaults[servicegroup_notify] == 0) {
	$notenabled="";	
	
} else {
	
	$notenabled="checked";
}


if(is_int($defaults[servicegroup_active]) && $defaults[servicegroup_active] == 0) {
	$servactive="";	
	
} else {

	$servactive="checked";	
}


if($fm_action == "modify_servicegroup") {
	$btl->hasRight("action.modify_servicegroup");	
}


if($defaults == false && $_GET["new"] != "true" && !$_GET[dropdown_search]) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$ov .= $layout->FormBox(

		array(
			0=>"Name",
			1=>$layout->Field("servicegroup_name", "text", $defaults[servicegroup_name]) . $layout->Field("action", "hidden", $fm_action) 
		)
,true);

$ov .= $layout->FormBox(

		array(
			0=>"Members",
			1=>$layout->DropDown("servicegroup_members[]", $servers,"multiple", "", false, "ajax_servicegroup_members")
		)
,true);


$ov .= $layout->FormBox(

		array(
			0=>"Enabled",
			1=>$layout->Field("servicegroup_active", "checkbox", "1", "", "class='switch' " . $servactive) 
			
			
		)
,true);

$ov .= $layout->FormBox(

		array(
			0=>"Notify?",
			1=>$layout->Field("servicegroup_notify", "checkbox", "1", "", "class='switch' " . $notenabled) . $layout->Field("servicegroup_id", "hidden", $_GET[servicegroup_id])
			
			
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
			1=>$layout->DropDown("servicegroup_triggers[]", $triggers, "multiple") . " "
		)
,true);


$ov .= $layout->FormBox(

		array(
			0=>"Orchestra ID",
			1=>$layout->orchDropdown(true, $defaults[orch_id]) .  $layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyServiceGroup(xajax.getFormValues(\"fm1\"))'")
		)
,true);









$title="add servicegroup";  
$content = "<span class=form-horizontal>" . $ov . "</span>";
$layout->create_box($layout->BoxTitle, $content);
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);
$layout->FormEnd();

$layout->boxes_placed[MAIN]=true;

$layout->display();