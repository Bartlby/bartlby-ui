<?
include "config.php";
include_once "bartlbystorage.class.php";
error_reporting(E_ERROR); 
ini_set('display_errors', 1);

class Audit {

	function Audit() {
		global $btl;
		$this->btl=$btl;
		$this->layout = new Layout();
		$this->storage = new BartlbyStorage("CORE-Audit");
		$this->DBSTR = "CREATE TABLE bartlby_object_audit (id INTEGER PRIMARY  KEY AUTOINCREMENT, 
				type INTEGER,
				action INTEGER,
				utime INTEGER,
				worker_id INTEGER,
				object_id INTEGER,
				prev_object TEXT
				);";

		$this->db = $this->storage->SQLDB($this->DBSTR, "core-audit_v4.db");
		echo $this->db->db_path;

		
	

	}
	function output_table($layout, $type, $id) {
		$info_box_title='Object Log';  
		$layout->create_box($info_box_title, $core_content, "object_audit", array(
											"type" => $type,
											"id" => $id
											)
											
		, "object_audit", false, false);

		return $layout->disp_box("object_audit");
	}
	function _serviceDetail() {
		global $layout;
		global $defaults;
		$layout->Tab("Audit", $this->output_table($layout,BARTLBY_AUDIT_TYPE_SERVICE, $defaults[service_id]));
		return "";
	}

	function _serverDetail() {
		global $layout;
		global $defaults;
		$layout->Tab("Audit", $this->output_table($layout,BARTLBY_AUDIT_TYPE_SERVER, $defaults[server_id]));
		return "";
	}

	function _servergroupDetails() {
		global $layout;
		global $defaults;
		$layout->Tab("Audit", $this->output_table($layout,BARTLBY_AUDIT_TYPE_SERVERGROUP, $defaults[servergroup_id]));
		return "";
	}
	function _servicegroupDetails() {
		global $layout;
		global $defaults;
		$layout->Tab("Audit", $this->output_table($layout,BARTLBY_AUDIT_TYPE_SERVICEGROUP, $defaults[servicegroup_id]));
		return "";
	}
	function _workerDetails() {
		global $layout;
		global $defaults;
		$layout->Tab("Audit", $this->output_table($layout,BARTLBY_AUDIT_TYPE_WORKER, $defaults[worker_id]));
		return "";
	}
}

?>