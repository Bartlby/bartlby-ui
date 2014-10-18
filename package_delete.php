<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("action.delete_package");
$layout= new Layout();

$layout->setTitle("Select a package");
$layout->Form("fm1", "bartlby_action.php");
$layout->set_menu("packages");

$optind=0;
$dhl=opendir("pkgs");

while($file = readdir($dhl)) {
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
				1=>$layout->DropDown("package_name", $packages) . $layout->Field("action", "hidden", "delete_package_ask") . $layout->Field("Subm", "submit", "next->")
			)
);


$layout->FormEnd();
$layout->display();