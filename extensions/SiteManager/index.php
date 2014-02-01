<?

	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/SiteManager/SiteManager.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("sitemanager");
	$sm = new SiteManager();
	
	
	ini_set('display_errors', '1');
	error_reporting(E_ERROR);

	$layout= new Layout();
	$layout->setTitle("SiteManager");
	$layout->set_menu("SiteManager");
	$layout->setMainTabName("Master-Settings");
	$layout->do_auto_reload=false;



	if($Bartlby_CONF_isMaster != true) {
		$layout->OUT = "Sites is only available on Master";
		$layout->display();
		exit;
	}
	/* Add Extension JS */
	$layout->OUT .= '<script src="extensions/SiteManager/sm.js" type="text/javascript"></script>';

	$mgmt_content = local_box_render("sm_mgmt.php");

	$sm_form = local_box_render("sm_form.php");
	$sm_form_remote = local_box_render("sm_form_remote.php");
	$sm_form_local = local_box_render("sm_form_local.php");
	$sm_form_add_folders = local_box_render("sm_form_add_folders.php");
	$sm_form_end = local_box_render("sm_form_end.php");



	$layout->create_box("Manage", $mgmt_content, "sm_manage");
	$layout->create_box("Core Settings", $sm_form, "sm_add");
	$layout->create_box("Remote DB", $sm_form_remote, "sm_form_remote");
	$layout->create_box("Local DB", $sm_form_local, "sm_form_local");
	$layout->create_box("Additional Folders", $sm_form_add_folders, "sm_form_addfolders");
	$layout->create_box("Action", $sm_form_end, "sm_form_end");
	//$layout->create_box("title", "Sync Content", "sm_sync");
	

	
	$frm_tab = $layout->disp_box("sm_add");
	
	$frm_tab .=  "<div style='clear: both;'></div>";
	$frm_tab .=  "<div id=service_detail_service_info_ajax class='fifty_float_left'>";
	$frm_tab .= $layout->disp_box("sm_form_local");
	$frm_tab .= "</div>";


	$frm_tab .=  "<div id=service_detail_service_info_ajax class='fifty_float_left'>";
	$frm_tab .= $layout->disp_box("sm_form_remote");
	$frm_tab .= "</div>";

	$frm_tab .=  "<div style='clear: both;'></div>";
	$frm_tab .= $layout->disp_box("sm_form_addfolders");
	$frm_tab .= $layout->disp_box("sm_form_end");



	$layout->Tab("Manage", $layout->disp_box("sm_manage"), "sm_manage");
	$layout->Tab("Add/Modify",$frm_tab, "sm_add");
	//$layout->Tab("Sync", $layout->disp_box("sm_sync"), "sm_sync");




	$layout->OUT .= "<b>Core Settings</b><br>";
	$layout->OUT .= "Local UI Path:<br>";
	$layout->OUT .= "<input type=text value='' id=local_ui_path>(e.g.:/var/www/bartlby-ui/)<br>";
	$layout->OUT .= "Local Core Path:<br>";
	$layout->OUT .= "<input type=text value='' id=local_core_path>(e.g.:/opt/bartlby/)<br>";
	$layout->OUT .= "Local Core Replication Path:<br>";
	$layout->OUT .= "<input type=text value='' id=local_core_replication_path>(e.g.:/opt/bartlby/nodes/)<br>";
	$layout->OUT .= "Local UI Replication Path:<br>";
	$layout->OUT .= "<input type=text value='' id=local_ui_replication_path>(e.g.:/var/www/bartlby-ui/nodes/)<br>";


	
	$layout->OUT .= "<input type=button value='Save' id=sm_save_local><br>";


	if($sm->db == false) $layout->OUT .= "<br>PHP SQLITE is not installed";
	$layout->boxes_placed[MAIN]=false;
	$layout->display();
	
	
function local_box_render($file, $plcs = array()) {
	global $sm;
	$boxes_path="extensions/SiteManager/boxes/" . $file;
	ob_start();
	include($boxes_path);
	$o = ob_get_contents();	
	ob_end_clean();	
	return $o;
}	
?>