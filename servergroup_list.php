<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);


$layout= new Layout();
$layout->setTitle("Select a Servergroup");
$layout->Form("fm1", $_GET[script]);
$layout->Table("100%");
$layout->set_menu("client");



if($dropdownded != "true")  {
	$servs=$btl->GetServerGroups();
	$optind=0;
	//$res=mysql_query("select srv.server_id, srv.server_name from servers srv, rights r where r.right_value=srv.server_id and r.right_key='server' and r.right_user_id=" . $poseidon->user_id);
	
	for($x=0; $x<count($servs); $x++ ) {
		$servergroups[$optind][c]="";
		$servergroups[$optind][k]=$servs[$x][servergroup_name];	
		$servergroups[$optind][v]=$servs[$x][servergroup_id];
		$optind++;
	}
	
	
	
	$layout->Tr(
		$layout->Td(
				Array(
					0=>"Servergroup:",
					1=>$layout->DropDown("servergroup_id", $servergroups)
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