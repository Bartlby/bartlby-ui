<?php
set_time_limit(0);

function dnl($i) {
	return sprintf("%02d", $i);
}
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("core.event_queue");
$layout= new Layout();
$layout->set_menu("core");
$layout->setTitle("Bartlby Last Event's");

//Check if profiling is enabled
	$evnts = '<table class="datat1able table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th style="width:150px">Date</th>
									  <th style="width:100px">Type</th>
									  <th style="width:100px">Output</th>
									  
									  <th style="width:100px">Service</th>
									  <th style="width:100px">State</th>
									  
								  </tr>
							  </thead>   ';
	$msg_array=array();
	for($x=128; $x>=0; $x--) {
		$msg=bartlby_event_fetch($btl->RES, $x);
		
		if($msg[id] == 0) {
			continue;	
		}
		$msg_array[$msg[time] + $msg[id]]=$msg;
		
		
	}

	krsort($msg_array);

	foreach($msg_array as $msg) {

		switch($msg[id]) {
			case 2:
				$evnt_type="STATE CHANGE";
			break;
			case 3:
				$evnt_type="TRIGGER";
			break;
			default:
				$evnt_type=$msg[id];	
			break;
		}
		$replaced_msg=str_replace("\\dbr", "\\n",$msg[message]);
		/*
		$replaced_msg=str_replace("}", "",$replaced_msg);
		$replaced_msg=str_replace("{", "",$replaced_msg);
		*/
		//$replaced_msg = utf8_encode($replaced_msg);
		$replaced_msg = trim($replaced_msg);	
		
		$replaced_msg = preg_replace( '/\s+/', ' ', $replaced_msg );
		$evnt_object = json_decode($replaced_msg,true);
		
		
		$svc_color=$btl->getColor($evnt_object[current_state]);
		$svc_state=$btl->getState($evnt_object[current_state]);
		
					$ajax_lbl = "label-default";
					if($svc_color == "green") {
							$ajax_lbl = "label-success";
					}
				
					if($svc_color == "orange") {
							$ajax_lbl = "label-warning";
					}
					if($svc_color == "red") {
							$ajax_lbl = "label-important";
					}
					
		
		
		$output = $evnt_object[type] . " <br>";
		//$output = (" . $evnt_object[service_id] . ")  - ";
		//$output .=  $evnt_object[current_state] . " <br>";
		$output .=  $evnt_object[current_output] . " <br>";

		$msgo = $evnt_object[current_output];
		$st = "<span class='label " . $ajax_lbl  . "'>" . $svc_state . "</span>";
		$lnk =  "<a href='service_detail.php?service_id=" .$evnt_object[service_id] . "'>" .  $evnt_object[server_and_service_name] . " </a>";
	
		
		
		
		$evnts .= "<tr><td>" . date("d.m.Y H:i:s", $msg[time]) . "</td><td>" . $evnt_type . "</td><td>" . $msgo . "</td><td>" . $lnk . "</td><td>" . $st . "</td></tr>";	
	}

	
	$evnts .="</table>";


$not_log  = '<table class="dat1atable table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th style="width:50px">Date</th>
									  <th style="width:100px">Worker</th>
									  <th style="width:100px">Service</th>
									  
									  <th style="width:100px">State</th>
									  <th style="width:100px">Trigger</th>
									  <th style="width:100px">Type</th>
									  <th style="width:100px">Aggregated</th>
									  
								  </tr>
							  </thead>   ';

$not_log_array = array();

for($x=0; $x<MAX_NOTIFICATION_LOG; $x++) {
	$r=bartlby_notification_log_at_index($btl->RES, $x);
	if($r != false && $r[notification_valid] >= 0) {
		$not_log_array[$r[time] . "-" . $r[service_id] . $r[state] . $r[worker_id] . "-" . $x] = $r;
	}
}	
krsort($not_log_array);						  
foreach($not_log_array as $el) {

	$svc_color=$btl->getColor($el[state]);
	$svc_state=$btl->getState($el[state]);
		
	$ajax_lbl = "label-default";
	if($svc_color == "green") {
		$ajax_lbl = "label-success";
	}
				
	if($svc_color == "orange") {
		$ajax_lbl = "label-warning";
	}
	if($svc_color == "red") {
		$ajax_lbl = "label-important";
	}
	if($el[type] == 0) {
		$el_type = "normal/re-notify";
	} else {
		$el_type="standby - escalation";		
	}

	$btl->worker_list_loop(function($wrk, $idx) use (&$el_worker, &$el) {
			if($wrk[worker_id] == $el[worker_id]) {
				$el_worker=$wrk[name];
				return LOOP_BREAK;
			}
	});

	$btl->service_list_loop(function($wrk, $idx) use (&$el_service, &$el) {
			if($wrk[service_id] == $el[service_id]) {
				
				 $el_service = "<a href='service_detail.php?service_id=" .$wrk[service_id] . "'>" .  $wrk[server_name] . "/" .  $wrk[service_name] . " </a>";
				return LOOP_BREAK;
			}
	});

	if($el[aggregation_interval] > 0) {
		$el_agg = "<font color=red>no</font>";
		if($el[aggregated] == 1) {
			$el_agg = "<font color=green>yes</font>";
		} 		
	} else {
		$el_agg = "not aggregatable";
	}
	$el_state = "<span class='label " . $ajax_lbl  . "'>" . $svc_state . "</span>";
	$not_log .= "<tr><td>" . date("d.m.Y H:i:s", $el[time]) . "</td><td>" . $el_worker . "</td><td>" . $el_service . "</td><td>" . $el_state . "</td><td>" . $el[trigger_name] . "</td><td>" . $el_type . "</td><td>" . $el_agg. "</td></tr>";

}


$not_log .= "</table>";

//$layout->AddScript("<script>$(document).ready(function() { $('.1').dataTable();});</script>");
$layout->SetMainTabName("Event Queue");
$layout->Tab("Notification Aggregation Queue", $not_log);
$layout->OUT .= $evnts;


$layout->display();

function hex_dump($data, $newline="\n")
{
  static $from = '';
  static $to = '';

  static $width = 16; # number of bytes per line

  static $pad = '.'; # padding for non-visible characters

  if ($from==='')
  {
    for ($i=0; $i<=0xFF; $i++)
    {
      $from .= chr($i);
      $to .= ($i >= 0x20 && $i <= 0x7E) ? chr($i) : $pad;
    }
  }

  $hex = str_split(bin2hex($data), $width*2);
  $chars = str_split(strtr($data, $from, $to), $width);

  $offset = 0;
  foreach ($hex as $i => $line)
  {
    echo sprintf('%6X',$offset).' : '.implode(' ', str_split($line,2)) . ' [' . $chars[$i] . ']' . $newline;
    $offset += $width;
  }
}