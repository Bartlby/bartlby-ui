<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);

$layout= new Layout();
$layout->setTitle("Select Downtime");
$layout->set_menu("downtimes");
$layout->Form("fm1", $_GET[script]);


$r=bartlby_downtime_map($btl->RES);
$optind=0;
for($x=0; $x<count($r); $x++) {
	if($r[$x][downtime_type] == 1) {
		$dttype="Service";
		$cl = "grey";	
	} else if($r[$x][downtime_type] == 2) {
		$dttype="Server";
		$cl = "orange";
	}else if($r[$x][downtime_type] == 3) {
		$dttype="ServerGroup";
		$cl = "pink";
	}else if($r[$x][downtime_type] == 2) {
		$dttype="ServiceGroup";
		$cl = "green";
	}
	$dts[$optind][c]="";
	$dts[$optind][v]=$r[$x][downtime_id];	
	$dts[$optind][k]=date("d.m.Y H:i", $r[$x][downtime_from]) . "&nbsp;&nbsp;-&nbsp;&nbsp;" . date("d.m.Y H:i", $r[$x][downtime_to]) . " " . $r[$x][downtime_notice];
	
	
	$y_from = date("Y", $r[$x][downtime_from]);
	$m_from = date("m", $r[$x][downtime_from])-1;
	$d_from = date("d", $r[$x][downtime_from]);
	$h_from = date("H", $r[$x][downtime_from]);
	$i_from = date("i", $r[$x][downtime_from]);
	
	$y_to = date("Y", $r[$x][downtime_to]);
	$m_to = date("m", $r[$x][downtime_to])-1;
	$d_to = date("d", $r[$x][downtime_to]);
	$h_to = date("H", $r[$x][downtime_to]);
	$i_to = date("i", $r[$x][downtime_to]);
	
	
	
	$not = date("d.m.Y H:i", $r[$x][downtime_from]) . "-" . date("d.m.Y H:i", $r[$x][downtime_to]);
	$not = $dttype . $r[$x][downtime_notice];
	$evnts .= "
		event = new Object();
		event.title = '" . $not . "'; // this should be string
		event.start = new Date(" . $y_from . "," . $m_from . "," . $d_from . ", " . $h_from . ", " . $i_from . "); // this should be date object
		event.end = new Date(" . $y_to . "," . $m_to . "," . $d_to . ", " . $h_to . ", " . $i_to . ");
		event.color = '" . $cl . "';
		event.url = '" . $_GET[script] . "?downtime_id=" . $r[$x][downtime_id] . "';
		event.allDay = false;
		events.push(event);
	
	";
	
	
	$optind++;	
}

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