<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);

$layout= new Layout();
$layout->setTitle("Select Downtime");
$layout->set_menu("downtimes");
$layout->Form("fm1", $_GET[script]);

$evnts = "";
$btl->downtime_list_loop(function($dt, $shm) use(&$evnts){
	$optind=0;
	if($dt[downtime_type] == 1) {
		$dttype="Service";
		$cl = "grey";	
	} else if($dt[downtime_type] == 2) {
		$dttype="Server";
		$cl = "orange";
	}else if($dt[downtime_type] == 3) {
		$dttype="ServerGroup";
		$cl = "pink";
	}else if($dt[downtime_type] == 2) {
		$dttype="ServiceGroup";
		$cl = "green";
	}
	$dts[$optind][c]="";
	$dts[$optind][v]=$dt[downtime_id];	
	$dts[$optind][k]=date("d.m.Y H:i", $dt[downtime_from]) . "&nbsp;&nbsp;-&nbsp;&nbsp;" . date("d.m.Y H:i", $dt[downtime_to]) . " " . $dt[downtime_notice];
	
	
	$y_from = date("Y", $dt[downtime_from]);
	$m_from = date("m", $dt[downtime_from])-1;
	$d_from = date("d", $dt[downtime_from]);
	$h_from = date("H", $dt[downtime_from]);
	$i_from = date("i", $dt[downtime_from]);
	
	$y_to = date("Y", $dt[downtime_to]);
	$m_to = date("m", $dt[downtime_to])-1;
	$d_to = date("d", $dt[downtime_to]);
	$h_to = date("H", $dt[downtime_to]);
	$i_to = date("i", $dt[downtime_to]);
	
	
	
	$not = date("d.m.Y H:i", $dt[downtime_from]) . "-" . date("d.m.Y H:i", $dt[downtime_to]);
	$not = $dttype . $dt[downtime_notice];
	$evnts .= "
		event = new Object();
		event.title = '" . $not . "'; // this should be string
		event.start = new Date(" . $y_from . "," . $m_from . "," . $d_from . ", " . $h_from . ", " . $i_from . "); // this should be date object
		event.end = new Date(" . $y_to . "," . $m_to . "," . $d_to . ", " . $h_to . ", " . $i_to . ");
		event.color = '" . $cl . "';
		event.url = '" . $_GET[script] . "?downtime_id=" . $dt[downtime_id] . "';
		event.allDay = false;
		events.push(event);
	
	";
	
	
	$optind++;	
});

$layout->OUT .= "<div id=calendar style='width: 50%;float:left;'></div>";







$layout->FormEnd();
$layout->display();

echo "<script>
function addToCalendar() {
var events = new Array();
event = new Object();";
echo $evnts;
echo "$('#calendar').fullCalendar('addEventSource',events);}</script>";
?>