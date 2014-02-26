<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasright("action.install_package");
$layout= new Layout();

$layout->setTitle("Select a package");
$layout->Form("fm1", "bartlby_action.php");
$layout->Table("100%");
$layout->set_menu("packages");

$optind=0;
//$res=mysql_query("select srv.server_id, srv.server_name from servers srv, rights r where r.right_value=srv.server_id and r.right_key='server' and r.right_user_id=" . $poseidon->user_id);
$dhl=opendir("pkgs");



$types[0][c]="";
$types[0][v]="1";
$types[0][k]="Active";


$types[1][c]="";
$types[1][v]="2";
$types[1][k]="Passive";

$types[2][c]="";
$types[2][v]="3";
$types[2][k]="Group";

$types[3][c]="";
$types[3][v]="4";
$types[3][k]="Local";

$types[4][c]="";
$types[4][v]="5";
$types[4][k]="SNMP";

$types[5][c]="";
$types[5][v]="6";
$types[5][k]="NRPE";

$types[6][c]="";
$types[6][v]="7";
$types[6][k]="NRPE(ssl)";

$types[8][c]="";
$types[8][v]="9";
$types[8][k]="AgentV2(no-SSL)";

$types[7][c]="";
$types[7][v]="8";
$types[7][k]="AgentV2";



$types[9][c]="";
$types[9][v]="10";
$types[9][k]="SSH";

$types[10][c]="";
$types[10][v]="";
$types[10][k]="use package value";
$types[10][s]=1;

$types[11][c]="";
$types[11][v]="-1";
$types[11][k]="use server default type";
$types[11][s]=0;

while($file = readdir($dhl)) {
	//$sr=bartlby_get_server_by_id($btl->CFG, $k);
	
	//$isup=$btl->isServerUp($k);
	//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }
	if(!is_dir("pkgs/" . $file)) {
		$packages[$optind][c]="";
		$packages[$optind][v]=$file;	
		$packages[$optind][k]="" . $file;
		$optind++;
	}
}
closedir($dhl);



$layout->Tr(
	$layout->Td(
			Array(
				0=>"Package:",
				1=>$layout->DropDown("package_name", $packages) . $layout->Field("action", "hidden", "install_package")
			)
		)

);
$layout->Tr(
	$layout->Td(
		array(
			0=>"Override Service Type",
			1=>$layout->DropDown("force_service_type", $types,"") 
		)
	)
);


$layout->Tr(
	$layout->Td(
			Array(
				0=>"Force plugins update:",
				1=>$layout->Field("force_plugins", "checkbox", "checked")
			)
		)

);
$layout->Tr(
	$layout->Td(
			Array(
				0=>"Force perf Handlers update:",
				1=>$layout->Field("force_perf", "checkbox", "checked")
			)
		)

);





$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "submit", "next->") . $layout->Field("server_id", "hidden", $_GET[server_id])
					)
			)
		)

);


$layout->TableEnd();

$layout->FormEnd();
$layout->display();