<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";



$btl=new BartlbyUi($Bartlby_CONF);





if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}


$layout= new Layout();

$layout->set_menu("worker");
$layout->setTitle("Modify Worker");
$defaults=@bartlby_get_worker_by_id($btl->RES, $_GET[worker_id]);

$layout->OUT .= "<script>global_worker_id=" . $_GET[worker_id] . ";</script>";

$fm_action="modify_worker";
if($_GET["copy"] == "true") {
	$btl->hasRight("action.copy_worker");
	$fm_action="add_worker";
	$layout->setTitle("Copy Worker");
}
if($_GET["new"] == "true") {
	$fm_action="add_worker";
	$layout->setTitle("Add Worker");
	$defaults="";
	
	
}

if($btl->user_id != $_GET[worker_id]) {
	$btl->hasRight("action." . $fm_action);
}
if(!$btl->isSuperUser() && $btl->user_id != $_GET[worker_id]) {
	$btl->hasRight("modify_all_workers");
}

if($defaults == false && $_GET["new"] != "true" && !$_GET[dropdown_search]) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}
if(!$defaults) {
	$defaults[escalation_limit]=50;
	$defaults[escalation_minutes]=3;
	$defaults["notify_plan"] = "0=00:00-23:59|1=00:00-23:59|2=00:00-23:59|3=00:00-23:59|4=00:00-23:59|5=00:00-23:59|6=00:00-23:59";
}

//$map = $btl->GetSVCMap();

$worker_rights=@$btl->loadForeignRights($defaults[worker_id]);

$optind=0;
$servers_out=array();
$services_x=0;
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out, &$services_x, &$worker_rights, &$defaults) {
	if(!$worker_rights[super_user]) {
		if(!@in_array($svc[server_id], $worker_rights[servers]) && !@in_array($svc[service_id], $worker_rights[services])) {
			return LOOP_CONTINUE;	
		}
	}
	if($svc[is_gone] != 0) {
	 return LOOP_CONTINUE;
	}
	if(($_GET[dropdown_term] &&  @preg_match("/" . $_GET[dropdown_term] . "/i", $svc[server_name] . "/" .  $svc[service_name]))
		|| 	(@in_array($svc[service_id], $worker_rights[services]))
		|| 	(@in_array($svc[server_id], $worker_rights[servers]))
		|| 	(@in_array($svc[service_id], $worker_rights[selected_services]))
		|| 	(@in_array($svc[server_id], $worker_rights[selected_servers]))
	 ) {
		if(!is_array($servers_out[$svc[server_id]])) {
			$servers_out[$svc[server_id]]=array();
		}
		array_push($servers_out[$svc[server_id]], $svc);
		
		$services_x++;
	}	//if($services_x > 50) return LOOP_BREAK;
	
});			
ksort($servers_out);

$optind=0;

$map = $servers_out;
reset($map);



while(list($k, $servs) = @each($map)) {

	for($x=0; $x<count($servs); $x++) {
		
		if($x == 0) {
			//$isup=$btl->isServerUp($v1[server_id]);
			//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }

			

			

			$servers[$optind][c]="";
			$servers[$optind][v]="s" . $servs[$x][server_id];	
			$servers[$optind][k]="" . $servs[$x][server_name] . "";
			$servers[$optind][is_group]=1;
			
			if(@in_array( $servs[$x][server_id], $worker_rights[selected_servers])) {
				
				$servers[$optind][s]=1;

			}
			$optind++;


			$state=$btl->getState($servs[$x][current_state]);
                $servers[$optind][c]="";
                        $servers[$optind][v]=$servs[$x][service_id];
                        $servers[$optind][k]=$servs[$x][server_name]  . "/" .  $servs[$x][service_name];


             

                       
                        if((@in_array($servs[$x][service_id], $worker_rights[selected_services]))) {
                        // || 	(@in_array($servs[$x][server_id], $worker_rights[selected_servers])) ) {
                        	$servers[$optind][s]=1;
                        }

                        $optind++;
		} else {
			
		
			$state=$btl->getState($servs[$x][current_state]);
			$servers[$optind][c]="";
			$servers[$optind][v]=$servs[$x][service_id];	
			$servers[$optind][k]=$servs[$x][server_name]  . "/" .  $servs[$x][service_name];
		
		
			//if(@in_array($servs[$x][service_id], $worker_rights[selected_services])) {
			if((@in_array($servs[$x][service_id], $worker_rights[selected_services])))  {
				//|| 	(@in_array($servs[$x][server_id], $worker_rights[selected_servers])) ) {

				$servers[$optind][s]=1;
			}
			$optind++;
		}
		
		
	}
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

$act[0][c]="";
$act[0][v]="0";
$act[0][k]="Inactive";
if($defaults[active] == 0) {
	$act[0][s]=1;
}

$act[1][c]="";
$act[1][v]="1";
$act[1][k]="Active";
if($defaults[active] == 1) {
	$act[1][s]=1;
}
$act[2][c]="";
$act[2][v]="2";
$act[2][k]="Standby";
if($defaults[active] == 2) {
	$act[2][s]=1;
}




$layout->OUT .= "<script>
		function modify_service_make_24() {
			for(x=0; x<=6; x++) {
				e = document.getElementById('wdays_plan[' + x + ']');
				e.value='00:00-23:59';
			}
			
		}
		function simulateTriggers() {
			wname=document.fm1.worker_name.value;
			wmail=document.fm1.worker_mail.value;
			wicq=document.fm1.worker_icq.value;
			TRR=document.fm1['worker_triggers[]'];
			wstr='|';
			for(x=0; x<=TRR.length-1; x++) {
				
				if(TRR.options[x].selected) {
					
					wstr =  wstr +  TRR.options[x].value + '|';	
				}
				
			}
			window.open('trigger.php?user='+wname+'&mail='+wmail+'&icq='+wicq+'&trs=' + wstr, 'tr', 'width=600, height=600, scrollbars=yes');
		}
		</script>
";


$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);
$layout->Table("100%");

if($defaults[is_super_user] == 1) $ss="checked";
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Is Super User",
			1=>"<font size=1>" . "<input type=checkbox value=1 name=is_super_user $ss>"
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Name",
			1=>$layout->Field("worker_name", "text", $defaults[name]) . $layout->Field("action", "hidden", $fm_action)
		)
	)
, true);
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Password:",
			1=>$layout->Field("worker_password", "password", "")
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Repeat password:",
			1=>$layout->Field("worker_password1", "password", "")
		)
	)
,true);


$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Mail",
			1=>$layout->Field("worker_mail", "text", $defaults[mail])
		)
	)
,true);
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"ICQ",
			1=>$layout->Field("worker_icq", "text", $defaults[icq])
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Notification Limit",
			1=>"<font size=1>" . $layout->Field("escalation_limit", "text", $defaults[escalation_limit]) . "notify's  per <br>" . $layout->Field("escalation_minutes", "text", $defaults[escalation_minutes]) .  " minutes</font>"
		)
	)
,true);
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Notification Aggregation Interval",
			1=>"<font size=1>" . $layout->Field("notification_aggregation_interval", "text", $defaults[notification_aggregation_interval]) . "</font>"
		)
	)
,true);

if($defaults[notification_aggregation_interval] == 1) $aggcheck="checked";
$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Aggregate Notifications:",
			1=>"<font size=1>" . "<input type=checkbox value=1 name=notification_aggregation_interval $aggcheck>"
		)
	)
,true);


$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Notifications:",
			1=>$layout->DropDown("worker_active", $act)
		)
	)
,true);


$o = explode("|", $defaults[notify_plan]);

for($x=0; $x<count($o); $x++) {
	$p = explode("=", $o[$x]);
	$filled[$p[0]]=$p[1];
	
}
$plan_box = "<table>";
for($x=0; $x<=6; $x++) {
	$chk="";
	if($filled[$x])
		$chk="checked";
		
	$plan_box .= "<tr><td><font size=1>" .  $wdays[$x] . "</font></td><td><input type=text id='wdays_plan[" . $x . "]'  name='wdays_plan[" . $x . "]' value='" . $filled[$x] . "' style='font-size:10px; width:200px; height:20px'></td></tr>";
}
$plan_box .= "<tr><td colspan=2><font size=1>Time ranges are seperated with ',' e.g.: 14:30-15:20,01:20-02:30 <a href='javascript:void(0);' onClick='modify_service_make_24();'>make 24h a day</a></font></td></tr>";
$plan_box .= "</table>";



$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Notify Plan:",
			1=>$plan_box
			
		)
	)
, true);




$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Services:",
			1=>$layout->DropDown("worker_services[]", $servers, "multiple","",true, "ajax_modify_worker_services")
		)
	)
, true);

if(strstr((string)$defaults[notify_levels], "|0|")) {
	$chk0="checked";	
}
if(strstr((string)$defaults[notify_levels], "|1|")) {
	$chk1="checked";	
}
if(strstr((string)$defaults[notify_levels], "|2|")) {
	$chk2="checked";	
}
if(strstr((string)$defaults[notify_levels], "|7|")) {
	$chk7="checked";	
}
if(strstr((string)$defaults[notify_levels], "|8|")) {
	$chk8="checked";	
}


$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Notifys:",
			1=>"<input type=checkbox value=0 name=notify[] $chk0><font color=green>OK</font><input value=1 type=checkbox name=notify[] $chk1><font color=orange>Warning</font><input value=2 type=checkbox name=notify[] $chk2><font color=red>Critical</font> <input type=checkbox value=7 name=notify[] $chk7><font color=gray>Sirene</font><input type=checkbox value=8 name=notify[] $chk8><font color=gray>Downtime</font>" 
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Triggers:",
			1=>$layout->DropDown("worker_triggers[]", $triggers, "multiple") . " <a href='javascript:simulateTriggers();'>Simulate</A>"
		)
	)
,true);




$ov .= $layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"left",
					'show'=>"<a href='modify_worker.php?copy=true&worker_id=" . $_GET[worker_id] . "'><img src='themes/classic/images/edit-copy.gif' title='Copy (Create a similar) this worker' border=0></A>"
					)
			)
		)

,true);

$title="";  
$content = "<table>" . $ov . "</table>";
$layout->push_outside($layout->create_box($layout->BoxTitle, $content));

$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);
	

$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyWorker(xajax.getFormValues(\"fm1\"))'") . $layout->Field("worker_id", "hidden", $_GET[worker_id])
					)
			)
		)

);


$layout->TableEnd();
$layout->FormEnd();
$layout->display();
