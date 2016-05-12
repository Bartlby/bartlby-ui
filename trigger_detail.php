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
$layout->setTitle("Trigger");
$layout->setMainTabName("Details");
$defaults=array();
$btl->trigger_list_loop(function($grp, $shm) use (&$defaults) {
		global $_GET;
		if($grp[trigger_id] == $_GET[trigger_id]) {
			$defaults=$grp;
			return LOOP_BREAK;
		}
});



if(!$defaults) {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}


if($defaults["trigger_enabled"]==1) {
	$trigger_enabled="<input type=checkbox class='switch'  disabled checked>";
} else {
	$trigger_enabled="<input type=checkbox class='switch'  disabled>";
}


$info_box_title='Trigger Info'; 



switch($defaults["trigger_type"]) {
	case TRIGGER_TYPE_LOCAL:
		$trigger_type = "BASH local /trigger dir";
	break;
	case TRIGGER_TYPE_SMTP:
		$trigger_type = "SMTP";
	break;
	case TRIGGER_TYPE_LUA:
		$trigger_type = "LUA";
	break;
	case TRIGGER_TYPE_WEBHOOKS:
		$trigger_type = "WEBHOOKS";
	break;
	default:
		$trigger_type = $defaults["trigger_type"];
	break;
}



$layout->create_box($info_box_title, $core_content, "trigger_detail_trigger_info", array(
										"trigger" => $defaults,
										"trigger_enabled" => $trigger_enabled,
										"trigger_type" => $trigger_type
										),
			"trigger_detail_trigger_info");







	

$r=$btl->getExtensionsReturn("_triggerDetails", $layout);

$layout->OUT .= $btl->gettriggerOptions($defaults, $layout, "btn-lg");





$layout->display("trigger_detail");