<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);

$layout= new Layout();

$layout->setTitle("Select a package");
$layout->Form("fm1", "bartlby_action.php");

$layout->set_menu("packages");

$optind=0;
//$res=mysql_query("select srv.server_id, srv.server_name from servers srv, rights r where r.right_value=srv.server_id and r.right_key='server' and r.right_user_id=" . $poseidon->user_id);
$dhl=opendir("pkgs");

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



$layout->FormBox(
			Array(
				0=>"Package:",
				1=>$layout->DropDown("package_name", $packages) . $layout->Field("action", "hidden", "uninstall_package") . $layout->Field("Subm", "submit", "next->") . $layout->Field("server_id", "hidden", $_GET[server_id])
			)
);



$layout->FormEnd();
$layout->display();