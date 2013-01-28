<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);


if($Bartlby_CONF_Remote == true && $Bartlby_CONF_DBSYNC == false) {
	$btl->redirectError("BARTLBY::INSTANCE::IS_REMOTE");
}

$layout= new Layout();
$layout->setTitle("Delete Servicegroup");
$layout->set_menu("client");
$layout->Form("fm1", "bartlby_action.php");
$layout->Table("100%");



$btl->hasRight("action.delete_servicegroup");

$global_msg[servicegroup_name]="asdf";

$dlmsg=$btl->finScreen("delete_servicegroup1");

$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					'show'=>$dlmsg
					)
			)
		)

);




$layout->Tr(
	$layout->Td(
			Array(
				0=>Array(
					'colspan'=> 2,
					"align"=>"right",
					'show'=>$layout->Field("Subm", "submit", "next->") . $layout->Field("action", "hidden", "delete_servicegroup") . $layout->Field("servicegroup_id", "hidden", $_GET[servicegroup_id])
					)
			)
		)

);


$layout->TableEnd();
$layout->FormEnd();
$layout->display();