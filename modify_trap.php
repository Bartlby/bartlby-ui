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




//$defaults=bartlby_get_trap_by_id($btl->RES, $_GET[server_id]);
$defaults = array();
$btl->trap_list_loop(function($trap, $shm) use(&$defaults) {
	global $_GET;
	if($trap[trap_id] == $_GET[trap_id]) {

		$defaults=$trap;

		return LOOP_BREAK;	
	}
});

//error_reporting(E_ALL);


$fm_action="modify_trap";
$layout->setTitle("Modify trap");
if($_GET["copy"] == "true") {
	$fm_action="add_trap";
	$btl->hasRight("action.copy_trap");
	$layout->setTitle("Copy trap");
		
}
if($_GET["new"] == "true") {
	$fm_action="add_trap";
	$btl->hasRight("action.add_trap");
	$layout->setTitle("Add trap");
	
	
	
}





$servers_out=array();
$services_x=0;
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out, &$services_x, &$defaults) {
	if($svc[is_gone] != 0) {
	 return LOOP_CONTINUE;
	}
	if(($_GET[dropdown_term] &&  @preg_match("/" . $_GET[dropdown_term] . "/i", $svc[server_name] . "/" .  $svc[service_name])) || $svc[service_id] == $defaults[trap_service_id] ) {

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
			if($servs[$x][service_id] == $defaults[trap_service_id]) {
				$servers[$optind][s]=1;		
			}
			

			
			$servers[$optind][c]="";
			$servers[$optind][v]=$servs[$x][service_id];	
			$servers[$optind][k]=$servs[$x][server_name] . "/" .  $servs[$x][service_name];
			
			$optind++;
		}
	}




//Events Enabled
$fixed_status[0][c]="";
$fixed_status[0][v] = -2; //No
$fixed_status[0][k] = "Unused"; //No
$fixed_status[0][s]=0;

$fixed_status[1][c]="";
$fixed_status[1][v] = 0; //No
$fixed_status[1][k] = "OK"; //No
$fixed_status[1][s]=0;

$fixed_status[2][c]="";
$fixed_status[2][v] = 1; //No
$fixed_status[2][k] = "Warning"; //No
$fixed_status[2][s]=0;


$fixed_status[3][c]="";
$fixed_status[3][v] = 2; //No
$fixed_status[3][k] = "Critical"; //No
$fixed_status[3][s]=0;

if(!$defaults[trap_fixed_status]) $defaults[trap_fixed_status]=-2;

switch($defaults[trap_fixed_status]) {
	case -2:
		$fixed_status[0][s]=1;
	break;
	case 0:
		$fixed_status[1][s]=1;
	break;
	case 1:
		$fixed_status[2][s]=1;
	break;
	case 2:
		$fixed_status[3][s]=1;
	break;

}




if(is_int($defaults[trap_is_final]) && $defaults[trap_is_final] == 0) {
	$trap_is_final="";	
	
} else {
	
	$trap_is_final="checked";
}

if($fm_action == "modify_trap") {
	$btl->hasRight("action.modify_trap");	
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$ov .= $layout->FormBox(
		array(
			0=>"Name",
			1=>$layout->Field("trap_name", "text", $defaults[trap_name]) . $layout->Field("action", "hidden", $fm_action)  . $layout->Field("trap_id", "hidden", $_GET[trap_id])
		)
,true);


$ov .= $layout->FormBox(
		array(
			0=>"Catcher Rule",
			1=>$layout->Field("trap_catcher", "text", nl_safe($defaults[trap_catcher]))
		)
,true);
$ov .= $layout->FormBox(
		array(
			0=>"Status Text Pattern",
			1=>$layout->Field("trap_status_text", "text", nl_safe($defaults[trap_status_text]))
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"OK Pattern",
			1=>$layout->Field("trap_status_ok", "text", nl_safe($defaults[trap_status_ok]))
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"WARNING Pattern",
			1=>$layout->Field("trap_status_warning", "text", nl_safe($defaults[trap_status_warning]))
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"CRITICAL Pattern",
			1=>$layout->Field("trap_status_critical", "text", nl_safe($defaults[trap_status_critical]))
		)
,true);




$ov .= $layout->FormBox(

		array(
			0=>"Assign to Service",
			1=>$layout->DropDown("trap_service_id", $servers,"", "", false, "ajax_trap_service")
		)
,true);



$ov .= $layout->FormBox(
		array(
			0=>"Fixed Status (if catcher matches):",
			1=>$layout->DropDown("trap_fixed_status", $fixed_status)
			
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Priority",
			1=>$layout->Field("trap_prio", "text", $defaults[trap_prio])
		)
,true);


$ov .= $layout->FormBox(
		array(
			0=>"Trap is Final?",
			1=>$layout->Field("trap_is_final", "checkbox", "1", "", "class='switch' " . $trap_is_final)
			
		)
,true);

$ov .= $layout->FormBox(

		array(
			0=>"Orchestra ID",
			1=>$layout->orchDropdown(true, $defaults[orch_id]) .  $layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyTrap(xajax.getFormValues(\"fm1\"))'")
		)
,true);



$title="add trap";  
$content = "<span class=form-horizontal>" . $ov . "</span>";
$layout->create_box($layout->BoxTitle, $content);
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);
$layout->FormEnd();


//HIDE MAIN
$layout->boxes_placed[MAIN]=true;


$layout->display();

function nl_safe($str) {
	$str = str_replace("\n", "\\n", $str);
	//$str = str_replace("\\", "\\\\", $str);
	return $str;
}
