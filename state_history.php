<?

include "config.php";
include "layout.class.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasright("log.logview");
$info=$btl->getInfo();
$layout= new Layout();
$layout->set_menu("report");




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
	
	$logf=bartlby_config($btl->CFG, "statehistory_logdir") .  "/" .  (int)$_GET[service_id] . "-" .  date("Y.m.d", $ch_time) . ".history";
	$xc = 0;

	
	$fla=@file_get_contents($logf);

	$ARR = @explode("#############REC##############", $fla);
	$fl=@array_reverse($ARR);

	

	$ajax_total_records=0;
	$ajax_displayed_records=0;
	while(list($k, $v)=@each($fl)) {
		$ajax_total_records++;

		
		$jso = json_decode($v, true);
		
		if(preg_match("/" . $_GET["text_filter"] . "/i", $jso[output]) && $jso[last_write] != "") {

			$ajax_displayed_records++;
			if($xc >= $_GET[iDisplayStart] && $xc < $_GET[iDisplayStart]+$_GET[iDisplayLength]) {
				$ajax_search["aaData"][] = array(date("d.m.Y H:i:s", $jso[last_write]), $btl->getColorSpan($jso[current_state]), $jso[output]);
							
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

?>