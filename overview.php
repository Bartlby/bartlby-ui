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

	$layout->set_menu("main");
	//$layout->MetaRefresh(30);
	$layout->Table("100%");
	$lib=bartlby_lib_info($btl->CFG);
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
	
		
	
	
	$servers=$btl->GetSVCMap();
	
	
	
	$hosts_sum=count($servers);
	$hosts_up=0;
	$hosts_down=0;
	$services_critical=0;
	$services_info=0;
	$services_ok=0;
	$services_warning=0;
	$services_unkown=0;
	$services_downtime=0;
	$all_services=0;
	$acks_outstanding=0;
	$gdelay_count = 0;
	$gdelay_sum = 0;
	
	while(list($k,$v)=@each($servers)) {
		$x=$k;
		if($btl->isServerUp($x, $servers)) {
			$hosts_up++;	
		} else {
			$hosts_down++;	
			$hosts_a_down[$k]=1;
			
		}
		
		for($y=0; $y<count($v); $y++) {
			//service_delay_sum
			$gdelay_sum += $v[$y][service_delay_sum];
			$gdelay_count += $v[$y][service_delay_count];
			
			if($v[$y][is_gone]) $reload_status="<font color=red>Reload needed</font>";		
			
			$service_state_a[$v[$y][service_id]][$v[$y][current_state]]++;	
			
			$qck[$v[$y][server_id]][$v[$y][current_state]]++;	
			$qck[$v[$y][server_id]][10]=$v[$y][server_id];
			$qck[$v[$y][server_id]][server_icon]=$v[$y][server_icon];
			$qck[$v[$y][server_id]][server_name]=$v[$y][server_name];
			if($v[$y][is_downtime] == 1) {
				$qck[$v[$y][server_id]][$v[$y][current_state]]--;
				$qck[$v[$y][server_id]][downtime]++;
				
			}
			if($v[$y][service_ack_current] == 2) {
				$qck[$v[$y][server_id]][acks]++;	
				$acks_outstanding++;
				
			}
			
			
			$all_services++;
			switch($v[$y][current_state]) {

				case 0:
					$services_ok++;
					
				break;
				case 1:
					$services_warning++;
					
				break;
				case 2:
					$services_critical++;
				
				break;
				case 4:
					$services_info++;
					

				break;
				case 8:
					//$services_info++;
					$services_downtime++;
				break;
				default:
					$services_unkown++;
					if($v[$y][is_downtime] == 1) {
						$services_ok--;
						$services_downtime++;	
					}
				
				
			}	
		}
		
		
	}
	
	$service_sum=$all_services-$services_downtime-$services_info;
	
	
	
	

	if($service_sum == 0) {
		$criticals=100;
	} else {
		$criticals=(($service_sum-$services_ok) * 100 / $service_sum);
	}

	$proz=100-$criticals;
	
	
	
	
	$prozent_zahl = floor($proz);
	$prozent_float = number_format($proz, 1); 
	$prozent_crit_zahl = floor($criticals);
	$prozent_crit_float = number_format($criticals, 1); 
	
	$color="green";
	
	if($prozent_float <= 60) {
		$color="red";	
	} else if($prozent_float <= 90) {
		$color="yellow";	
	} else if($prozent_float <= 80) {
		$color="red";	
	} else {
		$color="green";
	}

	$bar=$prozent_float . "% Ok - $prozent_crit_float % Critical";
		
	if($gdelay_count>0 && $gdelay_sum > 0) {
		
		$avgDEL = round($gdelay_sum/$gdelay_count,2);
	} else {
		$avgDEL = 0;	
	}
	
		$health_title='System Health';  
	$layout->create_box($health_title, $health_content,"system_health", array(
			'prozent_float' => $prozent_float,
			'color' => $color
		), "system_health", false, false);
	
	
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
	$last_sync = @file_get_contents("last_sync-" . $Bartlby_CONF_IDX);
	if($last_sync != "") {
		$fin_last_sync = $btl->intervall(time()-$last_sync);
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
		'services_sum' => $info[services]
	
	), "tactical_overview", false, true);
	
	
	
	//Group info
		$quickview_disabled="false";
			
		$all[0]=0;
		$all[1]=0;
		$all[2]=0;
			
		$grp_map=$btl->GetServerGroups();
	
		for($z=0; $z<count($grp_map); $z++) {
			$members=explode("|",$grp_map[$z][servergroup_members]);
			$grp_map[$z][members]=array();
			
			$all[0]=0;
			$all[1]=0;
			$all[2]=0;
			$zero_members=0;
			for($x=0; $x<count($members); $x++) {
					if(strlen($members[$x]) <= 0) {  continue; }
					array_push($grp_map[$z][members], $members[$x]);
					
					$ret=$btl->getServerInfs($members[$x], $servers);	
					$all[0] += $ret[0];
					$all[1] += $ret[1];
					$all[2] += $ret[2];
					
					
					
			}
			
				$service_sum=($all[0]+$all[1]+$all[2]);
				if($service_sum == 0) {
					$criticals=100;
				} else {
					$criticals=(($service_sum-$all[0]) * 100 / $service_sum);
				}
     	
				$proz=100-$criticals;
			
			
			
			
				$prozent_zahl = floor($proz);
				$prozent_float = number_format($proz, 1); 
				$prozent_crit_zahl = floor($criticals);
				$prozent_crit_float = number_format($criticals, 1); 
			
				$color="green";
	
				if($prozent_float <= 60) {
					$color="red";	
					$lbl = "progress-danger";
				} else if($prozent_float <= 90) {
					$lbl = "progress-warning";
				} else if($prozent_float <= 80) {
					$lbl = "progress-danger";
				} else {
					$lbl = "progress-success";
				}
				
				$grp_map[$z][prozent_float]=$prozent_float;
				$grp_map[$z][prozent_zahl]=$prozent_zahl;
				$grp_map[$z][prozent_crit_zahl]=$prozent_crit_zahl;
				$grp_map[$z][prozent_crit_float]=$prozent_crit_float;
				$grp_map[$z][service_sum]=$service_sum;
				$grp_map[$z][lbl]=$lbl;
				$grp_map[$z][0]=$all[0];
				$grp_map[$z][1]=$all[1];
				$grp_map[$z][2]=$all[2];
				
				
				
		}



	
	if(count($grp_map) <= 0) {
		$quickview_disabled	 = "true";
	} else {
		
		$health_title='Server Groups';  
		$layout->create_box($health_title, $health_content,"server_groups", array(
				'groups' => $grp_map
			), "server_groups", false, true);
	
	
	
		
	}
	
	
	
	
	//SErvice Groups
			$all[0]=0;
		$all[1]=0;
		$all[2]=0;
			
		$grp_map=$btl->GetServiceGroups();
		for($z=0; $z<count($grp_map); $z++) {
			$members=explode("|",$grp_map[$z][servicegroup_members]);
			$grp_map[$z][members]=array();
			
			$all[0]=0;
			$all[1]=0;
			$all[2]=0;
			
			for($x=0; $x<count($members); $x++) {
					if(strlen($members[$x]) <= 0) continue;
					array_push($grp_map[$z][members], $members[$x]);
					
					
					$all[0] += $service_state_a[$members[$x]][0];
					$all[1] += $service_state_a[$members[$x]][1];
					$all[2] += $service_state_a[$members[$x]][2];
					
					
					
					
					
			}
				$service_sum=($all[0]+$all[1]+$all[2]);
				if($service_sum == 0) {
					$criticals=100;
				} else {
					$criticals=(($service_sum-$all[0]) * 100 / $service_sum);
				}
     		
				$proz=100-$criticals;
			
			
			
			
				$prozent_zahl = floor($proz);
				$prozent_float = number_format($proz, 1); 
				$prozent_crit_zahl = floor($criticals);
				$prozent_crit_float = number_format($criticals, 1); 
			
				$color="green";
	
				if($prozent_float <= 60) {
					$color="red";	
					$lbl = "progress-danger";
				} else if($prozent_float <= 90) {
					$lbl = "progress-warning";
				} else if($prozent_float <= 80) {
					$lbl = "progress-danger";
				} else {
					$lbl = "progress-success";
				}
			
				$grp_map[$z][prozent_float]=$prozent_float;
				$grp_map[$z][prozent_zahl]=$prozent_zahl;
				$grp_map[$z][prozent_crit_zahl]=$prozent_crit_zahl;
				$grp_map[$z][prozent_crit_float]=$prozent_crit_float;
				$grp_map[$z][service_sum]=$service_sum;
				$grp_map[$z][lbl]=$lbl;
				$grp_map[$z][0]=$all[0];
				$grp_map[$z][1]=$all[1];
				$grp_map[$z][2]=$all[2];
				
		}
	
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
	
	
	$layout->boxes_placed[MAIN]=false;
	$layout->TableEnd();
	$layout->display("overview");
	
	
	
function my_sys_getloadavg() {
	$con = file_get_contents("/proc/loadavg");
	$r = explode(" ", $con);
	return $r;
	
	
}
?>
