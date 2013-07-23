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
	$evnts = '<table class="table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th style="width:150px">Date</th>
									  <th style="width:100px">Type</th>
									  <th style="width:100px">Output</th>
									  
									  <th style="width:100px">Service</th>
									  <th style="width:100px">State</th>
									  
								  </tr>
							  </thead>   ';
	
	for($x=128; $x>=0; $x--) {
		$msg=bartlby_event_fetch($btl->CFG, $x);
		
		if($msg[id] == 0) {
			continue;	
		}
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