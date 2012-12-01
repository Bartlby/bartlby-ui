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



if($dropdownded != "true")  {
	$servs=$btl->GetServiceGroups();
	$optind=0;
	
	for($x=0; $x<count($servs); $x++ ) {
		$servicegroups[$optind][c]="";
		$servicegroups[$optind][k]=$servs[$x][servicegroup_name];	
		$servicegroups[$optind][v]=$servs[$x][servicegroup_id];
		$optind++;
	}
	
	
	
	$layout->Tr(
		$layout->Td(
				Array(
					0=>"Servicegroup:",
					1=>$layout->DropDown("servicegroup_id", $servicegroups)
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
} else {
	$layout->Tr(
		$layout->Td(
				Array(
					0=>Array(
						'colspan'=> 2,
						"align"=>"left",
						'show'=>"Dropdown searches disabled in ui-extra config"
						)
				)
			)
	
	);	
}


$layout->TableEnd();

$layout->FormEnd();
$layout->display();