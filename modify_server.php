<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";



$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}

$layout= new Layout();


$layout->set_menu("client");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);
$layout->Table("100%");
if($_GET[server_id]) {
	$btl->hasServerRight($_GET[server_id]);	
}



	

$defaults=@bartlby_get_server_by_id($btl->RES, $_GET[server_id]);


$servers_out=array();
$services_x=0;
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out, &$services_x, &$defaults) {
	if($svc[is_gone] != 0) {
	 continue;
	}
	if(($_GET[dropdown_term] &&  @preg_match("/" . $_GET[dropdown_term] . "/i", $svc[server_name] . "/" .  $svc[service_name])) || $svc[service_id] == $defaults[server_dead]) {
		if(!is_array($servers_out[$svc[server_id]])) {
			$servers_out[$svc[server_id]]=array();
		}
		array_push($servers_out[$svc[server_id]], $svc);
		$services_x++;
		if($services_x > 50) return LOOP_BREAK;
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
			if($servs[$x][service_id] == $defaults[server_dead]) {
				$servers[$optind][s]=1;
			}
			$servers[$optind][c]="";
			$servers[$optind][v]=$servs[$x][service_id];	
			$servers[$optind][k]=$servs[$x][server_name] . "/" .  $servs[$x][service_name];
			
			$optind++;
		}
	}

$optind=0;
if(!is_dir("pkgs")) {
	mkdir("pkgs", 0777);	
}

$dhl=opendir("pkgs");
$packages[$optind][c]="";
$packages[$optind][v]="";	
$packages[$optind][k]="--None--";
$optind++;
while($file = readdir($dhl)) {
	//$sr=bartlby_get_server_by_id($btl->RES, $k);
	
	//$isup=$btl->isServerUp($k);
	//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }
	if(!is_dir("pkgs/" . $file)) {
		$packages[$optind][c]="";
		$packages[$optind][v]=$file;	
		$packages[$optind][k]="" . $file;
		$optind++;
	}
}
closedir($dhl);


$fm_action="modify_server";
$layout->setTitle("Modify Server");
if($_GET["copy"] == "true") {
	$fm_action="add_server";
	$btl->hasRight("action.copy_server");
	$layout->setTitle("Copy Server");
}
if($_GET["new"] == "true") {
	$fm_action="add_server";
	$btl->hasRight("action.add_server");
	$layout->setTitle("Add Server");
	
	$defaults["min_from"]="00";
	$defaults["min_to"]="59";
	$defaults["hour_from"]="00";
	$defaults["hour_to"]="24";
	$defaults["server_dead"] = "0";
	
	$defaults[server_port]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.server.port");
	$defaults[server_icon]=bartlby_config(getcwd() . "/ui-extra.conf", "new.server.icon");
	
	
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
       		
       		if(strstr((string)$defaults[server_enabled_triggers],"|" . $file . "|")) {
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

if(is_int($defaults[server_notify]) && $defaults[server_notify] == 0) {
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


if(is_int($defaults[server_enabled]) && $defaults[server_enabled] == 0) {
	$servactive[0][s]=1;	
	
} else {

	$servactive[1][s]=1;
}


if($fm_action == "modify_server") {
	$btl->hasRight("action.modify_server");	
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}
$optind=0;
$dhl=opendir("server_icons");
while($file = readdir($dhl)) {
	//$sr=bartlby_get_server_by_id($btl->RES, $k);
	
	//$isup=$btl->isServerUp($k);
	//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }
	if(preg_match("/.*\.[png|gif]/", $file)) {
		
		if($defaults[server_icon] == $file) {
			
			$server_icons[$optind][s]=1;
		}
		$server_icons[$optind][c]="";
		$server_icons[$optind][v]=$file;	
		$server_icons[$optind][k]="" . $file;
		$optind++;
	}
	
}
closedir($dhl);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Server Name",
			1=>$layout->Field("server_name", "text", $defaults[server_name]) . $layout->Field("action", "hidden", $fm_action) . "<a href=\"javascript:var w=window.open('locate_server.php','','width=353,height=421, scrollbar=yes, scrollbars=yes')\">Find Server Wizard!</A>"
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Server IP",
			1=>$layout->Field("server_ip", "text", $defaults[server_ip])
		)
	)
,true);
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Server Port",
			1=>$layout->Field("server_port", "text", $defaults[server_port]) . $layout->Field("server_id", "hidden", $_GET[server_id])
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Server Enabled?",
			1=>$layout->DropDown("server_enabled", $servactive)
			
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Server Notify?",
			1=>$layout->DropDown("server_notify", $notenabled)
			
		)
	)
,true);


$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Flap Seconds",
			1=>$layout->Field("server_flap_seconds", "text", $defaults[server_flap_seconds])
		)
	)
,true);


if(!$_GET["copy"] && !$_GET["new"]) {

	if($defaults[server_dead]) {
		$svc = bartlby_get_service_by_id($btl->RES, $defaults[server_dead]);	
	}
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Alive indicator",
			1=>$layout->DropDown("service_id", $servers,"","",false, "ajax_service_list_php") . "<div style='float:right'><a href='#' onClick='$(\"#service_id\").find(\"option\").remove();$(\"#service_id\").trigger(\"liszt:updated\");'>Remove</A></div>"
		)
	)
,true);

	
}

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Triggers:",
			1=>$layout->DropDown("server_triggers[]", $triggers, "multiple") . " "
		)
	)
,true);

if($fm_action == "add_server") {
	$ov .= $layout->Tr(
	$layout->Td(
			Array(
				0=>"Package:",
				1=>$layout->DropDown("package_name", $packages) 
			)
		)

	,true);	
}
$ov .= $layout->Tr(
	$layout->Td(
			Array(
				0=>"Icon:",
				1=>$layout->DropDown("server_icon", $server_icons, "onChange=\"serviceManageIconChange(this.form);\"") 
			)
		)

,true);
$ov .= $layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"left",
					'show'=>"<div id=picholder></div><script>serviceManageIconChange(document.fm1);</script>"
					)
			)
		)

,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>array("colspan"=>2, "show"=>"<hr><b>SSH Options</b>")
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Keyfile Path",
			1=>$layout->Field("server_ssh_keyfile", "text", $defaults[server_ssh_keyfile])
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Key-Passphrase",
			1=>$layout->Field("server_ssh_passphrase", "text", $defaults[server_ssh_passphrase])
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Username ",
			1=>$layout->Field("server_ssh_username", "text", $defaults[server_ssh_username])
		)
	)
,true);








$title="add server";  
$content = "<table>" . $ov . "</table>";
$layout->push_outside($layout->create_box($layout->BoxTitle, $content));
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);

$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyClient(xajax.getFormValues(\"fm1\"))'")
					)
			)
		)

,false);

$layout->TableEnd();
$layout->FormEnd();
$layout->display();