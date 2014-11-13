<?

include "config.php";
include "layout.class.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasright("log.logview");
$info=$btl->getInfo();
$layout= new Layout();
$layout->set_menu("report");

if($_GET[bartlby_filter]) $_GET[text_filter]=$_GET[bartlby_filter];
if($_GET[sSearch]) $_GET[text_filter]=$_GET[sSearch];

if($_GET[datatables_output] == 1) {
	$ch_time=time();
	if($_GET[date_filter]) {
		$tt=explode("/",$_GET[date_filter]);
		//var_dump($tt);
		$ch_time=mktime(0,0,0,$tt[0],$tt[1],$tt[2]);	
	} else {
		$_GET[date_filter]=date("m/d/Y");
	}
	$handle = "";
	if($_GET[handle_filter] && $_GET[handle_filter] != "MAIN") $handle="." . $_GET[handle_filter];
	$logf=bartlby_config($btl->CFG, "logfile") .  date(".Y.m.d", $ch_time) . $handle;
	$xc = 0;

	
	$fla=@file($logf);
	$fl=@array_reverse($fla);
	$ajax_total_records=0;
	$ajax_displayed_records=0;
	while(list($k, $v)=@each($fl)) {
		$ajax_total_records++;

		$info_array=explode(";",$v);
		$log_detail_o=explode("@", $info_array[2]);
		$hstate="";
		if($log_detail_o[1] == "PERF") {
			$tmp=explode("|", $log_detail_o[2]);
			if($_GET[server_id] && !cmpServiceIDHasServer($tmp[0], $_GET[server_id])) {
				continue;	
			}
			if($_GET[servergroup_id] && !cmpServiceIDisInServerGroup($tmp[0], $_GET[servergroup_id])) {
				continue;	
			}
		
			if($_GET[servicegroup_id] && !cmpServiceIDisInServiceGroup($tmp[0], $_GET[servicegroup_id])) {
				continue;	
			}
			
			if($_GET[service_id] && $tmp[0] != $_GET[service_id]) {
				continue;	
			}
			if(!$btl->hasServerorServiceRight($tmp[0], false)) {
				continue;	
			}
			
			$outline = "" . $tmp[1]  . "(" . $tmp[0] . ")";
			$stcheck=6;
		} else if($log_detail_o[1] == "LOG") {

			$tmp=explode("|", $log_detail_o[2]);
			if($_GET[server_id] && !cmpServiceIDHasServer($tmp[0], $_GET[server_id])) {
				continue;	
			}
			if($_GET[servergroup_id] && !cmpServiceIDisInServerGroup($tmp[0], $_GET[servergroup_id])) {
				continue;	
			}
			if($_GET[servicegroup_id] && !cmpServiceIDisInServiceGroup($tmp[0], $_GET[servicegroup_id])) {
				continue;	
			}
			if($_GET[service_id] && $tmp[0] != $_GET[service_id]) {
				
				continue;	
			}
			if(!$btl->hasServerorServiceRight($tmp[0], false)) {
				continue;	
			}
			$log_el = explode("|", $v);
			$clean="";
			for($z=3; $z<count($log_el);$z++) {
				if(preg_match("/HARD;CHECK\/HASTO$/", $log_el[$z])) {
					$hstate = "<br>(HARD)";
					break;
				} 
				if(preg_match("/SOFT;CHECK\/HASTO$/", $log_el[$z])) {
					$hstate = "<br>(SOFT)";
					break;
				}
				$clean .=  $log_el[$z] . " ";	
			}
			$outline = "<a href='logview.php?text_filter=" . $_GET["bartlby_filter"] . "&servicegroup_id=$svcgrpid&servergroup_id=$srvgrpid&server_id=$srvid&service_id=" . $tmp[0] . "&l=" . date("Y.m.d", $ch_time)  . "'>" . $tmp[2] . "</A> changed to " . $btl->getState($tmp[1]) . "<br>" . $clean . "<br>";
			$stcheck=$tmp[1];
		}else if($log_detail_o[1] == "KILL") {
			$tmp=explode("|", $log_detail_o[2]);
			if($_GET[server_id] && !cmpServiceIDHasServer($tmp[0], $_GET[server_id])) {
				continue;	
			}
			if($_GET[servergroup_id] && !cmpServiceIDisInServerGroup($tmp[0], $_GET[servergroup_id])) {
				continue;	
			}
			if($_GET[servicegroup_id] && !cmpServiceIDisInServiceGroup($tmp[0], $_GET[servicegroup_id])) {
				continue;	
			}
			if($_GET[service_id] && $tmp[0] != $_GET[service_id]) {
				
				continue;	
			}
			if(!$btl->hasServerorServiceRight($tmp[0], false)) {
				continue;	
			}
			$clean = htmlentities($tmp[3]);
			$outline = "<a href='logview.php?text_filter=" . $_GET["bartlby_filter"] . "&servicegroup_id=$svcgrpid&servergroup_id=$srvgrpid&server_id=$srvid&service_id=" . $tmp[0] . "&l=" . date("Y.m.d", $ch_time)  . "'>" . $tmp[2] . "</A><br>" . $clean . "<br>";
			$stcheck=8;
		} else if($log_detail_o[1] == "NOT") {
			$tmp=explode("|", $log_detail_o[2]);
			if($_GET[server_id] && !cmpServiceIDHasServer($tmp[0], $_GET[server_id])) {
				continue;	
			}
			if($_GET[servergroup_id] && !cmpServiceIDisInServerGroup($tmp[0], $_GET[servergroup_id])) {
				continue;	
			}
		
			if($_GET[servicegroup_id] && !cmpServiceIDisInServiceGroup($tmp[0], $_GET[servicegroup_id])) {
				continue;	
			}
			if($_GET[service_id] && $tmp[0] != $_GET[service_id]) {
				
				continue;	
			}
			if(!$btl->hasServerorServiceRight($tmp[0], false)) {
				continue;	
			}
			$outline =  "Done " . $tmp[3] . " for " . $tmp[4] . " Service:<a href='logview.php?text_filter=" . $_GET["bartlby_filter"] . "&servicegroup_id=$svcgrpid&servergroup_id=$srvgrpid&server_id=$srvid&service_id=" . $tmp[0] . "&l=" . date("Y.m.d", $ch_time)  . "'>" .  $tmp[5] . "</A> " . $btl->getState($tmp[2]);
			$stcheck=5;	
			
		} else if($log_detail_o[1] == "NOT-EXT") {
			$tmp=explode("|", $log_detail_o[2]);
			
			if($_GET[server_id] && !cmpServiceIDHasServer($tmp[0], $_GET[server_id])) {
				continue;	
			}
			if($_GET[servergroup_id] && !cmpServiceIDisInServerGroup($tmp[0], $_GET[servergroup_id])) {
				continue;	
			}
		
			if($_GET[servicegroup_id] && !cmpServiceIDisInServiceGroup($tmp[0], $_GET[servicegroup_id])) {
				continue;	
			}
			
			if($_GET[service_id] && $tmp[0] != $_GET[service_id]) {
				
				continue;	
			}
			if(!$btl->hasServerorServiceRight($tmp[0], false)) {
				continue;	
			}
			$outline =  $tmp[3] . " for " . $tmp[4] . " Service:<a href='logview.php?text_filter=" . $_GET["bartlby_filter"] . "&servicegroup_id=$svcgrpid&servergroup_id=$srvgrpid&server_id=$srvid&service_id=" . $tmp[0] . "&l=" . date("Y.m.d", $ch_time)  . "'>" .  $tmp[5] . "</A> " . $tmp[6];
			$stcheck=7;	
		} else if($log_detail_o[1] == "EV-HANDLER") {
			$tmp=explode("|", $log_detail_o[2]);
			
			if($_GET[server_id] && !cmpServiceIDHasServer($tmp[0], $_GET[server_id])) {
				continue;	
			}
			if($_GET[servergroup_id] && !cmpServiceIDisInServerGroup($tmp[0], $_GET[servergroup_id])) {
				continue;	
			}
		
			if($_GET[servicegroup_id] && !cmpServiceIDisInServiceGroup($tmp[0], $_GET[servicegroup_id])) {
				continue;	
			}
			if($_GET[service_id] && $tmp[0] != $_GET[service_id]) {
				
				continue;	
			}
			if(!$btl->hasServerorServiceRight($tmp[0], false)) {
				continue;	
			}
			$clean = htmlentities($tmp[3] . "-" . $tmp[4]);
			$outline = "<a href='logview.php?text_filter=" . $_GET["bartlby_filter"] . "&servicegroup_id=$svcgrpid&servergroup_id=$srvgrpid&server_id=$srvid&service_id=" . $tmp[0] . "&l=" . date("Y.m.d", $ch_time)  . "'>" . $tmp[2] . "</A> event handler called STATE: "  . $clean . "<br>";
			
			$stcheck=7;	
		}else if($log_detail_o[1] == "FORCE") {
			$tmp=explode("|", $log_detail_o[2]);
			
			if($_GET[server_id] && !cmpServiceIDHasServer($tmp[0], $_GET[server_id])) {
				continue;	
			}
			if($_GET[servergroup_id] && !cmpServiceIDisInServerGroup($tmp[0], $_GET[servergroup_id])) {
				continue;	
			}
		
			if($_GET[servicegroup_id] && !cmpServiceIDisInServiceGroup($tmp[0], $_GET[servicegroup_id])) {
				continue;	
			}
			if($_GET[service_id] && $tmp[0] != $_GET[service_id]) {
				
				continue;	
			}
			if(!$btl->hasServerorServiceRight($tmp[0], false)) {
				continue;	
			}
			$outline = "Force Service:<a href='logview.php?text_filter=" . $_GET["bartlby_filter"] . "&servicegroup_id=$svcgrpid&servergroup_id=$srvgrpid&server_id=$srvid&service_id=" . $tmp[0] . "&l=" . date("Y.m.d", $ch_time)  . "'>" .  $tmp[5] . "</A> " . $tmp[6];
			$stcheck=3;	
		} elseif(!$_GET[service_id] && !$_GET[server_id] && !$_GET[servergroup_id] && !$_GET[servicegroup_id]) {
			if(!$btl->hasRight("sysmessages", false)) {
				continue;	
			}
			
			$outline = $info_array[2];
			$stcheck=3;
				
		} else {
			continue;	
		}
		
		
		
		$date=$info_array[0];
		switch($stcheck) {
			case 0: $img="<span class='label label-success'>OK</span>" . $hstate; break;
			case 1: $img="<span class='label label-warning'>Warning</span>" . $hstate; break;
			case 2: $img="<span class='label label-danger'>Critical</span>" . $hstate; break;
			case 3: $img="<span class='label label-default'>Info</span>" . $hstate; break;	
			case 4: $img="<span class='label label-default'>Info</span>" . $hstate; break;
			case 5: $img="<span class='label label-primary'>Trigger</span>" . $hstate; break;
			case 6: $img="<span class='label label-default'>Info</span>" . $hstate; break;
			case 7: $img="<span class='label label-primary'>Notification</span>" . $hstate; break;
			case 8: $img="<span class='label label-default'>Info</span>" . $hstate; break;
		}
		
		if(preg_match("/^AgentSyncer.*/i", $outline)) {
			$img = "<span class='label label-primary'>Sync</span>";	

		}
		
		if(preg_match("/" . $_GET["text_filter"] . "/i", $v)) {

			$ajax_displayed_records++;
				
				if($xc >= $_GET[iDisplayStart] && $xc < $_GET[iDisplayStart]+$_GET[iDisplayLength]) {
					$ajax_search["aaData"][] = array($date, $img, $outline);
							
				}
				$xc++;

		}
	}


	$json_ret["iTotalRecords"] = $ajax_total_records;
	$json_ret["iTotalDisplayRecords"] = $ajax_displayed_records;
	$json_ret["sEcho"] = (int)$_GET[sEcho];
			
	
	$json_ret["aaData"] = $ajax_search["aaData"];
	if(!is_array($json_ret["aaData"])) {
			$json_ret["aaData"]=array();
	}
	echo json_encode(utf8_encode_all($json_ret));
	exit;

	
}	


$info_box_title='Filter';  

$layout->create_box($info_box_title, $core_content, "logview_filter", array(
											"FILTER" => $_GET											
											)
		, "logview_filter", false, false);


$info_box_title='Log';  
$layout->create_box($info_box_title, $core_content, "logview_table", array(
											"FILTER" => $_GET											
											)
		, "logview_table", false, false);

$layout->boxes_placed[MAIN]=true;

$layout->display("logview");

function cmpServiceIDisInServiceGroup($svc_id, $servicegroup_id) {
			global $btl;
			$r = false;
$btl->service_list_loop(function($svc, $shm) use(&$r) {
			if($svc[service_id] == $svc_id) {
				for($y=0; $y<count($svc[servicegroups]); $y++) {
				
						if($svc[servicegroups][$y][servicegroup_id] == $servicegroup_id) {
							$r=true;
							return LOOP_BREAK;
						}
				}
			}
});
		
	return $r;	
}

function cmpServiceIDisInServerGroup($svc_id, $servergroup_id) {
			global $btl;
			$r = false;
$btl->service_list_loop(function($svc, $shm) use(&$r) {
			if($svc[service_id] == $svc_id) {
				for($y=0; $y<count($svc[servergroups]); $y++) {
				
						if($svc[servergroups][$y][servergroup_id] == $servicegroup_id) {
							$r=true;
							return LOOP_BREAK;
						}
				}
			}
}); 
		
	return $r;	
}

function cmpServiceIDHasServer($svc_id, $server_id) {
	global $btl;
	
	$found = false;

	$btl->service_list_loop(function($svc, $shm) use(&$found, &$server_id, &$svc_id) {
			if($svc[service_id] == $svc_id && $svc[server_id] == $server_id) {
				$found=true;
				return LOOP_BREAK;
			}
	});
	return $found;	
}


