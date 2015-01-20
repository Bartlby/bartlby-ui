<?
include "config.php";
include "layout.class.php";

include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);


$layout= new Layout();
$layout->setTitle("Select a Server");
$layout->Form("fm1", $_GET[script]);
$layout->set_menu("client");

$servers=array();

if($_GET[pkey] && $_GET[pval]) {
	$passthrough = $layout->Field($_GET[pkey], "hidden", $_GET[pval]);
}

	$optind=0;
	$btl->server_list_loop(function($srv, $shm) use(&$servers, &$optind) {
		global $_GET;
		
		if($_GET[dropdown_term] && @preg_match("/" . $_GET[dropdown_term] . "/i", $srv[server_name])) {
			$servers[$optind][c]="";
			$servers[$optind][k]=$srv[server_name];	
			$servers[$optind][v]="" . $srv[server_id];
			$optind++;
		}
	});

	$layout->FormBox(
				array(
					0=>"Server:",
					1=>$layout->DropDown("server_id", $servers,"","",false, "ajax_server_list_php") . $layout->Field("Subm", "submit", "next->") . $passthrough
				)
	);
	
	



$layout->FormEnd();
$layout->display();