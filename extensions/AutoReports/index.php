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
	$layout->setTitle("AutoReports - Main Settings");
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
	




	$layout->OUT .= "<span class=form-horizontal>";

	$layout->OUT .= $layout->FormBox(array(
			0=>" SMTP Host",
			1=> $layout->Field("local_smtp_host", "text")
			), true);

	$layout->OUT .= $layout->FormBox(array(
			0=>" Mail From Address",
			1=> $layout->Field("local_mail_from", "text") . "<input type=button value='Save' id=ar_save_local class='btn btn-success'>"
			), true);
	

	
	$layout->OUT .= "</span>";


	if($ar->db == false) $layout->OUT .= "<br>PHP SQLITE is not installed";
	$layout->boxes_placed[MAIN]=false;
	$layout->display();
	
	
function ar_local_box_render($file, $plcs = array()) {
	global $ar, $layout;
	$boxes_path="extensions/AutoReports/boxes/" . $file;
	ob_start();
	include($boxes_path);
	$o = ob_get_contents();	
	ob_end_clean();	
	return $o;
}	
?>