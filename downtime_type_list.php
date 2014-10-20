<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("action.add_downtime");
$layout= new Layout();

$layout->setTitle("");

$layout->set_menu("downtimes");

$ov .= $layout->Form("fm1", "bartlby_action.php", "GET", true);





$optind=0;
//$res=mysql_query("select srv.server_id, srv.server_name from servers srv, rights r where r.right_value=srv.server_id and r.right_key='server' and r.right_user_id=" . $poseidon->user_id);


$downtime_type[$optind][c]="";
$downtime_type[$optind][k]="Service";	
$downtime_type[$optind][v]=1;
$optind++;


$downtime_type[$optind][c]="";
$downtime_type[$optind][k]="Server";	
$downtime_type[$optind][v]=2;
$optind++;


$downtime_type[$optind][c]="";
$downtime_type[$optind][k]="ServerGroup";	
$downtime_type[$optind][v]=3;
$optind++;


$downtime_type[$optind][c]="";
$downtime_type[$optind][k]="ServiceGroup";	
$downtime_type[$optind][v]=4;
$optind++;


$ov .= $layout->FormBox(
				Array(
					0=>"Type:",
					1=>$layout->DropDown("downtime_type", $downtime_type)  . $layout->Field("Subm", "button", "next->", "" ,"onClick='downtime_type_selected();'")
				)
	
	,true);


$title="Select Downtime Type";  
$content = "<span class=form-horizontal>" . $ov . "</span>";
$layout->create_box($title, $content);
	

$layout->FormEnd();
$layout->boxes_placed[MAIN]=true;

$layout->display();