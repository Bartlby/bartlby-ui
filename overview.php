<?php
/* $Id: ack.c 16 2008-04-07 19:20:34Z hjanuschka $ */
/* ----------------------------------------------------------------------- *
 *
 *   Copyright 2005-2008 Helmut Januschka - All Rights Reserved
 *   Contact: <helmut@januschka.com>, <contact@bartlby.org>
 *
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, Inc., 675 Mass Ave, Cambridge MA 02139,
 *   USA; either version 2 of the License, or (at your option) any later
 *   version; incorporated herein by reference.
 *
 *   visit: www.bartlby.org for support
 * ----------------------------------------------------------------------- */
/*
$Revision: 16 $
$HeadURL: http://bartlby.svn.sourceforge.net/svnroot/bartlby/trunk/bartlby-core/src/ack.c $
$Date: 2008-04-07 21:20:34 +0200 (Mo, 07 Apr 2008) $
$Author: hjanuschka $ 
*/

	include "config.php";

	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$layout= new Layout();
	$layout->do_auto_reload=true;

	$btl->hasRight("main.overview");
	$layout->setMainTabName("Overview");
	
	$layout->set_menu("main");
	//$layout->MetaRefresh(30);
	$layout->Table("100%");
	$lib=bartlby_lib_info($btl->RES);
	$info=$btl->info;
	
	$reload_status="data is up-to-date";
	
	if ($info[sirene_mode] == 0) {
		$sir="<a href='#' title='Enable Sirene' onClick=\"document.location.href='bartlby_action.php?action=sirene_enable';\"><img border=0 title='Enable Sirene' src='themes/" . $layout->theme . "/images/Disable-Sirene.gif'></A>";	
	} else {
		$sirene_intv=bartlby_config($btl->CFG, "sirene_interval");
		if(!$sirene_intv) {
			$sirene_intv=600;
		}
		$sir="Notify Every: $sirene_intv Seconds <a title='Disable Sirene' href='#' onClick=\"document.location.href='bartlby_action.php?action=sirene_disable';\"><img border=0 title='Disable Sirene' src='" . $layout->theme . "images/Enable-Sirene.gif'></A>";	
	}
	
	
	
	$quickview_disabled=bartlby_config(getcwd() . "/ui-extra.conf", "quickview_enabled");
	
	if($info[round_time_count] > 0 &&  $info[round_time_sum] > 0 ) {
		$rndMS=round($info[round_time_sum] / $info[round_time_count], 2);
	} else {
		$rndMS=0;	
	}
	
		
	
	//if($_GET[json]) {
//		$servers=$btl->GetSVCMap();
	//}
	
	
	
	$hosts_sum=count($servers);
	$hosts_up=0;
	$hosts_down=0;
	$services_critical=0;
	$services_info=0;
	$services_ok=0;
	$services_warning=0;
	$services_unkown=0;
	$services_downtime=0;
	$services_handled=0;
	$all_services=0;
	$acks_outstanding=0;
	$gdelay_count = 0;
	$gdelay_sum = 0;
	$server_state_a=array();
	//

	$btl->service_list_loop(function($v)  {
			//service_delay_sum

			global $reload_status, $hosts_down, $hosts_up, $services_critical, $services_critical, $services_info, $services_ok, $services_warning, $services_unkown, $services_downtime, $all_services, $acks_outstanding, $gdelay_sum, $gdelay_count, $service_state_a, $server_state_a;
			global $services_handled;

			$gdelay_sum += $v[service_delay_sum];
			$gdelay_count += $v[service_delay_count];
			
			if($v[is_gone]) $reload_status="<font color=red>Reload needed</font>";		
			
			$service_state_a[$v[service_id]][$v[current_state]]++;	
			$server_state_a[$v[server_id]][$v[current_state]]++;	
			

			$qck[$v[server_id]][$v[current_state]]++;	
			$qck[$v[server_id]][10]=$v[server_id];
			$qck[$v[server_id]][server_icon]=$v[server_icon];
			$qck[$v[server_id]][server_name]=$v[server_name];
			if($v[is_downtime] == 1) {
				$qck[$v[server_id]][$v[current_state]]--;
				$qck[$v[server_id]][downtime]++;
				
			}
			if($v[handled] == 1) {
				$services_handled++;
				$qck[$v[server_id]][$v[current_state]]--;
				$qck[$v[server_id]][handled]++;

				$service_state_a[$v[service_id]][$v[current_state]]--;	
				$server_state_a[$v[server_id]][$v[current_state]]--;
			}
			if($v[service_ack_current] == 2) {
				$qck[$v[server_id]][acks]++;	
				$acks_outstanding++;
				
			}
			
			
			$all_services++;

			switch($v[current_state]) {

				case 0:
					$services_ok++;
					
				break;
				case 1:
					if($v[handled] == 0) $services_warning++;
					
				break;
				case 2:
					if($v[handled] == 0) $services_critical++;
				
				break;
				case 4:
					if($v[handled] == 0)  $services_info++;
					

				break;
				case 8:
					//$services_info++;
					$services_downtime++;
				break;
				default:
					
					if($v[is_downtime] == 1) {
						$services_ok--;
						$services_downtime++;	
					} else {
						$services_unkown++;
					}
				
				
			}	

	});

		
		
		
		
	
	
	$service_sum=$all_services-$services_downtime-$services_info-$services_handled;
	
	
	
	

	$oks=($services_ok * 100 / $service_sum);
	$downtimes_infos=(($services_downtime+$services_info+$services_unkown) * 100 / $service_sum);
	$warnings=($services_warning * 100 / $service_sum);
	$criticals=($services_critical * 100 / $service_sum);

	$proz=100-$criticals;
	

	
	
	
	$prozent_float[ok] = number_format($oks, 1); 
	$prozent_float[warning] = number_format($warnings, 1); 
	$prozent_float[downtimes_infos] = number_format($downtimes_infos, 1); 
	$prozent_float[criticals] = number_format($criticals, 1); 

	
	
	
		
	if($gdelay_count>0 && $gdelay_sum > 0) {
		
		$avgDEL = round($gdelay_sum/$gdelay_count,2);
	} else {
		$avgDEL = 0;	
	}
	
		$health_title='System Health';  
	$layout->create_box($health_title, $health_content,"system_health", array(
			'prozent_float' => $prozent_float,
			'color' => $color
		), "system_health", false, true);
	
	
	$max_running = bartlby_config($btl->CFG, "max_concurent_checks");
	$max_load = bartlby_config($btl->CFG, "max_load");
	$curr_load = my_sys_getloadavg();
		
	if($curr_load[0] > $max_load) {
			
		if($info[current_running] >= $max_running) {
			$load_bar = "<font color=red>" . $info[current_running]  . " / " . $max_running  . " </font> Load: <font color=red> " . $curr_load[0] . " / " . $max_load . " </font>";
	
		} else if ($info[current_running] >= $max_running-2) {
			$load_bar = "<font color=orange>" . $info[current_running]  . " / " . $max_running  . " </font> Load: <font color=orange> " . $curr_load[0] . " / " . $max_load . " </font>";			

	
		} else {
			$load_bar = "<font color=green>" . $info[current_running]  . " / " . $max_running  . " </font> Load: <font color=green>" . $curr_load[0] . " / " . $max_load . " </font>";
	
		}
	} else {
		$load_bar = "<font color=green>" . $info[current_running]  . "</font> Load: <font color=green>" . $curr_load[0] . " / " . $max_load . " </font>";	
	}

	$fin_last_sync =  "MASTER";
	$last_sync = @filemtime("nodes/" . $Bartlby_CONF_IDX . "/last_sync_shm");
	$last_db = @filemtime("nodes/" . $Bartlby_CONF_IDX . "/last_sync_db");
	if($last_sync != "") {
		$cl = "green";
		if(time()-$last_sync>10*60) {
			$cl="red";
		}
		$fin_last_sync = "<font color=$cl>" . $btl->intervall(time()-$last_sync) . "</font>";
	}
	
	$rel_name = $btl->getRelease();
	$tmpa = explode(":", $rel_name);
	
	if(count($tmpa) > 1) {
		$tmpa[1]= str_replace(")", "", $tmpa[1]);
		$rel_name = $tmpa[0] . " <a href='https://github.com/Bartlby/bartlby-core/commit/" . $tmpa[1] . "'>" . $tmpa[1] . "</A>)";
	}
	$info_box_title='Core Information';  
	$core_content = "";
	$layout->create_box($info_box_title, $core_content, "core_info", array(
		'user' => $btl->user,
		'time' =>  date("d.m.Y H:i:s"),
		'uptime' =>  $btl->intervall(time()-$btl->info[startup_time]),
		'services' => $info[services],
		'workers' => $info[workers],
		'servers' => $info[server],
		'downtimes' => $info[downtimes],
		'datalib' => $lib[Name],
		'datalib_version' => $lib[Version],
		'running' => $load_bar,
		'round_ms_time' => $rndMS,
		'average_delay' => $avgDEL,
		'release_name' => $rel_name,
		'reload_state' => $reload_status,
		'sirene'  => $sir,
		'last_sync' => $fin_last_sync,
		'checks_performed' => number_format($info[checks_performed], 0, ',', '.'),
		'checks_performed_per_sec' => round($info[checks_performed] / (time()-$btl->info[checks_performed_time]),2)
		
		), "core_info", false, true);
	
	
	
	

	
	
	$tac_title='Tactical Overview';  
	$layout->create_box($tac_title, $tac_content, "tactical_overview",array(
		'host_sum' => $hosts_sum,
		'hosts_up' => $hosts_up,
		'hosts_down' => $hosts_down,
		'services_ok' => $services_ok,
		'services_warning' => $services_warning,
		'services_critical' => $services_critical,
		'services_downtime' => $services_downtime,
		'acks_outstanding' => $acks_outstanding,
		'services_info' => $services_info,
		'services_sum' => $info[services],
		'services_unkown' => $services_unkown,
		'services_handled' => $services_handled
	
	), "tactical_overview", false, true);
	
	
	
	//Group info
		$quickview_disabled="false";
			
		$all[0]=0;
		$all[1]=0;
		$all[2]=0;
		$grp_map=array();
		$z=0;
		$btl->servergroup_list_loop(function($grp) use($all, &$grp_map, $z, $server_state_a) {

			$members=explode("|",$grp[servergroup_members]);
			$all[0]=0;
			$all[1]=0;
			$all[2]=0;
			$all[4]=0;
			$all[8]=0;

			$zero_members=0;
			for($x=0; $x<count($members); $x++) {
					if(strlen($members[$x]) <= 0) {  continue; }
			
					$all[0] += $server_state_a[$members[$x]][0];
					$all[1] += $server_state_a[$members[$x]][1];
					$all[2] += $server_state_a[$members[$x]][2];
					$all[4] += $server_state_a[$members[$x]][4];
					$all[8] += $server_state_a[$members[$x]][8];

					
					
					
			}
			
			
			$service_sum=($all[0]+$all[1]+$all[2]+$all[4]+$all[8]);

			
		
			$oks=($all[0] * 100 / $service_sum);
			$downtimes_infos=(($all[8]+$all[4]) * 100 / $service_sum);
			$warnings=($all[1] * 100 / $service_sum);
			$criticals=($all[2] * 100 / $service_sum);

			$proz=100-$criticals;
	

			$prozent_float[ok] = number_format($oks, 1); 
			$prozent_float[warning] = number_format($warnings, 1); 
			$prozent_float[downtimes_and_infos] = number_format($downtimes_infos, 1); 
			$prozent_float[criticals] = number_format($criticals, 1); 	
     	
			
			$tt[prozent_float]=$prozent_float;
			$tt[prozent_zahl]=$prozent_zahl;
			$tt[prozent_crit_zahl]=$prozent_crit_zahl;
			$tt[prozent_crit_float]=$prozent_crit_float;
			$tt[service_sum]=$service_sum;
			$tt[lbl]=$lbl;
			$tt[0]=$all[0];
			$tt[1]=$all[1];
			$tt[2]=$all[2];
			$tt[servergroup_id]=$grp[servergroup_id];
			$tt[servergroup_name]=$grp[servergroup_name];
			array_push($grp_map, $tt);


			

		$z++;
	});
	
	if(count($grp_map) <= 0) {
		$quickview_disabled	 = "true";
	} else {
		
		$health_title='Server Groups';  
		$layout->create_box($health_title, $health_content,"server_groups", array(
				'groups' => $grp_map
			), "server_groups", false, true);
	
	
	
		
	}
		$all[0]=0;
		$all[1]=0;
		$all[2]=0;
		$grp_map=array();
		$z=0;
		$btl->servicegroup_list_loop(function($grp) use($all, $z, &$grp_map, $service_state_a) {

			$members=explode("|",$grp[servicegroup_members]);
			$grp_map[$z][members]=array();
			
			$all[0]=0;
			$all[1]=0;
			$all[2]=0;
			$all[4]=0;
			$all[8]=0;
			$zero_members=0;
			for($x=0; $x<count($members); $x++) {
					if(strlen($members[$x]) <= 0) {  continue; }
					array_push($grp_map[$z][members], $members[$x]);
					
					$all[0] += $service_state_a[$members[$x]][0];
                    $all[1] += $service_state_a[$members[$x]][1];
					$all[2] += $service_state_a[$members[$x]][2];
					$all[4] += $service_state_a[$members[$x]][4];
					$all[8] += $service_state_a[$members[$x]][8];
					
					
					
			}
			$service_sum=($all[0]+$all[1]+$all[2]);

			
		
			$oks=($all[0] * 100 / $service_sum);
			$downtimes_infos=(($all[8]+$all[4]) * 100 / $service_sum);
			$warnings=($all[1] * 100 / $service_sum);
			$criticals=($all[2] * 100 / $service_sum);
			$proz=100-$criticals;
	
			if($grp[servicegroup_name] == "hexbased daemons") {
				
			}
			$prozent_float[ok] = number_format($oks, 1); 
			$prozent_float[warning] = number_format($warnings, 1); 
			$prozent_float[downtimes_and_infos] = number_format($downtimes_infos, 1); 
			$prozent_float[criticals] = number_format($criticals, 1); 	
     	

			$tt[prozent_float]=$prozent_float;
			$tt[prozent_zahl]=$prozent_zahl;
			$tt[prozent_crit_zahl]=$prozent_crit_zahl;
			$tt[prozent_crit_float]=$prozent_crit_float;
			$tt[service_sum]=$service_sum;
			$tt[lbl]=$lbl;
			$tt[0]=$all[0];
			$tt[1]=$all[1];
			$tt[2]=$all[2];
			$tt[servicegroup_name]=$grp[servicegroup_name];
			$tt[servicegroup_id]=$grp[servicegroup_id];
			array_push($grp_map, $tt);

		$z++;

	});

	
	
	
	if(count($grp_map) > 0) {
	
			$health_title='Service Groups';  
			$layout->create_box($health_title, $health_content,"service_groups", array(
					'groups' => $grp_map
				), "service_groups", false, true);
				
		}
	
	
	


	
	$layout->setTitle("QuickView");
	$r=$btl->getExtensionsReturn("_overview", $layout);
	
	if($quickview_disabled != "false") {
		$qv_title='Quick View';  
		$layout->create_box($qv_title, $qv_content,"quick_view", array(
				'quick_view' => $qck
			), "quick_view", false,true);
		
	} 
	


	$whats_on=$btl->getWhatsOn();
	$layout->create_box("Notifications", "$notiy_cnt", "whats_on_notifications", array("whats_on" => $whats_on
		), "whats_on_notifications", false, false);

	$layout->create_box("State Changes", "$notiy_cnt", "whats_on_state_changes", array("whats_on" => $whats_on
		), "whats_on_state_changes", false, false);
	$layout->create_box("State Change Log", "$notiy_cnt", "whats_on_state_change_log", array("whats_on" => $whats_on
		), "whats_on_state_change_log", false, false);

	$layout->create_box("Notification Log", "$notiy_cnt", "whats_on_notify_log", array("whats_on" => $whats_on
		), "whats_on_notify_log", false, false);

	$whats_on_tab = "<div>Time Range: " . $whats_on[start_date] . " - " . $whats_on[end_date] . "</div>";

	$whats_on_tab .= "<div id=service_detail_service_info_ajax class='fifty_float_left'>";
	$whats_on_tab .= $layout->disp_box("whats_on_notifications");
	$whats_on_tab .= "</div>";

	$whats_on_tab .= "<div id=service_detail_service_info_ajax class='fifty_float_left'>";
	$whats_on_tab .= $layout->disp_box("whats_on_state_changes");
	$whats_on_tab .= "</div><div style='clear:both;'></div>";

	$whats_on_tab .= $layout->disp_box("whats_on_notify_log");
	$whats_on_tab .= $layout->disp_box("whats_on_state_change_log");


	$layout->Tab("Whats On <span class='notification red' style='display:inline-block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif; position:relative;top: 0px;'>" . $whats_on[state_changes] . "</span>", $whats_on_tab);
	$layout->boxes_placed[MAIN]=true;
	$layout->TableEnd();
	$layout->display("overview");
	
	
	
function my_sys_getloadavg() {
	$con = file_get_contents("/proc/loadavg");
	$r = explode(" ", $con);
	return $r;
	
	
}
?>
