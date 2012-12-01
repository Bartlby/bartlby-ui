<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";



$btl=new BartlbyUi($Bartlby_CONF);

$layout= new Layout();


//$layout->set_menu("client");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);
$layout->Table("100%");



//$defaults=bartlby_get_servergroup_by_id($btl->CFG, $_GET[server_id]);

$servicegroups=$btl->GetServiceGroups();
for($x=0; $x<count($servicegroups); $x++) {
	if($servicegroups[$x][servicegroup_id] == $_GET[servicegroup_id]) {
		$defaults=$servicegroups[$x];
		break;	
	}
}

$map = $btl->GetSVCMap();


$optind=0;
while(list($k, $servs) = @each($map)) {

	for($x=0; $x<count($servs); $x++) {
		
				
		if($x == 0) {
			//$isup=$btl->isServerUp($v1[server_id]);
			//if($isup == 1 ) { $isup="UP"; } else { $isup="DOWN"; }
			$servers[$optind][c]="";
			$servers[$optind][v]="s" . $servs[$x][server_id];	
			$servers[$optind][k]="" . $servs[$x][server_name] . "";
			$servers[$optind][is_group]=1;
			
			$optind++;

	} else {
      $servers[$optind][c]="";
 		  $servers[$optind][v]=$servs[$x][service_id];
      $servers[$optind][k]=$servs[$x][server_name] . "/" .  $servs[$x][service_name];
      
     
     
      	if(strstr($defaults[servicegroup_members], "|" . $servs[$x][service_id] . "|")) {
      		$servers[$optind][s]=1;
      		
      	}
      
      
      
      $optind++;
	}	
	
}
}



//error_reporting(E_ALL);


$fm_action="modify_servicegroup";
$layout->setTitle("Modify Servicegroup");
if($_GET["copy"] == "true") {
	$fm_action="add_servicegroup";
	$btl->hasRight("action.copy_servicegroup");
	$layout->setTitle("Copy Servicegroup");
}
if($_GET["new"] == "true") {
	$fm_action="add_servicegroup";
	$btl->hasRight("action.add_servicegroup");
	$layout->setTitle("Add Servicegroup");
	
	
	
}

//Notify Enabled
$notenabled[0][c]="";
$notenabled[0][v] = 0; //No
$notenabled[0][k] = "No"; //No
$notenabled[0][s]=0;

$notenabled[1][c]="";
$notenabled[1][v] = 1; //No
$notenabled[1][k] = "Yes"; //No
$notenabled[1][s]=0;

if(is_int($defaults[servicegroup_notify]) && $defaults[servicegroup_notify] == 0) {
	$notenabled[0][s]=1;	
	
} else {
	
	$notenabled[1][s]=1;
}

//Notify Enabled
$servactive[0][c]="";
$servactive[0][v] = 0; //No
$servactive[0][k] = "No"; //No
$servactive[0][s]=0;

$servactive[1][c]="";
$servactive[1][v] = 1; //No
$servactive[1][k] = "Yes"; //No
$servactive[1][s]=0;


if(is_int($defaults[servicegroup_active]) && $defaults[servicegroup_active] == 0) {
	$servactive[0][s]=1;	
	
} else {

	$servactive[1][s]=1;
}


if($fm_action == "modify_servicegroup") {
	$btl->hasRight("action.modify_servicegroup");	
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servicegroup Name",
			1=>$layout->Field("servicegroup_name", "text", $defaults[servicegroup_name]) . $layout->Field("action", "hidden", $fm_action) 
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servicegroup Members",
			1=>$layout->DropDown("servicegroup_members[]", $servers,"multiple", "", false)
		)
	)
,true);


$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servicegroup Enabled",
			1=>$layout->DropDown("servicegroup_active", $servactive)
			
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servicegroup Notify?",
			1=>$layout->DropDown("servicegroup_notify", $notenabled) . $layout->Field("servicegroup_id", "hidden", $_GET[servicegroup_id])
			
		)
	)
,true);











$title="add servicegroup";  
$content = "<table>" . $ov . "</table>";
$layout->push_outside($layout->create_box($layout->BoxTitle, $content));
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);

$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyServiceGroup(xajax.getFormValues(\"fm1\"))'")
					)
			)
		)

,false);

$layout->TableEnd();
$layout->FormEnd();
$layout->display();