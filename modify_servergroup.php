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

$servergroups=$btl->GetServerGroups();
for($x=0; $x<count($servergroups); $x++) {
	if($servergroups[$x][servergroup_id] == $_GET[servergroup_id]) {
		$defaults=$servergroups[$x];
		break;	
	}
}

$optind=0;


	$servs=$btl->GetServers();
	$optind=0;
	
	while(list($k, $v) = @each($servs)) {
		$servers[$optind][c]="";
		$servers[$optind][v]=$k;	
		$servers[$optind][k]="" . $v;
		
		if(strstr($defaults[servergroup_members], "|" . $k . "|")) {
			$servers[$optind][s]=1;
		}
		
		$optind++;
	}
	
	

$optind=0;



//error_reporting(E_ALL);


$fm_action="modify_servergroup";
$layout->setTitle("Modify Servergroup");
if($_GET["copy"] == "true") {
	$fm_action="add_servergroup";
	$btl->hasRight("action.copy_servergroup");
	$layout->setTitle("Copy Servergroup");
}
if($_GET["new"] == "true") {
	$fm_action="add_servergroup";
	$btl->hasRight("action.add_servergroup");
	$layout->setTitle("Add Servergroup");
	
	
	
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

if(is_int($defaults[servergroup_notify]) && $defaults[servergroup_notify] == 0) {
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


if(is_int($defaults[servergroup_active]) && $defaults[servergroup_active] == 0) {
	$servactive[0][s]=1;	
	
} else {

	$servactive[1][s]=1;
}


if($fm_action == "modify_servergroup") {
	$btl->hasRight("action.modify_servergroup");	
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servergroup Name",
			1=>$layout->Field("servergroup_name", "text", $defaults[servergroup_name]) . $layout->Field("action", "hidden", $fm_action) 
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servergroup Members",
			1=>$layout->DropDown("servergroup_members[]", $servers,"multiple")
		)
	)
,true);


$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servergroup Enabled",
			1=>$layout->DropDown("servergroup_active", $servactive)
			
		)
	)
,true);

$ov .= $layout->Tr(
	$layout->Td(
		array(
			0=>"Servergroup Notify?",
			1=>$layout->DropDown("servergroup_notify", $notenabled) . $layout->Field("servergroup_id", "hidden", $_GET[servergroup_id])
			
		)
	)
,true);











$title="add servergroup";  
$content = "<table>" . $ov . "</table>";
$layout->push_outside($layout->create_box($layout->BoxTitle, $content));
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);

$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyServerGroup(xajax.getFormValues(\"fm1\"))'")
					)
			)
		)

,false);

$layout->TableEnd();
$layout->FormEnd();
$layout->display();