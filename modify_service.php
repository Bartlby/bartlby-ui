<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";




$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}
error_reporting(1);

$layout= new Layout();
$layout->setTitle("");


function dnl($i) {
	return sprintf("%02d", $i);
}
if($_GET[service_id]{0} == 's') {
	$layout->Form("fm1", "bartlby_action.php");
	$layout->Table("100%");

	
	$layout->Tr(
		$layout->Td(
			array(
				0=>'you have choosen a server',
				1=>'<input type=button value=back onClick="javascript:history.back();">'
				
			)
		)
	);
	
	$layout->TableEnd();
	$layout->FormEnd();
	$layout->display();
	exit(1);
	
	
} 
if($_GET[service_id]) {
	$btl->hasServerorServiceRight($_GET[service_id]);
}
$defaults=@bartlby_get_service_by_id($btl->RES, $_GET[service_id]);

$fm_action="modify_service";
$server_list_type="";
$server_field_name="service_server";

if($_GET["copy"] == "true") {
	$fm_action="add_service";
	$btl->hasRight("action.copy_service");
	$server_list_type="multiple";
	$server_field_name="service_server[]";
	$defaults[usid]=substr(sha1(time()), 0, 15); //NEW USID
}

if($_GET["new"] == "true") {
	$fm_action="add_service";
	$btl->hasRight("action.add_service");
	
	$defaults["exec_plan"] = "0=00:00-23:59|1=00:00-23:59|2=00:00-23:59|3=00:00-23:59|4=00:00-23:59|5=00:00-23:59|6=00:00-23:59";
	$defaults["check_interval"]=bartlby_config(getcwd() . "/ui-extra.conf", "new.service.interval");
	$defaults[service_type]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.service.type");
	$defaults[service_ack_enabled]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.service.ack");
	$defaults[service_retain]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.service.retain");
	
	$defaults[service_check_timeout]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.service.active.tcptimeout");
	$defaults[plugin]=bartlby_config(getcwd() . "/ui-extra.conf", "new.service.active.plugin");
	$defaults[service_args]=bartlby_config(getcwd() . "/ui-extra.conf", "new.service.active.arguments");
	
	$defaults[service_passive_timeout]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.service.passive.timeout");
	$defaults[flap_seconds]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.service.flap_seconds");
	
	$defaults[escalate_divisor]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.service.escalate_divisor");
	$defaults[renotify_interval]=(int)bartlby_config(getcwd() . "/ui-extra.conf", "new.service.renotify_interval");
	$defaults[prio]=50;
	$defaults[notify_super_users]=1;
	$defaults[usid]=substr(sha1(time()), 0, 15);
	
	$server_list_type="multiple";
	$server_field_name="service_server[]";	
	
}
if($fm_action == "modify_service") {
	$btl->hasRight("action.modify_service");
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
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

//ACKS



//Notify Enabled


if(is_int($defaults[notify_enabled]) && $defaults[notify_enabled] == 0) {
	$notenabled="";
	
} else {
	
	$notenabled="checked";
}

if(is_int($defaults[notify_super_users]) && $defaults[notify_super_users] == 0) {
	$notsuperenabled="";
	
} else {
	
	$notsuperenabled="checked";
}

$handled[0][c]="";
$handled[0][v] = 0; //No
$handled[0][k] = "Unhandled"; //No
$handled[0][s]=0;

$handled[1][c]="";
$handled[1][v] = 1; //No
$handled[1][k] = "Handled"; //No
$handled[1][s]=0;

//Events Enabled
$eventenabled[0][c]="";
$eventenabled[0][v] = 0; //No
$eventenabled[0][k] = "Disabled"; //No
$eventenabled[0][s]=0;

$eventenabled[1][c]="";
$eventenabled[1][v] = 1; //No
$eventenabled[1][k] = "HARD"; //No
$eventenabled[1][s]=0;

$eventenabled[2][c]="";
$eventenabled[2][v] = 2; //No
$eventenabled[2][k] = "SOFT"; //No
$eventenabled[2][s]=0;


$eventenabled[3][c]="";
$eventenabled[3][v] = 3; //No
$eventenabled[3][k] = "BOTH"; //No
$eventenabled[3][s]=0;


if(is_int($defaults[handled]) && $defaults[handled] == 1) {
	$handled[1][s]=1;	
	
} else {
	$handled[0][s]=1;	
}

if(is_int($defaults[fires_events]) && $defaults[fires_events] == 1) {
	$eventenabled[1][s]=1;	
	
} else if (is_int($defaults[fires_events]) && $defaults[fires_events] == 2) {
	
	$eventenabled[2][s]=1;
} else if(is_int($defaults[fires_events]) && $defaults[fires_events] == 3) {
	$eventenabled[3][s]=1;
} else {
	$eventenabled[0][s]=1;
}





if(is_int($defaults[service_active]) && $defaults[service_active] == 0) {
	$servactive="";
} else {
	$servactive="checked";
}




if(is_int($defaults[service_ack_enabled]) && $defaults[service_ack_enabled] == 0) {
	$ack="";
} else {

	$ack = "checked";
}




$types[0][c]="";
$types[0][v]="1";
$types[0][k]="Active";

if($defaults[service_type] == 1) {
	$types[0][s]=1;
}

$types[1][c]="";
$types[1][v]="2";
$types[1][k]="Passive";
if($defaults[service_type] == 2) {
	$types[1][s]=1;
}

$types[2][c]="";
$types[2][v]="3";
$types[2][k]="Group";
if($defaults[service_type] == 3) {
	$types[2][s]=1;
}

$types[3][c]="";
$types[3][v]="4";
$types[3][k]="Local";
if($defaults[service_type] == 4) {
	$types[3][s]=1;
}
$types[4][c]="";
$types[4][v]="5";
$types[4][k]="SNMP";
if($defaults[service_type] == 5) {
	$types[4][s]=1;
}

$types[5][c]="";
$types[5][v]="6";
$types[5][k]="NRPE";
if($defaults[service_type] == 6) {
	$types[5][s]=1;
}

$types[6][c]="";
$types[6][v]="7";
$types[6][k]="NRPE(ssl)";
if($defaults[service_type] == 7) {
	$types[6][s]=1;
}

$types[8][c]="";
$types[8][v]="9";
$types[8][k]="AgentV2(no-SSL)";
if($defaults[service_type] == 9) {
	$types[8][s]=1;
}

$types[7][c]="";
$types[7][v]="8";
$types[7][k]="AgentV2";
if($defaults[service_type] == 8) {
	$types[7][s]=1;
}


$types[9][c]="";
$types[9][v]="10";
$types[9][k]="SSH";
if($defaults[service_type] == 10) {
	$types[9][s]=1;
}

$types[10][c]="";
$types[10][v]="11";
$types[10][k]="Trap";
if($defaults[service_type] == 11) {
	$types[10][s]=1;
}

if(!$defaults[service_type]) {
	$types[0][s]=1;	
}
//Get plugins :))

$plg_drop=null;
$plgs=bartlby_config($btl->CFG, "agent_plugin_dir");
$dh=opendir($plgs);
while ($file = readdir ($dh)) { 
   if ($file != "." && $file != "..") { 
	   	clearstatcache();
	   	if(is_dir($plgs . "/" . $file)) continue;

	   	if(($_GET[dropdown_term] && preg_match("/" . $_GET[dropdown_term] . "/", $file)) || $file == $defaults[plugin]) {
	   			$el[c] =  "";
	   			$el[v] = $file;
	   			$el[k] = $file;
	   			$el[s]=0;
	   			if($defaults[plugin] == $file) {
	   				$el[s]=1;
	   			}
	   			$plg_drop[]=$el;
	   }
	}
}
closedir($dh); 




$layout->set_menu("services");


$optind=0;
$servers=array();
$server_orch_id=-1;
$btl->server_list_loop(function($srv, $shm) use (&$optind, &$servers, &$defaults, &$server_orch_id) {
	global $_GET;
	if(($_GET[dropdown_term] && preg_match("/" . $_GET[dropdown_term] . "/", $srv[server_name])) || $srv[server_id] == $defaults[server_id]) {
		$servers[$optind][c]="";
		$servers[$optind][v]=$srv[server_id];	
		$servers[$optind][k]=$srv[server_name];
		if($defaults[server_id] == $srv[server_id]) {
			$server_orch_id=$srv[orch_id];
			$servers[$optind][s]=1;	
		}
		$optind++;
	}
	

});

	

$layout->OUT .= "<script>

		function modify_service_make_24() {
			for(x=0; x<=6; x++) {
				e = document.getElementById('wdays_plan[' + x + ']');
				e.value='00:00-23:59';
			}
			
		}
	
		function testPlg() {
		plugin=document.fm1.service_plugin.value;
		server=document.fm1.service_server.options[document.fm1.service_server.selectedIndex].value;
		plg_args=document.fm1.service_args.value;
		window.open('check.php?server=' + server +  '&plugin=' + plugin + '&args=' + plg_args, 'chk','width=600, height=600, scrollbars=yes'); 
		}
		function showPlgHelp() {
			plugin=document.fm1.service_plugin.value;
			window.open('execv.php?cmd='+plugin+' -h', 'plgwnd', 'width=600, height=600');
		}
		function GrpChk() {
			window.open('grpstr.php?str='+document.fm1.service_var.value, 'grp', 'width=750, height=600, scrollbars=yes');
		}
		function CheckTables() {
			va=document.fm1.service_type.options[document.fm1.service_type.selectedIndex].value;
			GenericToggleFix(\"active\", \"none\");
			GenericToggleFix(\"passive\", \"none\");
			GenericToggleFix(\"group\", \"none\");
			GenericToggleFix(\"snmp\", \"none\");
			
			if(va == 2) {
				GenericToggleFix(\"passive\", \"block\");
				GenericToggleFix(\"active\", \"block\");
			}
			if(va == 1 || va == 6 || va == 7 || va == 8 ||  va == 9 || va == 10 || va == 11) {
				GenericToggleFix(\"active\", \"block\");
			}
			if(va == 3) {
				GenericToggleFix(\"group\", \"block\");	
			}
			if(va == 4) {
				GenericToggleFix(\"active\", \"block\");	
			}
			if(va == 5) {
				GenericToggleFix(\"snmp\", \"block\");	
			}
			
		}
		CheckTables();
		</script>
";







$active_box_out .=$layout->FormBox(
		array(
			0=>"Type",
			1=> $layout->DropDown("service_type", $types,"onChange=\"CheckTables()\"")  . $layout->Field("use_server_default_type", "checkbox", "1", "" ,'class="icheck"') . "Use 'Server Default Type'"

			
		)
, true);

$active_box_out .= $layout->FormBox(
		array(
			0=>"Name",
			1=>$layout->Field("service_name", "text", $defaults[service_name]) . $layout->Field("action", "hidden", $fm_action)
		)
,true);
$active_box_out .= $layout->FormBox(
		array(
			0=>"Server(s)",
			1=>$layout->DropDown($server_field_name, $servers, $server_list_type,"",false, "ajax_server_list_php")
			
		)
,true);



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
		
	$plan_box .= "<tr><td><font size=1>" .  $wdays[$x] . "</font></td><td><input type=text id='wdays_plan[" . $x . "]'  name='wdays_plan[" . $x . "]' value='" . $filled[$x][value] . "' style='font-size:10px; width:200px; height:20px'><input type=checkbox id='wdays_inv[" . $x . "]'  name='wdays_inv[" . $x . "]' $inv_check class=icheck> invert</td></tr>";
	
}
$plan_box .= "<tr><td colspan=2><font size=1>Time ranges are seperated with ',' e.g.: 14:30-15:20,01:20-02:30 <a href='javascript:void(0);' onClick='modify_service_make_24();'>make 24h a day</a></font></td></tr>";
$plan_box .= "</tbody></table>";



$active_box_out .= $layout->FormBox(
		array(
			0=>"Check Plan:",
			1=>$plan_box
			
		)
, true);



$timing_box_out .= $layout->FormBox(
		array(
			0=>"Check intervall",
			1=>$layout->Field("service_interval", "text", $defaults[check_interval]) . " Seconds"
			
		)
,true);

$toggle_box_out .= $layout->FormBox(
		array(
			0=>"Checks Enabled?",
			1=>$layout->Field("service_active", "checkbox", "1", "", "class='switch' " . $servactive)
			
			
	)
,true);

$toggle_box_out .= $layout->FormBox(
		array(
			0=>"Notification enabled",
			1=>$layout->Field("notify_enabled", "checkbox", "1", "", "class='switch' " . $notenabled)
			
			
	)
,true);


$toggle_box_out .= $layout->FormBox(
		array(
			0=>"Fires Events?",
			1=>$layout->DropDown("fires_events", $eventenabled)
			
		)
,true);


$toggle_box_out .= $layout->FormBox(
		array(
			0=>"Problem Handled",
			1=>$layout->DropDown("handled", $handled)
			
		)
,true);

$toggle_box_out .= $layout->FormBox(
		array(
			0=>"Acknowledgement",
			1=>$layout->Field("service_ack_enabled", "checkbox", "1", "", "class='switch' " . $ack)
			
			
		)
,true);



$timing_box_out .=$layout->FormBox(
		array(
			0=>"Retain Count",
			1=>$layout->Field("service_retain", "text", $defaults[service_retain]) . " Times"
			
		)
,true);
$timing_box_out .=$layout->FormBox(
		array(
			0=>"Flap time threshold",
			1=>$layout->Field("flap_seconds", "text", $defaults[flap_seconds]) . " seconds"
			
		)
,true);


$timing_box_out .=$layout->FormBox(
		array(
			0=>"Re-Notification",
			1=>$layout->Field("renotify_interval", "text", $defaults[renotify_interval]) . " runs"
			
		)
,true);

$timing_box_out .=$layout->FormBox(
		array(
			0=>"Escalate",
			1=>$layout->Field("escalate_divisor", "text", $defaults[escalate_divisor]) . " runs"
			
		)
,true);

$active_box_out .= $layout->FormBox(
		array(
			0=>"Triggers:",
			1=>$layout->DropDown("service_triggers[]", $triggers, "multiple") . " "
		)
,true);



$toggle_box_out .= $layout->FormBox(
		array(
			0=>"Priority:",
			1=>'<input  class="form-control service_deepnes" style="width:70%; type="text"  data-slider-value="' . $defaults[prio] . '" data-slider-step="1" data-slider-max="100" data-slider-min="0" value="">',
			2=>$layout->Field("prio", "text", $defaults[prio]) 
		)
,true);

$orch_box_out .= $layout->FormBox(

		array(
			0=>"Uniqe Service Identifier:",
			1=>$layout->Field("usid", "text", $defaults[usid]) 
		)
,true);

$toggle_box_out .= $layout->FormBox(
		array(
			0=>"Notify Super Users by default?",
			1=>$layout->Field("notify_super_users", "checkbox", "1", "", "class='switch' " . $notsuperenabled)
			
			
			
		)

,true);

$orch_box_out .= $layout->FormBox(
		array(
			0=>"Orchestra ID:",
			1=>$layout->orchDropdown(false, -1)
		)
,true);



$layout->create_box("Basic Settings", '<span class="form-horizontal" role="form"> ' . $active_box_out . "</span>", "basic");


$layout->create_box("Toggles", '<span class="form-horizontal" role="form"> ' . $toggle_box_out . "</span>", "toggles");

$layout->create_box("Cluster/Orchestra Settings", '<span class="form-horizontal" role="form"> ' . $orch_box_out . "</span>", "orch");

$layout->create_box("Timing Settings", '<span class="form-horizontal" role="form"> ' . $timing_box_out . "</span>", "timing");


$active_box_out="";


$active_box_out .= $layout->FormBox(
		array(
			0=>"Timeout",
			1=>$layout->Field("service_check_timeout", "text", $defaults[service_check_timeout])
			
		)
,true);

$active_box_out .=$layout->FormBox(

		array(
			0=>"Plugin",
			//ajax_plugin_search
			
			1=>$layout->DropDown("service_plugin", $plg_drop, "multiple","",false, "ajax_plugin_search") . " " . "<a class='btn btn-primary' href='javascript:showPlgHelp();'><i class='fa fa-lightbulb-o'></i>Help</A>&nbsp;&nbsp;<a class='btn btn-success' href='javascript:testPlg();'><i class='fa fa-play'></i>  Test It</A>"
		)
,true);

 $defaults[plugin_arguments]=str_replace("\n", "\\n",  $defaults[plugin_arguments]);
 $defaults[plugin_arguments]=str_replace("\r", "\\r",  $defaults[plugin_arguments]);

$active_box_out .= $layout->FormBox(
		array(
			0=>"Arguments",
			1=>$layout->Field("service_args", "text", $defaults[plugin_arguments])
			
		)
,true);


$layout->create_box("Active/Local Settings", '<span class="form-horizontal" role="form"> ' . $active_box_out . "</span>", "active");


$active_box_out="";

$active_box_out .= $layout->FormBox(
		array(
			0=>"Timeout",
			1=>$layout->Field("service_passive_timeout", "text", $defaults[service_passive_timeout])
			
		)
,true);



$layout->create_box("Passive Settings", '<span class="form-horizontal" role="form"> ' . $active_box_out . "</span>", "passive");

$active_box_out="";

$active_box_out .= $layout->FormBox(
		array(
			0=>"Group definition",
			
			1=>$layout->Field("service_var", "hidden", $defaults[service_var]) . "<a href='javascript:GrpChk();'>Open Group selector</A>"
			
		)
,true);
$layout->create_box("Group Settings", '<span class="form-horizontal" role="form"> ' . $active_box_out . "</span>", "group");

$active_box_out="";


$snmptypes[0][c]="";
$snmptypes[0][v]="1";
$snmptypes[0][k]="Lower";


if($defaults[snmp_type] == 1) {
	 $snmptypes[0][s]=1;
}

$snmptypes[1][c]="";
$snmptypes[1][v]="2";
$snmptypes[1][k]="Greater";

if($defaults[snmp_type] == 2) {
	 $snmptypes[1][s]=1;
}

$snmptypes[2][c]="";
$snmptypes[2][v]="3";
$snmptypes[2][k]="Equal";

if($defaults[snmp_type] == 3) {
	 $snmptypes[2][s]=1;
}

$snmptypes[3][c]="";
$snmptypes[3][v]="4";
$snmptypes[3][k]="Not-Equal";

if($defaults[snmp_type] == 4) {
	 $snmptypes[3][s]=1;
}

$snmptypes[4][c]="";
$snmptypes[4][v]="5";
$snmptypes[4][k]="contains";

if($defaults[snmp_type] == 5) {
	 $snmptypes[4][s]=1;
}


$snmpversions[0][c]="";
$snmpversions[0][v]="1";
$snmpversions[0][k]="1";


if($defaults[snmp_version] == 1) {
	 $snmpversions[0][s]=1;
}

$snmpversions[1][c]="";
$snmpversions[1][v]="2";
$snmpversions[1][k]="2c";

if($defaults[snmp_version] == 2) {
	 $snmpversions[1][s]=1;
}


$active_box_out .= $layout->FormBox(

		array(
			0=>"Community",
			1=>$layout->Field("snmp_community", "text", $defaults[snmp_community])
			
		)
,true);
$active_box_out .= $layout->FormBox(

		array(
			0=>"OBJ ID",
			1=>$layout->Field("snmp_objid", "text", $defaults[snmp_objid])
			
		)
,true);
$active_box_out .= $layout->FormBox(
		array(
			0=>"Version",
			1=>$layout->DropDown("snmp_version", $snmpversions, "") 
			
		)
,true);
$active_box_out .= $layout->FormBox(
		array(
			0=>"Warning",
			1=>$layout->Field("snmp_warning", "text", $defaults[snmp_warning])
			
		)

,true);
$active_box_out .= $layout->FormBox(
		array(
			0=>"Critical",
			1=>$layout->Field("snmp_critical", "text", $defaults[snmp_critical])
			
		)
,true);

$active_box_out .= $layout->FormBox(

		array(
			0=>"TextMatch",
			1=>$layout->Field("snmp_textmatch", "text", $defaults[snmp_textmatch])
			
		)
,true);

$active_box_out .= $layout->FormBox(
		array(
			0=>"MatchType",
			1=>$layout->DropDown("snmp_type", $snmptypes, "") 
			
		)
,true);

$layout->create_box("SNMP Settings", '<span class="form-horizontal" role="form"> ' . $active_box_out . "</span>", "snmp");

$active_box_out="";

if(!$_GET["copy"] && !$_GET["new"]) {
	$idx=$btl->findSHMPlace($_GET[service_id]);
	
	$ssvc=bartlby_get_service($btl->RES, $idx);
	
	if($ssvc[service_active] == 1) {
		bartlby_toggle_service_active($btl->RES, $idx, 0);
		$dounlock=$idx;
			
	
	$layout->FormBox(
			array(
					0=>"",
					1=>"<input type=hidden name=unlock value='" . $dounlock . "'><font color=red>the service check has been disabled until you hit save, if you don't do this the service remains inactive</font>"
					)
		);
	}
}

$layout->FormBox(
			array(
				0=>"",
				1=>$layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyService(xajax.getFormValues(\"fm1\"))'") . $layout->Field("service_id", "hidden", $_GET[service_id])
				
			)
);


	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);


$layout->display("modify_service");

