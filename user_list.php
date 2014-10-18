<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);

$layout= new Layout();
$layout->set_menu("worker");
$layout->setTitle("Select a  Worker");
$layout->Form("fm1", $_GET[script]);


$servs=$btl->GetWorker();
$optind=0;



	while(list($k, $v) = @each($servs)) {
		
		
		$v1=bartlby_get_worker_by_id($btl->RES, $v[worker_id]);
		
		$servers[$optind][c]="";
		$servers[$optind][v]=$v1[worker_id];	
		$servers[$optind][k]=$v1[name];
		$optind++;
	}
	
	
	
	$layout->FormBox(
				Array(
					0=>"Worker:",
					1=>$layout->DropDown("worker_id", $servers) . $layout->Field("Subm", "submit", "next->")
				)
	);
	

$layout->FormEnd();
$layout->display();