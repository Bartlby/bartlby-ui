<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);


$layout= new Layout();
$layout->setTitle("Select a Servicegroup");
$layout->Form("fm1", $_GET[script]);
$layout->Table("100%");
$layout->set_menu("client");

if($_GET[pkey] && $_GET[pval]) {
	$passthrough = $layout->Field($_GET[pkey], "hidden", $_GET[pval]);
}

$optind = 0;
$servergroups=array();
$btl->servicegroup_list_loop(function($grp, $shm) use(&$servergroups, &$optind) {
	global $_GET;
		if($_GET[dropdown_term] && preg_match("/" . $_GET[dropdown_term] . "/i", $grp[servicegroup_name])) {
			$servergroups[$optind][c]="";
			$servergroups[$optind][k]=$grp[servicegroup_name];	
			$servergroups[$optind][v]=$grp[servicegroup_id];
			$optind++;
		}
	});
	
	
	
	$layout->Tr(
		$layout->Td(
				Array(
					0=>"Servicegroup:",
					1=>$layout->DropDown("servicegroup_id", $servergroups,"", "", false, "ajax_servicegroup_list") . $passthrough
				)
			)
	
	);
	
	$layout->Tr(
		$layout->Td(
				Array(
					0=>Array(
						'colspan'=> 2,
						"align"=>"right",
						'show'=>$layout->Field("Subm", "submit", "next->")
						)
				)
			)
	
	);



$layout->TableEnd();

$layout->FormEnd();
$layout->display();