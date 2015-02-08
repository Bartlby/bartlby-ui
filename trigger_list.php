<?
include "config.php";
include "layout.class.php";
include "bartlby-ui.class.php";


$btl=new BartlbyUi($Bartlby_CONF);


$layout= new Layout();
$layout->setTitle("Select a Trigger");
$layout->Form("fm1", $_GET[script]);
$layout->set_menu("client");

if($_GET[pkey] && $_GET[pval]) {
	$passthrough = $layout->Field($_GET[pkey], "hidden", $_GET[pval]);
}

$optind = 0;
$triggers=array();

$btl->trigger_list_loop(function($grp, $shm) use(&$triggers, &$optind) {
	
	global $_GET;
		if($_GET[dropdown_term] && preg_match("/" . $_GET[dropdown_term] . "/i", $grp[trigger_name])) {
			$triggers[$optind][c]="";
			$triggers[$optind][k]=$grp[trigger_name];	
			$triggers[$optind][v]=$grp[trigger_id];
			$optind++;
		}
	});

	
	
	
	$layout->FormBox(
				Array(
					0=>"Trigger:",
					1=>$layout->DropDown("trigger_id", $triggers,"", "", false, "ajax_trigger_list")  . $layout->Field("Subm", "submit", "next->") .  $passthrough
				)
	);
	
	

$layout->FormEnd();
$layout->display();