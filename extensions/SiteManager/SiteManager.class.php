<?
include "config.php";
include_once "bartlbystorage.class.php";
error_reporting(E_ERROR); 
ini_set('display_errors', 1);
class SiteManager {
	function SiteManager() {
		$this->layout = new Layout();
		$this->storage = new BartlbyStorage("SiteManager");
		$this->DBSTR = "CREATE TABLE sm_remotes (id INTEGER PRIMARY  KEY, 
				remote_core_path TEXT,
				local_core_path TEXT,
				ssh_key TEXT,
				ssh_ip TEXT, 
				ssh_username TEXT,
				remote_ui_path TEXT,
				remote_db_name TEXT,
				remote_db_user TEXT,
				remote_db_pass TEXT, 
				remote_db_host TEXT,
				local_db_name TEXT,
				local_db_user TEXT,
				local_db_pass TEXT, 
				local_db_host TEXT,
				local_ui_path TEXT,
				last_sync DATE,
				mode INTEGER
				)";
		$this->db = $this->storage->SQLDB($this->DBSTR);
	}
	function _Menu() {
		$r =  $this->layout->beginMenu();
		$r .= $this->layout->addRoot("Sites");
		$r .= $this->layout->addSub("Sites", "Manage","extensions_wrap.php?script=SiteManager/index.php");
		
		
		
		$r .= $this->layout->endMenu();
		return $r;
	}

}

?>