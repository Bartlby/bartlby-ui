<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);

$layout= new Layout();

$layout->setTitle("Select a Service");
$layout->Form("fm1", $_GET[script]);
$layout->set_menu("services");

$optind=0;

if($_GET[pkey] && $_GET[pval]) {
	$passthrough = $layout->Field($_GET[pkey], "hidden", $_GET[pval]);
}

$servers_out=array();
$services_x=0;
$btl->service_list_loop(function($svc, $shm) use(&$servers, &$optind, &$btl, &$servers_out, &$services_x) {
	if($svc[is_gone] != 0) {
	 return LOOP_CONTINUE;
	}
	if($_GET[dropdown_term] &&  @preg_match("/" . $_GET[dropdown_term] . "/i", $svc[server_name] . "/" .  $svc[service_name])) {
		if(!is_array($servers_out[$svc[server_id]])) {
			$servers_out[$svc[server_id]]=array();
		}
		array_push($servers_out[$svc[server_id]], $svc);
		$services_x++;
		if($services_x > 50) return LOOP_BREAK;
	}
});			
ksort($servers_out);



$optind=0;

while(list($k, $servs) = @each($servers_out)) {
	for($x=0; $x<count($servs); $x++) {
			if($x == 0) {
				//$isup=$btl->isServerUp($v1[server_id]);
				//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }
				$servers[$optind][c]="";
				$servers[$optind][v]="s" . $servs[$x][server_id];	
				$servers[$optind][k]="" . $servs[$x][server_name] . "";
				$servers[$optind][is_group]=1;
				$optind++;
			}
			if($servs[$x][is_gone] != 0) {
					continue;
			}
			
			$state=$btl->getState($servs[$x][current_state]);
			if($servs[$x][service_id] == $defaults[server_dead]) {
				$servers[$optind][s]=1;
			}
			$servers[$optind][c]="";
			$servers[$optind][v]=$servs[$x][service_id];	
			$servers[$optind][k]=$servs[$x][server_name] . "/" .  $servs[$x][service_name];
			$optind++;
			
		}
}

		
			
	
	
	
	$layout->FormBox(
				array(
					0=>"Service:",
					1=>$layout->DropDown("service_id", $servers,"","",false, "ajax_service_list_php") . $layout->Field("Subm", "submit", "next->") .  $passthrough
				)
	);
	





$layout->FormEnd();
$layout->display();