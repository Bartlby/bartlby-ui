<?
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";



$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}


$layout= new Layout();


//$layout->set_menu("client");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);





$defaults = array();
$btl->trigger_list_loop(function($trigger, $shm) use(&$defaults) {
	global $_GET;
	if($trigger[trigger_id] == $_GET[trigger_id]) {

		$defaults=$trigger;

		return LOOP_BREAK;	
	}
});

//error_reporting(E_ALL);


$fm_action="modify_trigger";
$layout->setTitle("Modify Trigger");
if($_GET["copy"] == "true") {
	$fm_action="add_trigger";
	$btl->hasRight("action.copy_trigger");
	$layout->setTitle("Copy Trigger");
		
}
if($_GET["new"] == "true") {
	$fm_action="add_trigger";
	$btl->hasRight("action.add_trigger");
	$layout->setTitle("Add Trigger");
	
	
	
}

$trigger_type[0][c]="";
$trigger_type[0][v] = 1; //No
$trigger_type[0][k] = "TRIGGER (executable inside bartlby triggers)"; //No
$trigger_type[0][s]=0;

$trigger_type[1][c]="";
$trigger_type[1][v] = 2; //No
$trigger_type[1][k] = "Shell"; //No
$trigger_type[1][s]=0;

$trigger_type[2][c]="";
$trigger_type[2][v] = 3; //No
$trigger_type[2][k] = "WEBHOOK"; //No
$trigger_type[2][s]=0;


$trigger_type[3][c]="";
$trigger_type[3][v] = 4; //No
$trigger_type[3][k] = "Lua"; //No
$trigger_type[3][s]=0;

switch($defaults[trigger_type]) {
	case 1:
		$trigger_type[0][s]=1;
	break;
	case 2:
		$trigger_type[1][s]=1;
	break;
	case 3:
		$trigger_type[2][s]=1;
	break;
	
}




if(is_int($defaults[trigger_enabled]) && $defaults[trigger_enabled] == 0) {
	$trigger_enabled="";	
	
} else {
	
	$trigger_enabled="checked";
}

if($fm_action == "modify_trigger") {
	$btl->hasRight("action.modify_trigger");	
}


if($defaults == false && $_GET["new"] != "true") {
	$btl->redirectError("BARTLBY::OBJECT::MISSING");
	exit(1);	
}

$ov .= $layout->FormBox(
		array(
			0=>"Name",
			1=>$layout->Field("trigger_name", "text", $defaults[trigger_name]) . $layout->Field("action", "hidden", $fm_action)  . $layout->Field("trigger_id", "hidden", $_GET[trigger_id])
		)
,true);




$ov .= $layout->FormBox(
		array(
			0=>"Enabled?",
			1=>$layout->Field("trigger_enabled", "checkbox", "1", "", "class='switch' " . $trigger_enabled)
			
		)
,true);

$ov .= $layout->FormBox(
		array(
			0=>"Type:",
			1=>$layout->DropDown("trigger_type", $trigger_type)
			
		)
,true);


$o = explode("|", $defaults[trigger_execplan]);


for($x=0; $x<count($o); $x++) {
	$p = explode("=", $o[$x]);
	
	if(count($p) == 1) {
		$p = explode("!", $o[$x]);
		$filled[$p[0]][disabled]=1;
		
		
	}
	
	
	$filled[$p[0]][value]=$p[1];
	
	
}
$plan_box = "<table class='no-border'><tbody class='no-border-y'>";
for($x=0; $x<=6; $x++) {
	$chk="";
	
	
	$inv_check="";
	if($filled[$x][disabled] == 1) {
		$inv_check="checked";
	}	
		
	$plan_box .= "<tr><td><font size=1>" .  $wdays[$x] . "</font></td><td><input type=text id='wdays_plan[" . $x . "]'  name='wdays_plan[" . $x . "]' value='" . $filled[$x][value] . "' style='font-size:10px; width:200px; height:20px'><input type=checkbox id='wdays_inv[" . $x . "]'  name='wdays_inv[" . $x . "]' $inv_check class=icheck> invert</td></tr>";
	
}
$plan_box .= "<tr><td colspan=2><font size=1>Time ranges are seperated with ',' e.g.: 14:30-15:20,01:20-02:30 <a href='javascript:void(0);' onClick='modify_service_make_24();'>make 24h a day</a></font></td></tr>";
$plan_box .= "</tbody></table>";



$ov .= $layout->FormBox(
		array(
			0=>"Exec Plan:",
			1=>$plan_box
			
		)
, true);



$ov .= $layout->FormBox(
		array(
			0=>"Data",
			1=>$layout->codemirror("trigger_data", nl_safe($defaults[trigger_data]), "lua", 1023,650, 300, array(
					array("label"=>"SMTP", "script"=>"/sample_scripts/trigger_sample.smtp"),
					array("label"=>"LUA", "script"=>"/sample_scripts/trigger_sample.lua"),
					array("label"=>"Webhook", "script"=>"/sample_scripts/trigger_sample.webhook"),
					
				))
		)
,true);




$ov .= $layout->FormBox(

		array(
			0=>"Orchestra ID",
			1=>$layout->orchDropdown(true, $defaults[orch_id]) .  $layout->Field("Subm", "button", "next->", "", " onClick='xajax_AddModifyTrigger(xajax.getFormValues(\"fm1\"))'")
		)
,true);



$title="add trigger";  
$content = "<span class=form-horizontal>" . $ov . "</span>";
$layout->create_box($layout->BoxTitle, $content);
	
	
$r=$btl->getExtensionsReturn("_PRE_" . $fm_action, $layout);
$layout->FormEnd();


//HIDE MAIN
$layout->boxes_placed[MAIN]=true;


$layout->display();

function nl_safe($str) {
	$str = str_replace("\n", "\\n", $str);
	//$str = str_replace("\\", "\\\\", $str);
	return $str;
}
