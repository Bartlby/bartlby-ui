<?
include "config.php";
include "layout.class.php";
include "bartlby-ui.class.php";


$btl=new BartlbyUi($Bartlby_CONF);


$layout= new Layout();
$layout->setTitle("Select a Trap");
$layout->Form("fm1", $_GET[script]);
$layout->set_menu("client");

if($_GET[pkey] && $_GET[pval]) {
	$passthrough = $layout->Field($_GET[pkey], "hidden", $_GET[pval]);
}

$optind = 0;
$traps=array();
$btl->trap_list_loop(function($grp, $shm) use(&$traps, &$optind) {
	global $_GET;
		if($_GET[dropdown_term] && preg_match("/" . $_GET[dropdown_term] . "/i", $grp[trap_name])) {
			$traps[$optind][c]="";
			$traps[$optind][k]=$grp[trap_name];	
			$traps[$optind][v]=$grp[trap_id];
			$optind++;
		}
	});

	
	
	
	$layout->FormBox(
				Array(
					0=>"Trap:",
					1=>$layout->DropDown("trap_id", $traps,"", "", false, "ajax_trap_list")  . $layout->Field("Subm", "submit", "next->") .  $passthrough
				)
	);
	
	

$layout->FormEnd();
$layout->display();