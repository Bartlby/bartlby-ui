<?
/*
storage layout

array 
	0 => array(element_info)
	2 => array(element_info)


*/
	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/OcL/OcL.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("ocl_view");
	$ocl = new OcL();
	
	
	
	$layout= new Layout();
	$layout->setTitle("OcL: Schedule");
	$layout->SetMainTabName("Schedule");
	
	
	

	$layout->AddScript("<link href='extensions/OcL/ocl.css' rel='stylesheet' />");
	$layout->AddScript("<script src='extensions/OcL/ocl.js'></script>");
	

	$layout->OUT .= "<div id='ocl_schedule'></div>";
	
	

	$worker_drp = "<div style='width: 320px;'>Managed Workers: <select class='form-control' name='worker_id' id='worker_id'   data-rel='ocl_chosen' multiple>";
	$btl->worker_list_loop(function($wrk, $shm) use(&$worker_drp, &$ocl) {
		$sel="";

		if(@in_array($wrk[worker_id], $ocl->managed_users)) {
			$sel="selected";
		}
		$worker_drp .= "<option value=" . $wrk[worker_id] . " " . $sel .   ">" . $wrk[name] . "</option>"; 
	});
	$worker_drp .= "</select></div><div style='height:350px;'></div>";

	$cnt .= '<button  class="ocl_save_managed btn  btn-success">Save</button>';
	$cnt .= $worker_drp;


	$layout->Tab("Managed Worker", $cnt);


	$layout->display();
	
	
	
?>