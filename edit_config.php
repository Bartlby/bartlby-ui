<?php
include "layout.class.php";
include "config.php";
include "bartlby-ui.class.php";
$btl=new BartlbyUi($Bartlby_CONF);
$btl->hasRight("core.edit_cfg");
$layout= new Layout();
$layout->set_menu("core");
$layout->setTitle("Edit Config");
$layout->Form("fm1", "bartlby_action.php","POST");

switch($_GET[ecfg]) {
	case 'bartlby.cfg':
		$cfgfile=$btl->CFG;
	break;	
	case 'ui-extra.conf':
		$cfgfile="ui-extra.conf";
	break;
}
$ecfg=$_GET[ecfg];
$cur_conf=implode(file($cfgfile), "");

$layout->OUT .= $layout->TextArea("cfg_file", $cur_conf, 35, 80) . $layout->Field("action", "hidden", "edit_cfg") . $layout->Field("ecfg", "hidden", $ecfg) . $layout->Field("Subm", "submit", "next->");






$layout->FormEnd();
$layout->display();