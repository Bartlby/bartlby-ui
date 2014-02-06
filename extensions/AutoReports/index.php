<?

	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/AutoReports/AutoReports.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("autoreports");
	$ar = new AutoReports();
	
	
	ini_set('display_errors', '1');
	error_reporting(E_ERROR);

	$layout= new Layout();
	$layout->setTitle("AutoReports");
	$layout->set_menu("AutoReports");
	$layout->setMainTabName("Master-Settings");
$layout->do_auto_reload=false;

	/* Add Extension JS */
	$layout->OUT .= '<script src="extensions/AutoReports/ar.js" type="text/javascript"></script>';

	$mgmt_content = ar_local_box_render("ar_mgmt.php");
	$ar_form = ar_local_box_render("ar_form.php");

	$layout->create_box("Manage", $mgmt_content, "ar_manage");
	$layout->create_box("Add/Modify", $ar_form, "ar_add");
	
	

	$layout->Tab("Manage", $layout->disp_box("ar_manage"), "ar_manage");
	$layout->Tab("Add/Modify", $layout->disp_box("ar_add"), "ar_add");
	




	$layout->OUT .= "<b>Mail Settings</b><br>";
	$layout->OUT .= "SMTP-Host:<br>";
	$layout->OUT .= "<input type=text value='' id=local_smtp_host>(e.g.:localhost)<br>";
	$layout->OUT .= "Mail From address:<br>";
	$layout->OUT .= "<input type=text value='' id=local_mail_from><br>";


	
	$layout->OUT .= "<input type=button value='Save' id=ar_save_local><br>";


	if($ar->db == false) $layout->OUT .= "<br>PHP SQLITE is not installed";
	$layout->boxes_placed[MAIN]=false;
	$layout->display();
	
	
function ar_local_box_render($file, $plcs = array()) {
	global $ar;
	$boxes_path="extensions/AutoReports/boxes/" . $file;
	ob_start();
	include($boxes_path);
	$o = ob_get_contents();	
	ob_end_clean();	
	return $o;
}	
?>