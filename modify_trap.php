<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";



$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}


$layout= new Layout();


//$layout->set_menu("client");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);




//$defaults=bartlby_get_trap_by_id($btl->RES, $_GET[server_id]);
$defaults = array();
$btl->trap_list_loop(function($grp, $shm) use(&$defaults) {
	global $_GET;
	if($grp[trap_id] == $_GET[trap_id]) {

		$defaults=$grp;

		return LOOP_BREAK;	
	}
});

//error_reporting(E_ALL);


$fm_action="modify_trap";
$layout->setTitle("Modify trap");
if($_GET["copy"] == "true") {
	$fm_action="add_trap";
	$btl->hasRight("action.copy_trap");
	$layout->setTitle("Copy trap");
		
}
if($_GET["new"] == "true") {
	$fm_action="add_trap";
	$btl->hasRight("action.add_trap");
	$layout->setTitle("Add trap");
	
	
	
}






if(is_int($defaults[trap_notify]) && $defaults[trap_notify] == 0) {
	$notenabled="";	
	
} else {
	
	$notenabled="checked";
}

if($fm_action == "modify_trap") {
	$btl->hasRight("action.modify_trap");	
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$ov .= $layout->FormBox(
		array(
			0=>"Name",
			1=>$layout->Field("trap_name", "text", $defaults[trap_name]) . $layout->Field("action", "hidden", $fm_action) 
		)
,true);

$ov .= $layout->FormBox(

		array(
			0=>"Orchestra ID",
			1=>$layout->orchDropdown(true, $defaults[orch_id]) .  $layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyTrap(xajax.getFormValues(\"fm1\"))'")
		)
,true);



$title="add trap";  
$content = "<span class=form-horizontal>" . $ov . "</span>";
$layout->create_box($layout->BoxTitle, $content);
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);
$layout->FormEnd();


//HIDE MAIN
$layout->boxes_placed[MAIN]=true;


$layout->display();