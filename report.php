<?php
set_time_limit(0);

include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasright("log.report");
$btl->hasServerorServiceRight($_GET[report_service]);

$defaults=bartlby_get_service_by_id($btl->CFG, $_GET[report_service]);

$ibox[0][c]="green";
$ibox[0][v]=0;
$ibox[0][s]=1;	
$ibox[0][k]="OK";
$ibox[1][c]="orange";        
$ibox[1][v]=1;	  
$ibox[1][k]="Warning";
$ibox[2][c]="red";        
$ibox[2][v]=2;	  
$ibox[2][k]="Critical";


$layout= new Layout();

$layout->set_menu("report");
$layout->setTitle("Report");
$layout->Form("fm1", "report.php");
$layout->Table("100%");

$state_array=array();

$log_mask=bartlby_config($btl->CFG, "logfile");

if(!$_GET[report_service] || !$log_mask) {
	$out ="You v choosen a server? or log file is not set";	
} else {
	
	
	$date_start=explode("/", $_GET[report_start]);
	$date_end=explode("/", $_GET[report_end]);
	
	
	
	$_GET[report_start] = $date_start[1] . "." . $date_start[0] . "." . $date_start[2];
	$_GET[report_end] = $date_end[1] . "." . $date_end[0] . "." . $date_end[2];
	
	if($_POST[report_rcpt]) {
		
		$out .= $btl->send_custom_report($_POST[report_rcpt], $_GET[report_service], $_GET[report_start], $_GET[report_end]);
	}
	
	
	
	$out .= "creating report for service: $_GET[report_service] From: $_GET[report_start] To: $_GET[report_end]<br>";	
	$ra=$btl->do_report($_GET[report_start], $_GET[report_end], $_GET[report_init], $_GET[report_service]);
	
	
	$date_start=explode(".", $_GET[report_start]);
	$date_end=explode(".", $_GET[report_end]);
	
	$time_start=mktime(0,0,0, $date_start[1], $date_start[0], $date_start[2]);
	$time_end=mktime(23,59,0, $date_end[1], $date_end[0], $date_end[2]);
		
		
	
	
	
	$svc=$ra[svc];
	$state_array=$ra[state_array];
	$notify=$ra[notify];
	$files_scanned=$ra[files_scanned];
		
	$idx=$btl->findSHMPlace($defaults[service_id]);
		$svc_option_line="<a href='service_detail.php?service_place=$idx'>" . $defaults[server_name] . ":" . $defaults[client_port] . "/" . $defaults[service_name] . "</A>" . $btl->getServiceOptions($defaults, $layout) . "<a href='print_report.php?report_start=" . $_GET[report_start] . "&report_end=" .  $_GET[report_end] . "&report_init=" . $_GET[report_init] . "&report_service=" . $_GET[report_service] . "' target='_blank'>Print</A>";
		
	
	$out .= $svc_option_line . "<br><br>";
			 
			 
			 
			 
		$out .= "<table  border=5 class='table table-striped table-bordered ' id='services_table2'>
						  <thead>
							  <tr>
							  	<th>Time</th>
								  <th>State</th>
								  <th>Percentage</th>
								  
							  </tr>
						  </thead>
						    <tbody>";
		$out .= "";
		
		$hun=$svc[0]+$svc[1]+$svc[2];
		$flash[0]="0";
		$flash[1]="0";
		$flash[2]="0";
		
		//$img_file=$btl->create_report_img($state_array, $time_start, $time_end);
		
		
		$script_out .= '
		<script>
			var data1 = [	';
			
		while(list($state, $time) = @each($svc)) {
						
			
			$perc =   (($hun-$time) * 100 / $hun);
			$perc =100-$perc;
			$lbl="";
			if($state == 0) {
					 $lbl="label-success";
					 $col="green";
			}
			if($state == 1) {
				$lbl="label-warning";
				$col="orange";
			}
			if($state == 2) {
				 $lbl="label-important";
				 $col="red";
			}
			if($state == 8) {
				 $lbl="";
				 $col="grey";
			}
			
			$out .= "<tr>";
			$out .= "<td width=200><span class='label " .  $lbl . "'>" . $btl->getState($state) . "</span><br>";
			
			$out .= "</td>";
			$out .= "<td>Time:  " . $btl->intervall($time) . " seconds</td>";
			$out .= "<td><b>" . round($perc,2) . "%</b>   </td></tr>";
			
			$script_out .= '{ color: "' . $col . '", label: "' . $btl->getState($state) . '",  data: ' . $perc . '},';
			
			$flash[$state]=$perc;
			
			
		}
		$script_out .= '{}]</script>';
		
		$out .= $script_out;
		
						
		
			
		
	
		
		
		
		$out .= "<tr>";
		
			$out .= '
			
			 
				<script>
				
				window.setTimeout("doChart()", 1000);
				function doChart() {
					$.plot($("#placeholder"),[{data:d, 
					threshold:  [{
								below: 3,
								color: "grey"
							},{
								below: 1,
								color: "orange"
							},{
								below: 2,
								color: "green"
							},{
								below: -1,
								color: "red"
							}],
							color: "green",
							
							points: { show: true },
            	lines: { show: true, steps: true }}], {
							 xaxis: { mode: "time",timeformat: "%y/%d/%m - %H:%M:%S" },
						 	yaxis: { min: -3, ticks: [[1, "OK"], [-1, "Warning"], [-2, "Critical"], [2, "Downtime"]], max: 2 }, 
						 	
            	
					});
					
					$.plot($("#donutchart1"), data1,
					{
							series: {
									pie: {
											radius: 80,
											innerRadius: 0.5,
											show: true,
											label: {
			                        show: true
			                 }
									}
							},
							legend: {
								show: true
							}
					});
					
				}
				</script>
				<br>
				
			';
				$out .= "</tr>";
		$out .= "<tbody></table>";
		$out .= '<div id="donutchart1" style="height: 300px;"></div>
		<div id="placeholder" style="left: 40px;width:90%;height:400px;"></div>';
		$out .= "<table  border=5 class='table table-striped table-bordered ' id='services_table3'>
						  <thead>
							  <tr>
							  	<th>Worker</th>
								  <th>Trigger</th>
								  
								  
							  </tr>
						  </thead>
						    <tbody>";
		$out .= "<td colspan=2 class=header><font color='black'>Notifications:</td>";
		$hun=$daycnt;
		while(list($worker, $dd) = @each($notify)) {
			
			
			
			$out .= "<tr>";
			$out .= "<td valign=top width=200><b>$worker</b></td>";
			
			$out .= "<td>";
			
			
			while(list($trigger, $dd1) = @each($dd)) {
				$out .=	"<i>" . $trigger . "</i><br>";
				while(list($k, $ts) = @each($dd1)) {
					$lbl="";
					if($ts[1] == 0) $lbl="label-success";
					if($ts[1] == 1) $lbl="label-warning";
					if($ts[1] == 2) $lbl="label-important";
					$out .= "&nbsp;	&nbsp;&nbsp;&nbsp;&nbsp; "  . date("d.m.Y H:i:s", $ts[0]) . " <span class='label " .  $lbl . "'>" . $btl->getState($ts[1]) . "</span><br>";
				}
			}
			
			$out .= "</td>";
			
			
			$out .= "</tr>";
		}
		
		$out .= "</tbody></table>";
		
		if($_GET[report_init] == 0) $st_r = 1;
		if($_GET[report_init] == 1) $st_r = -1;
		if($_GET[report_init] == 2) $st_r = -2;
		
		$o1 .= "<table class='table table-striped table-bordered ' id='services_table1'>
						  <thead>
							  <tr>
							  	<th>Time</th>
								  <th>State</th>
								  <th>Output</th>
								  
							  </tr>
						  </thead>
						    <tbody>";
		$o1 .= "<tr><td colspan=3 class=header><font color=black>Output:</font></td></tr>";
		$js_out .= "<script>var d = [";
		$js_out .= "[" . ($time_start*1000) . ", " . $st_r . "],";

		for($xy=0; $xy<count($state_array);$xy++) {
					
				if($state_array[$xy][lstate] == 0) $st_r = 1;
				if($state_array[$xy][lstate] == 1) $st_r = -1;
				if($state_array[$xy][lstate] == 2) $st_r = -2;
				if($state_array[$xy][lstate] == 8) $st_r = 2;
				
			$lbl="";
			if($state_array[$xy][lstate] == 0) $lbl="label-success";
			if($state_array[$xy][lstate] == 1) $lbl="label-warning";
			if($state_array[$xy][lstate] == 2) $lbl="label-important";
			if($state_array[$xy][lstate] == 8) $lbl="";
			
					$o1 .= "<tr>";
					$o1 .= "<td>" . date("d.m.Y H:i:s", $state_array[$xy][end]) . "</td>";
					$o1 .= "<td valign=top width=200><span class='label " .  $lbl . "'>" . $btl->getState($state_array[$xy][lstate]) . "</span></b></td>";
			
					$o1 .= "<td>" . $state_array[$xy][msg] . "</td></tr>";
					$js_out .= "[" . ($state_array[$xy][end]*1000) . ", " . $st_r . "],";
		}
		$o1 .= "</tbody></table>";
		if($time_end > time()) {
			$time_end=time();
		}
		$js_out .= "[" . ($time_end*1000) . ", " . $st_r . "],";
		
		$js_out .= "[]];</script>";
		$o1 .= $js_out;
	
}


$layout->Tr(
	$layout->Td(
			Array(
				0=>$out
			)
		)

);
$layout->Tr(
	$layout->Td(
			Array(
				0=>$o1
			)
		)

);
for($x=0; $x<count($files_scanned); $x++)  {
	$worked_on_files++;
	$worked_on_lines += $files_scanned[$x][1]; 	
}

$layout->Tr(
	$layout->Td(
			Array(
				0=>"Looked @ $worked_on_files files and $worked_on_lines Lines"
			)
		)

);





$layout->TableEnd();

$layout->FormEnd();
$layout->display();

