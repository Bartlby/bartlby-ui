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
$layout->Table("100%");



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
	 continue;
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


//Notify Enabled
$notenabled[0][c]="";
$notenabled[0][v] = 0; //No
$notenabled[0][k] = "No"; //No
$notenabled[0][s]=0;

$notenabled[1][c]="";
$notenabled[1][v] = 1; //No
$notenabled[1][k] = "Yes"; //No
$notenabled[1][s]=0;

if(is_int($defaults[servicegroup_notify]) && $defaults[servicegroup_notify] == 0) {
	$notenabled[0][s]=1;	
	
} else {
	
	$notenabled[1][s]=1;
}

//Notify Enabled
$servactive[0][c]="";
$servactive[0][v] = 0; //No
$servactive[0][k] = "No"; //No
$servactive[0][s]=0;

$servactive[1][c]="";
$servactive[1][v] = 1; //No
$servactive[1][k] = "Yes"; //No
$servactive[1][s]=0;


if(is_int($defaults[servicegroup_active]) && $defaults[servicegroup_active] == 0) {
	$servactive[0][s]=1;	
	
} else {

	$servactive[1][s]=1;
}


if($fm_action == "modify_servicegroup") {
	$btl->hasRight("action.modify_servicegroup");	
}


if($defaults == false && $_GET["new"] != "true" && !$_GET[dropdown_search]) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servicegroup Name",
			1=>$layout->Field("servicegroup_name", "text", $defaults[servicegroup_name]) . $layout->Field("action", "hidden", $fm_action) 
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servicegroup Members",
			1=>$layout->DropDown("servicegroup_members[]", $servers,"multiple", "", false, "ajax_servicegroup_members")
		)
	)
,true);


$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servicegroup Enabled",
			1=>$layout->DropDown("servicegroup_active", $servactive)
			
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servicegroup Notify?",
			1=>$layout->DropDown("servicegroup_notify", $notenabled) . $layout->Field("servicegroup_id", "hidden", $_GET[servicegroup_id])
			
		)
	)
,true);
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Alive indicator",
			1=>$layout->DropDown("service_dead", $alive_indicator,"","",false, "ajax_service_list_php") . "<div style='float:right'><a href='#' onClick='$(\"#service_dead\").find(\"option\").remove();$(\"#service_dead\").trigger(\"liszt:updated\");'>Remove</A></div>"
		)
	)
,true);
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Triggers:",
			1=>$layout->DropDown("servicegroup_triggers[]", $triggers, "multiple") . " "
		)
	)
,true);


$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Orchestra ID",
			1=>$layout->Field("orch_id", "text", $defaults[orch_id])  
		)
	)
,true);









$title="add servicegroup";  
$content = "<table>" . $ov . "</table>";
$layout->push_outside($layout->create_box($layout->BoxTitle, $content));
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);

$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyServiceGroup(xajax.getFormValues(\"fm1\"))'")
					)
			)
		)

,false);

$layout->TableEnd();
$layout->FormEnd();
$layout->display();