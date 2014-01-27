<?

	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/SiteManager/SiteManager.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("sitemanager");
	$sm = new SiteManager();
	
	
	
	$layout= new Layout();
	$layout->setTitle("SiteManager");
	$layout->set_menu("SiteManager");
	$layout->setMainTabName("SiteManager");

	$layout->create_box("title", "Add Mod Form", "sm_add");
	$layout->create_box("title", "Sync Content", "sm_sync");
	

	$layout->Tab("Add/Modify", $layout->disp_box("sm_add"));
	$layout->Tab("Sync", $layout->disp_box("sm_sync"));

	$layout->OUT = "Remote List";
	if($sm->db == false) $layout->OUT .= "<br>PHP SQLITE is not installed";
	$layout->boxes_placed[MAIN]=false;
	$layout->display();
	
	
	
?>