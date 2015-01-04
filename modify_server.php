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

if($_GET[server_id]) {
	$btl->hasServerRight($_GET[server_id]);	
}


$defaults=@bartlby_get_server_by_id($btl->RES, $_GET[server_id]);


$servers_out=array();
$services_x=0;
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out, &$services_x, &$defaults) {
	if($svc[is_gone] != 0) {
	return LOOP_CONTINUE;
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


$types[0][c]="";
$types[0][v]="1";
$types[0][k]="Active";

if($defaults[default_service_type] == 1) {
	$types[0][s]=1;
}

$types[1][c]="";
$types[1][v]="2";
$types[1][k]="Passive";
if($defaults[default_service_type] == 2) {
	$types[1][s]=1;
}

$types[2][c]="";
$types[2][v]="3";
$types[2][k]="Group";
if($defaults[default_service_type] == 3) {
	$types[2][s]=1;
}

$types[3][c]="";
$types[3][v]="4";
$types[3][k]="Local";
if($defaults[default_service_type] == 4) {
	$types[3][s]=1;
}
$types[4][c]="";
$types[4][v]="5";
$types[4][k]="SNMP";
if($defaults[default_service_type] == 5) {
	$types[4][s]=1;
}

$types[5][c]="";
$types[5][v]="6";
$types[5][k]="NRPE";
if($defaults[default_service_type] == 6) {
	$types[5][s]=1;
}

$types[6][c]="";
$types[6][v]="7";
$types[6][k]="NRPE(ssl)";
if($defaults[default_service_type] == 7) {
	$types[6][s]=1;
}

$types[8][c]="";
$types[8][v]="9";
$types[8][k]="AgentV2(no-SSL)";
if($defaults[default_service_type] == 9) {
	$types[8][s]=1;
}

$types[7][c]="";
$types[7][v]="8";
$types[7][k]="AgentV2";
if($defaults[default_service_type] == 8) {
	$types[7][s]=1;
}


$types[9][c]="";
$types[9][v]="10";
$types[9][k]="SSH";
if($defaults[default_service_type] == 10) {
	$types[9][s]=1;
}

$types[10][c]="";
$types[10][v]="11";
$types[10][k]="TRAP";
if($defaults[default_service_type] == 11) {
	$types[10][s]=1;
}

$types[11][c]="";
$types[11][v]="12";
$types[11][k]="JSON";
if($defaults[default_service_type] == 12) {
	$types[11][s]=1;
}


if(!$defaults[default_service_type]) {
	$types[0][s]=1;	
}



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
       		
       		if(strstr((string)$defaults[enabled_triggers],"|" . $file . "|")) {
				$triggers[$optind][s]=1;	
			}
       		
       		$optind++;
       	}
   } 
}
closedir($dh);



if(is_int($defaults[server_notify]) && $defaults[server_notify] == 0) {
	
	$notchecked="";
	
} else {
	
	$notchecked="checked";
}

//Notify Enabled



if(is_int($defaults[server_enabled]) && $defaults[server_enabled] == 0) {
	$servchecked="";	
	
} else {

	$servchecked="checked";
}


if($fm_action == "modify_server") {
	$btl->hasRight("action.modify_server");	
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$o = explode("|", $defaults[exec_plan]);


for($x=0; $x<count($o); $x++) {
	$p = explode("=", $o[$x]);
	
	if(count($p) == 1) {
		$p = explode("!", $o[$x]);
		$filled[$p[0]][disabled]=1;
		
		
	}
	
	
	$filled[$p[0]][value]=$p[1];
	
	
}
$plan_box = "<table class='no-border'><tbody class='no-border-y'>";
for($x=0; $x<=6; $x++) {
	$chk="";
	
	
	$inv_check="";
	if($filled[$x][disabled] == 1) {
		$inv_check="checked";
	}	
		
	$plan_box .= "<tr><td><font size=1>" .  $wdays[$x] . "</font></td><td><input type=text id='wdays_plan[" . $x . "]'  name='wdays_plan[" . $x . "]' value='" . $filled[$x][value] . "' style='font-size:10px; width:200px; height:20px'><input type=checkbox class='icheck'  id='wdays_inv[" . $x . "]'  name='wdays_inv[" . $x . "]' $inv_check> invert</td></tr>";
	
}
$plan_box .= "<tr><td colspan=2><font size=1>Time ranges are seperated with ',' e.g.: 14:30-15:20,01:20-02:30 <a href='javascript:void(0);' onClick='modify_service_make_24();'>make 24h a day</a></font></td></tr>";
$plan_box .= "</tbody></table>";






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

$ov .= $layout->FormBox(
		array(
			0=>"Name",
			1=>$layout->Field("server_name", "text", $defaults[server_name]) . $layout->Field("action", "hidden", $fm_action) . "<a href=\"javascript:var w=window.open('locate_server.php','','width=353,height=421, scrollbar=yes, scrollbars=yes')\">Find Server Wizard!</A>"
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"IP-Address",
			1=>$layout->Field("server_ip", "text", $defaults[server_ip])
		)
,true);
$ov .= $layout->FormBox(
		array(
			0=>"Server Port",
			1=>$layout->Field("server_port", "text", $defaults[server_port]) . $layout->Field("server_id", "hidden", $_GET[server_id])
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Enabled?",
			1=>$layout->Field("server_enabled", "checkbox", "1", "", "class='switch' " . $servchecked)
			
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Notify?",
			1=>$layout->Field("server_notify", "checkbox", "1", "", "class='switch' " . $notchecked)
			
		)
,true);


$ov .= $layout->FormBox(
		array(
			0=>"Flap Seconds",
			1=>$layout->Field("server_flap_seconds", "text", $defaults[server_flap_seconds])
		)
,true);


if(!$_GET["copy"] && !$_GET["new"]) {

	if($defaults[server_dead]) {
		$svc = bartlby_get_service_by_id($btl->RES, $defaults[server_dead]);	
	}
$ov .= $layout->FormBox(
		array(
			0=>"Alive indicator",
			1=>$layout->DropDown("service_id", $servers,"multiple","",false, "ajax_service_list_php") . ""
		)
,true);

	
}

$ov .= $layout->FormBox(
		array(
			0=>"Triggers:",
			1=>$layout->DropDown("server_triggers[]", $triggers, "multiple") . " "
	)
,true);


$ov .=$layout->FormBox(
	
		array(
			0=>"Default Service Type:",
			1=>$layout->DropDown("default_service_type", $types) 
		)
	
, true);

if($fm_action == "add_server") {
	$ov .= $layout->FormBox(
			Array(
				0=>"Package:",
				1=>$layout->DropDown("package_name", $packages) 
			)
	,true);	
}
$ov .= $layout->FormBox(
			array(
				0=>"Icon:",
				1=>$layout->DropDown("server_icon", $server_icons, "onChange=\"serviceManageIconChange(this.form);\"") . "<div id=picholder></div><script>serviceManageIconChange(document.fm1);</script>" 
			)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Exec Plan:",
			1=>$plan_box
		)
,true);


$ov .= $layout->FormBox(
		array(
			0=>"Orchestra ID:",
			1=>$layout->orchDropdown(true, $defaults[orch_id])
	)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Web Hooks (one per line):",
			1=>$layout->TextArea("web_hooks", $defaults[web_hooks])
	)
,true);



$ov .= $layout->FormBox(
		array(
			0=>"JSON Endpoint:",
			1=>$layout->Field("json_endpoint", "text", $defaults[json_endpoint])
	)
,true);


$ov .= $layout->FormBox(
		array(
			0=>"<h4><b>SSH Options</b></h4>" . ''
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Keyfile Path",
			1=>$layout->Field("server_ssh_keyfile", "text", $defaults[server_ssh_keyfile])
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Key-Passphrase",
			1=>$layout->Field("server_ssh_passphrase", "text", $defaults[server_ssh_passphrase])
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Username ",
			1=>$layout->Field("server_ssh_username", "text", $defaults[server_ssh_username])
		)
,true);



$ov .= $layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyClient(xajax.getFormValues(\"fm1\"))'");


$ov .= "<script>function modify_service_make_24() {
			for(x=0; x<=6; x++) {
				e = document.getElementById('wdays_plan[' + x + ']');
				e.value='00:00-23:59';
			}
			
		}</script>";


$title="add server";  
$content = "" . $ov . "";
$layout->push_outside($layout->create_box($layout->BoxTitle, $content));
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);

//HIDE MAIN
$layout->boxes_placed[MAIN]=true;

$layout->FormEnd();
$layout->display();
