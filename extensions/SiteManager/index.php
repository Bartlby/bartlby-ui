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

	/* Add Extension JS */
	$layout->OUT .= '<script src="extensions/SiteManager/sm.js" type="text/javascript"></script>';

	$mgmt_content = local_box_render("sm_mgmt.php");
	$sm_form = local_box_render("sm_form.php");

	$layout->create_box("Manage", $mgmt_content, "sm_manage");
	$layout->create_box("Add/Modify", $sm_form, "sm_add");
	$layout->create_box("title", "Sync Content", "sm_sync");
	

	$layout->Tab("Manage", $layout->disp_box("sm_manage"));
	$layout->Tab("Add/Modify", $layout->disp_box("sm_add"));
	$layout->Tab("Sync", $layout->disp_box("sm_sync"));




	$layout->OUT .= "<b>Core Settings</b><br>";
	$layout->OUT .= "Local UI Path:<br>";
	$layout->OUT .= "<input type=text value='' id=local_ui_path>(e.g.:/var/www/bartlby-ui/)<br>";
	$layout->OUT .= "Local Core Path:<br>";
	$layout->OUT .= "<input type=text value='' id=local_core_path>(e.g.:/opt/bartlby/)<br>";


	
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