<?
/*
minimal headless core config
############ BARTLBY CONF
#data_library=/opt/bartlby/lib/mysql.so
#max_concurent_checks=6
#max_load=0
#shm_key=/opt/bartlby-1
#shm_size=15
#logfile=/opt/bartlby-1/var/log/bartlby  
#### TRIGGERS FROM MASTER
#trigger_dir=/opt/bartlby/trigger
#agent_plugin_dir=/opt/bartlby-agent/plugins
#
#mysql_host=localhost
#mysql_user=root
#mysql_pw=XXXX
#mysql_db=bartlby_second
###########################################
*/
include "config.php";
include_once "bartlbystorage.class.php";
error_reporting(E_ERROR); 
ini_set('display_errors', 1);
class SiteManager {

	/*XAJAX functions */
	function sm_set_local_settings() {
			global $_GET;
			$re = new XajaxResponse();
			/*
			$_GET[xajaxargs][1] == UI PATH
			$_GET[xajaxargs][2] == CORE PATH
			*/
			$re->AddScript("sm_local_settings_update('" . $this->local_ui_path . "','" . $this->local_core_path .  "');");
			return $re;
	}
	function sm_save_node() {
		global $xajax;
		$res = new xajaxResponse();
		$values = $xajax->_xmlToArray("xjxquery", $_GET[xajaxargs][2]);
		$e=0;

		$sql = "INSERT INTO sm_remotes (remote_core_path, ssh_key, ssh_ip, ssh_username, remote_ui_path, remote_db_name, remote_db_pass, remote_db_host, local_db_name, local_db_user, local_db_pass, local_db_host, additional_folders_pull, additional_folders_push, mode, remote_alias) VALUES(";
		$sql .= "'" .  SQLite3::escapeString($values[remote_core_path]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[ssh_key]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[ssh_ip]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[ssh_username]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[remote_ui_path]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[remote_db_name]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[remote_db_pass]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[remote_db_host]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[local_db_name]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[local_db_user]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[local_db_pass]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[local_db_host]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[additional_folders_pull]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[additional_folders_push]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[mode]) . "',";
		$sql .= "'" .  SQLite3::escapeString($values[remote_alias]) . "')";
		$this->db->exec($sql);
		$res->AddScript('noty({"text":"[SITEMANAGER] Node saved! (' . $values[ssh_key] . ')","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
		return $res;
		//$values[ocl_date]
	}
	function sm_save_local_settings() {
			global $_GET;
			$re = new XajaxResponse();
			/*
			$_GET[xajaxargs][2] == UI PATH
			$_GET[xajaxargs][3] == CORE PATH
			*/

			$this->storage->save_key("local_ui_path", $_GET[xajaxargs][2]);
			$this->storage->save_key("local_core_path", $_GET[xajaxargs][3]);
			$re->AddScript('noty({"text":"[SITEMANAGER] Settings saved","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
			return $re;
	}
	function SiteManager() {
		$this->layout = new Layout();
		$this->storage = new BartlbyStorage("SiteManager");
		$this->DBSTR = "CREATE TABLE sm_remotes (id INTEGER PRIMARY  KEY, 
				remote_core_path TEXT,
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
				last_sync DATE,
				additional_folders_pull TEXT,
				additional_folders_push TEXT,
				mode TEXT,
				remote_alias TEXT
				);";
		$this->db = $this->storage->SQLDB($this->DBSTR);

		//Load Local Conf
		//local_core_path TEXT,
		//local_ui_path TEXT,		
		$this->local_core_path=$this->storage->load_key("local_core_path");
		$this->local_ui_path=$this->storage->load_key("local_ui_path");
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