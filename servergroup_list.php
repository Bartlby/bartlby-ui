<?
include "config.php";
include "layout.class.php";
include "bartlby-ui.class.php";


$btl=new BartlbyUi($Bartlby_CONF);


$layout= new Layout();
$layout->setTitle("Select a Servergroup");
$layout->Form("fm1", $_GET[script]);
$layout->set_menu("client");

if($_GET[pkey] && $_GET[pval]) {
	$passthrough = $layout->Field($_GET[pkey], "hidden", $_GET[pval]);
}

$optind = 0;
$servergroups=array();
$btl->servergroup_list_loop(function($grp, $shm) use(&$servergroups, &$optind) {
	global $_GET;
		if($_GET[dropdown_term] && preg_match("/" . $_GET[dropdown_term] . "/i", $grp[servergroup_name])) {
			$servergroups[$optind][c]="";
			$servergroups[$optind][k]=$grp[servergroup_name];	
			$servergroups[$optind][v]=$grp[servergroup_id];
			$optind++;
		}
	});

	
	
	
	$layout->FormBox(
				Array(
					0=>"Servergroup:",
					1=>$layout->DropDown("servergroup_id", $servergroups,"", "", false, "ajax_servergroup_list")  . $layout->Field("Subm", "submit", "next->") .  $passthrough
				)
	);
	
	

$layout->FormEnd();
$layout->display();