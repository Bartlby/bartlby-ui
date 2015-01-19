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
$layout->setTitle("Traps");
$layout->setMainTabName("Details");
$defaults=array();
$btl->trap_list_loop(function($grp, $shm) use (&$defaults) {
		global $_GET;
		if($grp[trap_id] == $_GET[trap_id]) {
			$defaults=$grp;
			return LOOP_BREAK;
		}
});



if(!$defaults) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}



if($defaults["trap_is_final"]==1) {
	$is_final="<input type=checkbox class=switch checked disabled>";
} else {
	$is_final="<input type=checkbox class=switch disabled>";
}
$info_box_title='Trap Info';  


$layout->create_box($info_box_title, $core_content, "trap_detail_trap_info", array(
										"trap" => $defaults,
										),
			"trap_detail_trap_info");



$info_box_title='Last Data';  
$layout->create_box($info_box_title, $defaults[trap_last_data] ? date("d.m.Y H:i:s", $defaults[trap_last_match]) . "<br>" . $defaults[trap_last_data] : "no data received", "trap_last_data");



$info_box_title='Rules';  
$layout->create_box($info_box_title, $core_content, "trap_detail_trap_rules", array(
										"trap" => $defaults,
										"is_final" => $is_final,
										),
			"trap_detail_trap_rules");



	

$r=$btl->getExtensionsReturn("_trapDetails", $layout);

$layout->OUT .= $btl->getTrapOptions($defaults, $layout, "btn-lg");





$layout->display("trap_detail");