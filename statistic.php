<?php
set_time_limit(0);

function dnl($i) {
	return sprintf("%02d", $i);
}
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("core.process_info");

if($_GET[maxn]) {
	$maxn=$_GET[maxn];	
} else {
	$maxn=10;	
}
if($_GET[sortorder]) {
	$sorto=$_GET[sortorder];	
} else {
	$sorto="desc";	
}

if($sorto == "asc") {
	$ascc="checked";	
} else {
	$descc="checked";	
}


$layout= new Layout();
$layout->set_menu("core");
$layout->setMainTabName("Statistics");
$layout->do_auto_reload=true;
//function create_box($title, $content, $id="", $plcs="", $box_file="", $collapsed=false, $auto_reload=false) {
$layout->setTitle("Core Performance");
$layout->Table("100%");

//Check if profiling is enabled
	//$map = $btl->GetSVCMap();
	$info = $btl->getInfo();
	$check_max=0;
	$check_avg=0;
	$check_count=0;
	$check_sum=0;
	$check_plg_max="";
	
	$round_max=0;
	$round_avg=0;
	$round_count=0;
	$round_sum=0;
	
	$retain_count=0;
	$retain_sum=0;
	
	$btl->service_list_loop(function($svc, $shm) use (&$check_sum, &$check_count, &$delay_sum, &$delay_count, &$plugin_table, &$service_table, &$server_table, &$delay_service_table, &$delay_server_table, &$retain_table) {
		$x=0;
		$servs[$x]=$svc;
	
			$check_sum +=$servs[$x][service_time_sum];
			$check_count +=$servs[$x][service_time_count];
			
			$retain_sum += $servs[$x][service_retain_current];
			$retain_count++;
			
			$delay_sum += $servs[$x][service_delay_sum];
			$delay_count += $servs[$x][service_delay_count];
			
			$plugin=$servs[$x][plugin];
			if($servs[$x][service_time_count] > 0) {
				$ms=@round($servs[$x][service_time_sum] / $servs[$x][service_time_count], 2);
			} else {
				$ms=0;	
			}
			
			if($servs[$x][service_delay_count] > 0) {
				$delay_ms=@round($servs[$x][service_delay_sum] / $servs[$x][service_delay_count], 2);
			} else {
				$delay_ms=0;	
			}
			
			$service="<img src='server_icons/" . $servs[$x][server_icon] . "'><a href='service_detail.php?service_place=" . $servs[$x][shm_place] . "'>" .  $servs[$x][server_name] . "/" . $servs[$x][service_name] . "(" .   $servs[$x][plugin] . ")</A>";
			$server="<img src='server_icons/" . $servs[$x][server_icon] . "'><a href='server_detail.php?server_id=" . $servs[$x][server_id] . "'>" . $servs[$x][server_name] . "</A>";
			
			//var_dump();
			
			if(!is_array($plugin_table[$plugin])) {
					$plugin_table[$plugin]=Array();
			}
			array_push($plugin_table[$plugin], $ms);
			
			//Service Table
			if(!is_array($service_table[$service])) {
				$service_table[$service]=Array();
			}
			array_push($service_table[$service], $ms);
			
			//Server Table
			if(!is_array($server_table[$server])) {
				$server_table[$server]=Array();
			}
			array_push($server_table[$server], $ms);
			
			//Delay Servcie Table
			if(!is_array($delay_service_table[$service])) {
				$delay_service_table[$service]=Array();
			}
			array_push($delay_service_table[$service], $delay_ms*1000);
			
			//Delay Table
			if(!is_array($delay_server_table[$server])) {
				$delay_server_table[$server]=Array();
			}
			array_push($delay_server_table[$server], $delay_ms*1000);
			
			//retain counter - service
			if(!is_array($retain_table[$service])) {
					$retain_table[$service]=Array();
			}
			array_push($retain_table[$service],(time()-$servs[$x][last_state_change]));
			
			
	});		

	
	$round_sum=$info[round_time_sum];
	$round_count=$info[round_time_count];
	
	
	
	
	$check_avg=round($check_sum / $check_count,2);
	$round_avg=round($round_sum / $round_count,2);
	$delay_avg=round(($delay_sum / $delay_count)*1000, 2);
	
	//$retain_avg=round($retain_sum / $retain_count,2);
	
	
	
	//Make top 10 table plugins
	//Table K1 == AVG == K2 VALUE == K3 MAX
	$delay_service_sorted=sort_table($delay_service_table);
	$delay_server_sorted=sort_table($delay_server_table);
	$plugins_sorted=sort_table($plugin_table);
	$server_sorted=sort_table($server_table);
	$service_sorted=sort_table($service_table);
	$plugin_html=make_html($plugins_sorted, "Plugin Name", "ms", "ms");
	$service_html=make_html($service_sorted, "Service", "ms", "ms");
	$server_html=make_html($server_sorted, "Server", "ms", "ms");
	$delay_service_html=make_html($delay_service_sorted, "Service", "ms", "ms");
	$delay_server_html=make_html($delay_server_sorted, "Server", "ms", "ms");
	
	
	
	
	//Retain
	$retain_service_sorted=sort_table($retain_table);
	$retain_service_table=make_html($retain_service_sorted, "Service", "Retain Counter", "seconds");
	
	
	
	$info_box_title="Check Time";  
	$core_content = $plugin_html;
	//function create_box($title, $content, $id="", $plcs="", $box_file="", $collapsed=false, $auto_reload=false) {
	$b=$layout->create_box("Plugins", $core_content, "plugin_check_time", "", "", false);
	
	

	$info_box_title="Servers";  
	$core_content = 					$server_html;
	$b=$layout->create_box($info_box_title, $core_content, "server_time_check", "", "", false);

	
	$core_content = 	$service_html;
	$b=$layout->create_box("Services", $core_content, "service_check_time", "", "", false);
	
	$layout->Tab("Check Time", $layout->disp_box("plugin_check_time") . $layout->disp_box("service_check_time") .   $layout->disp_box("server_time_check"));
	



	$info_box_title="Services";  
	$core_content = $delay_service_html;
	$b=$layout->create_box($info_box_title, $core_content, "delay_service_time", "", "", false);
	
	
	
	
	
	
	



	$info_box_title="Server";  
	$core_content = $delay_server_html;
	$b=$layout->create_box($info_box_title, $core_content, "server_delay_time", "", "", false);
	

	$layout->Tab("Delays", $layout->disp_box("delay_service_time") .   $layout->disp_box("server_delay_time"));
		
	
	$info_box_title="Timing:";  
	$layout->OUT .= "<table  width='100%'>
	
		<tr>
			<td width=150 valign=top class='font2'>Average - Check Time:</td>
			<td>$check_avg ms</td>
			<td width=150 valign=top class='font2'>Average - Round Time:</td>
			<td>$round_avg ms</td>
			<td width=150 valign=top class='font2'>Average - Delay Time:</td>
			<td>$delay_avg ms</td>
		</tr>
		
		
	</table>";
	

	

		$info_box_title="Options:";  
	$core_content = "

	<table  width='100%'>
	
		<tr>
			<td width=150 valign=top class='font2'>Max Num:</td>
			<td><form name='f' action=statistic.php method=POST><input type=text name='maxn' value='$maxn'><input type='submit' value='Update..'><br>
			<input type=radio name='sortorder' value='asc' $ascc >Ascending <input type=radio name='sortorder' value='desc' $descc >Descending
			</form></td>
		</tr>
		
		
	</table>";
	
	$layout->create_box($info_box_title, $core_content, "statistic_options", "", "", false);
	
	

$r=$btl->getExtensionsReturn("_processInfo", $layout);
$layout->TableEnd();

$layout->display();

function sort_table($plugin_table) {
	global $sorto;
	
	while(list($k, $v) = @each($plugin_table)) {
		
		$max=0;
		$sum=0;
		$cnt=0;
		for($x=0; $x<count($v); $x++) {
			if($v[$x] > $max) {
				$max=$v[$x];	
			}
			$sum += $v[$x];
			$cnt++;
		}
		$avg=round($sum/$cnt, 2);
		
		$plugins_sortable[$avg][$k][$max] = 1;
	}
	
	if($sorto == "asc") {
		@ksort($plugins_sortable);
	} else {
		@krsort($plugins_sortable);	
	}
	return $plugins_sortable;
	
}

function make_html($info=array(), $key, $time_value, $time_short) {
	global $maxn, $btl;
	$have=0;
	
	$out = '<table class="table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th>' . $key . '</th>
									  <th>' . $time_value . '</th>
									  
									  
								  </tr>
							  </thead>   ';
	

	
	while(list($average, $d) = each( $info )) {
		while(list($plugin, $d1) = each($d)) {
			while(list($max, $d2) = each($d1)) {
				if($time_short == "seconds") {
					$average = $btl->intervall($average);
				}
				$out .= "<tr>";
				$out .= "<td align=left valign=top nowrap>$plugin</td>";	
				$out .= "<td align=right valign=bottom>$average " . $time_short . "</td>";
				
				$out .= "</tr>";
				$have++;
				
				if($have == $maxn) {
					break 3;	
				}
			}	
		}
	}
	
	$out .= "</table>";
	return $out;
}