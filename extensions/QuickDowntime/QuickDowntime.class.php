<?
include "config.php";
include_once "bartlbystorage.class.php";
error_reporting(E_ERROR); 
ini_set('display_errors', 1);
class QuickDowntime {

	function QuickDowntime() {
		

	}

	function _js_hook() {
		return '<script src="extensions/QuickDowntime/qd.js?v=1" type="text/javascript"></script>';
	}
	/* XAJAX FUNCTIONS*/
	function qd_add_downtime() {
		global $xajax;
		global $btl;
		$res = new xajaxResponse();
		$values = $xajax->_xmlToArray("xjxquery", $_GET[xajaxargs][2]);

		switch($values[qd_object_type]) {
			case BARTLBY_OBJECT_SERVICE:
				$dt_type=1;
			break;
			case BARTLBY_OBJECT_SERVER:

				$dt_type=2;
			break;
			case BARTLBY_OBJECT_SERVERGROUP:

				$dt_type=3;
			break;

			case BARTLBY_OBJECT_SERVICEGROUP:

				$dt_type=4;
			break;
		}


		$dt_obj=array(
				"downtime_from" => time(),
				"downtime_to" => time()+($values[qd_minutes]*60),
				"downtime_type" => (int)$dt_type,
				"downtime_notice" => "QD: " . $values[qd_notice],
				"downtime_service" => (int)$values[qd_object_id],
				"orch_id" => (int)$values[qd_orch_id]
			);

		bartlby_add_downtime($btl->RES, $dt_obj);
		if($values[qd_reload_after] == 1) {
			bartlby_reload($btl->RES);
		}
		$res->AddScript("qd_add_done();");
		return $res;

	}
	function qd_load_dialog() {
		global $btl;
		
		$re = new XajaxResponse();
		$layout = new Layout();
		$type = $_GET[xajaxargs][2];
		$id = $_GET[xajaxargs][3];

		$svc = bartlby_get_object_by_id($btl->RES, (int)$type, (int)$id);
		
		switch($type) {
			case BARTLBY_OBJECT_SERVICE:

				$lbl = "Service " . $svc[server_name] . "/" . $svc[service_name];
			break;
			case BARTLBY_OBJECT_SERVER:

				$lbl = "Server " . $svc[server_name] ;
			break;
			case BARTLBY_OBJECT_SERVERGROUP:

				$lbl = "ServerGroup " . $svc[servergroup_name] ;
			break;

			case BARTLBY_OBJECT_SERVICEGROUP:

				$lbl = "ServiceGroup " . $svc[servicegroup_name] ;
			break;
		}

		$r = '<form name=qd_form id=qd_form><span class=form-horizontal>';
		$r .= $lbl;
		$r .= $layout->FormBox(array(
					0=>"Notice",
					1=>$layout->Field("qd_notice", "text") . ""
				), true);

		$r .= $layout->FormBox(array(
					0=>"Time (in Minutes)",
					1=>$layout->Field("qd_minutes", "text") . "" . $layout->Field("qd_object_type", "hidden", $type)  . $layout->Field("qd_object_id", "hidden", $id) . $layout->Field("qd_orch_id", "hidden", $svc[orch_id])
				), true);

		$r .= $layout->FormBox(array(
					0=>"Reload after Save",
					1=>$layout->Field("qd_reload_after", "checkbox", "1", "" ,'class="icheck"')
			), true);

		$r .= "</span></form>";
		
		$re->AddAssign('qd_body', "innerHTML", $r); // Change to Manage Tab
		$re->AddScript("qd_load_done();");
		return $re;

	}
	function _servicegroupoptions($size) {
		global $btl;
		global $layout;
		

		$obj = $btl->_BTL["object"];
		$btn_size = $btl->_BTL["btn_size"];

		if($obj[is_downtime]) return "";
		
		return ' <span onClick="qd_show_dialog(' . BARTLBY_OBJECT_SERVICEGROUP . ',' . $obj[servicegroup_id] . ')" class="btn btn-primary ' . $btn_size . '"><i title="Add Downtime" class="fa fa-bomb "></i></span>';
	}
	function _servergroupoptions($size) {
		global $btl;
		global $layout;
		

		$obj = $btl->_BTL["object"];
		$btn_size = $btl->_BTL["btn_size"];

		if($obj[is_downtime]) return "";
			
		return ' <span onClick="qd_show_dialog(' . BARTLBY_OBJECT_SERVERGROUP . ',' . $obj[servergroup_id] . ')" class="btn btn-primary ' . $btn_size . '"><i title="Add Downtime" class="fa fa-bomb "></i></span>';
	}
	function _serviceoptions($size) {
		
		global $btl;
		global $layout;
		

		$obj = $btl->_BTL["object"];
		$btn_size = $btl->_BTL["btn_size"];

		if($obj[is_downtime]) return "";
		
		return ' <span onClick="qd_show_dialog(' . BARTLBY_OBJECT_SERVICE . ',' . $obj[service_id] . ')" class="btn btn-primary ' . $btn_size . '"><i title="Add Downtime" class="fa fa-bomb "></i></span>';
	}

	function _serveroptions($size) {
		global $btl;
		global $layout;
		

		$obj = $btl->_BTL["object"];
		$btn_size = $btl->_BTL["btn_size"];

		if($obj[is_downtime]) return "";
		
		return ' <span onClick="qd_show_dialog(' . BARTLBY_OBJECT_SERVER . ',' . $obj[server_id] . ')" class="btn btn-primary ' . $btn_size . '"><i title="Add Downtime" class="fa fa-bomb "></i></span>';
	}

}