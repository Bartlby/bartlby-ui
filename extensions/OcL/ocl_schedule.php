<?
error_reporting(E_ALL);
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/OcL/OcL.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("ocl_view");
	$ocl = new OcL();
	

if(!$_GET[update]) {
	$sql = "select id, strftime('%s', date_from) as dfrom, strftime('%s', date_to) as dto, all_day, color, worker_id, activity_level from schedule where strftime('%s', date_from) >= " . $_GET[start] ;
	$r = $ocl->db_schedule->query($sql);

	$events=array();
	foreach($r as $row) {
		$aday=false;

		if($row[all_day] == 1) {
		
			$aday=true;
		}
		$wrk_name="";
		$btl->worker_list_loop(function($wrk, $shm) use(&$wrk_name, &$row) {
			if($wrk[worker_id] == (int)$row[worker_id]) {
				$wrk_name=$wrk[name];
				
				return LOOP_BREAK;
			}
		});
		if($row[activity_level] == 1) {
			$alevel = "ACTIVE";
		} else {
			$alevel = "STANDBY";
		}
		$event[id] = $row[id];
		$event[start] = $row[dfrom];
		$event[end] = $row[dto];
		$event[title] = $wrk_name . " (" . $alevel . ")";	
		$event[allDay] = $aday;
		$event[color] = $row[color];
		$events[] = $event;
	}

	echo json_encode($events);
} else {
	switch($_GET[update]) {
		case 3:
			$sql = "delete from schedule where id="  . $_GET[id];
			$ocl->db_schedule->exec($sql);
		break;
		case 2:
			$sql = "insert into schedule (date_from, date_to, worker_id, activity_level, all_day, color) values(DATETIME(" . $_GET[dfrom] . ", 'unixepoch'), DATETIME(" . $_GET[dto] . ", 'unixepoch')," .  $_GET[worker_id] . "," . $_GET[activity_level] . ", 1, '" . $_GET[color] . "')";
			
			$ocl->db_schedule->exec($sql);
			echo $ocl->db_schedule->lastInsertId();
		break;
		case 1:
			$aday=0;
			if($_GET[allday] == "true") {
				$aday=1;
			}
			$sql = "update schedule set date_from=DATETIME(" . $_GET[dfrom] . ", 'unixepoch'), date_to=DATETIME(" . $_GET[dto] . ", 'unixepoch'), all_day=" . $aday . " where id=" . $_GET[id];
			
			$ocl->db_schedule->exec($sql);
		break;
		default:
		break;

	}


}

?>