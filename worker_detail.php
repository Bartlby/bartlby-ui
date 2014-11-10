<?php
function dnl($i) {
	return sprintf("%02d", $i);
}

include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("main.servergroup_detail");
$layout= new Layout();
$layout->set_menu("main");
$layout->setTitle("Actions");
$layout->setMainTabName("Details");


$defaults=array();
$btl->worker_list_loop(function($wrk, $shm) use (&$defaults) {
		global $_GET;
		if($wrk[worker_id] == $_GET[worker_id]) {
			$defaults=$wrk;
			return LOOP_BREAK;
		}
});



if(!$defaults) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}






$triggers = "";
if(strlen($defaults[enabled_triggers]) > 2) {
	$tr_array = explode("|", $defaults[enabled_triggers]);
	for($x=0; $x<count($tr_array); $x++) {
			if($tr_array[$x] != "") {
				$triggers .= $tr_array[$x] . ",";
			}
	}
	
	
}

if($triggers == "") $triggers = "all";


if(strstr((string)$defaults[notify_levels], "|0|")) {
	$levels .= $btl->getColorSpan(0)  . ",";
}
if(strstr((string)$defaults[notify_levels], "|1|")) {
	$levels .= $btl->getColorSpan(1)  . ",";
}
if(strstr((string)$defaults[notify_levels], "|2|")) {
	$levels .= $btl->getColorSpan(2)  . ",";
}
if(strstr((string)$defaults[notify_levels], "|7|")) {
	$levels .= $btl->getColorSpan(7, "Downtime") . ",";
}
if(strstr((string)$defaults[notify_levels], "|8|")) {
	$levels .= $btl->getColorSpan(8, "Sirene") . ",";
}
if($levels == "") $levels="ALL";


$info_box_title='Worker Info';  
$plan_box = $btl->resolveServicePlan($defaults[notify_plan]);

$layout->create_box($info_box_title, $core_content, "worker_detail_info", array(
										"worker" => $defaults,
										"triggers" => $triggers,
										"plan_box" => $plan_box,
										"levels" => $levels
										
										),
			"worker_detail_info");








$layout->OUT .= $btl->GetWorkerOptionsBTN($defaults, $layout, "btn-lg");

$title="Notification Status";  
$content = "asdf";

$w[0][v]=$defaults[worker_id];
$w[0][k]="";
$w[0][opts]=$btl->GetWorkerOptions($defaults, $layout);

$layout->create_box($title, $content, "activity_worker", array(
	'workers' => $w,
), "activity_worker");



					
//$layout->boxes_placed[MAIN]=true;

$r=$btl->getExtensionsReturn("_workerDetails", $layout);





$layout->display("worker_detail");