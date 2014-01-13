<?
include "config.php";
include "layout.class.php";

include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);


$layout= new Layout();
$layout->setTitle("Select a Server");
$layout->Form("fm1", $_GET[script]);
$layout->Table("100%");
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

	$layout->Tr(
		$layout->Td(
				Array(
					0=>"Server:",
					1=>$layout->DropDown("server_id", $servers,"","",false, "ajax_server_list_php") . $passthrough
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