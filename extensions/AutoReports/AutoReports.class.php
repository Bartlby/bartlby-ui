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
class AutoReports {

	/*XAJAX functions */
	function ar_delete_node() {
		global $_GET;
		$re = new XajaxResponse();
		$id = $_GET[xajaxargs][2];
		$sql = "delete from autoreports where id=" . (int)$id;
		$this->db->exec($sql);

		$re->AddScript('noty({"text":"[AutoReports] Node deleted! (' . $values[ssh_key] . ')","timeout": 600, "layout":"center","type":"warning","animateOpen": {"opacity": "show"}})'); //Notify User
		$re->AddScript('btl_force_reload_ui();'); // Force Reload
		$re->AddScript('ar_show_tab("ar_manage");'); // Change to Manage Tab
		
		return $re;
	}
	function ar_set_form_fields($row) {
		$re = new XajaxResponse();
		//$re->AddScript("alert('" . $row[receipient] . "')");
		$re->AddScript('$("#receipient").val("' . $row[receipient] . '"); ');
		$re->AddScript('$("#service_var").val("' . $row[service_var] . '")');
		$re->AddScript('$("#alias").val("' . $row[alias] . '")');
		
		if($row[daily] == 1) $re->AddScript('$("#daily").iCheck("check")');
		if($row[weekly] == 1) $re->AddScript('$("#weekly").iCheck("check")');
		if($row[monthly] == 1)  $re->AddScript('$("#monthly").iCheck("check")');

		if($row[daily] == 0) $re->AddScript('$("#daily").iCheck("uncheck")');
		if($row[weekly] == 0) $re->AddScript('$("#weekly").iCheck("uncheck")');
		if($row[monthly] == 0)  $re->AddScript('$("#monthly").iCheck("uncheck")');

		return $re;
	}
	function ar_load_form() {
		global $_GET;
		$id = $_GET[xajaxargs][2];

		$sql = "select * from autoreports where id=" . (int)$id;
		$r = $this->db->query($sql);
		foreach($r as $row) {
			$re=$this->ar_set_form_fields($row);
		}
		if($re == "") {
			$re=$this->ar_set_form_fields();
		}
		$re->AddScript("ar_show_tab('ar_add')");
		$re->AddScript("ar_unlock_form()");
		return $re;
	}
	function ar_set_local_settings() {
			global $_GET;
			$re = new XajaxResponse();
			/*
			$_GET[xajaxargs][1] == UI PATH
			$_GET[xajaxargs][2] == CORE PATH
			*/
			$re->AddScript("ar_local_settings_update('" . $this->local_smtp_host . "','" . $this->local_mail_from .  "');");
			return $re;
	}
	function ar_save_node() {
		global $xajax;
		$res = new xajaxResponse();
		$values = $xajax->_xmlToArray("xjxquery", $_GET[xajaxargs][2]);
		$e=0;

		if($values[ar_edit_node_id] == "") {
			$sql = "INSERT INTO autoreports (receipient, service_var, daily, weekly, monthly, alias) VALUES(";
			$sql .= "'" .  SQLite3::escapeString($values[receipient]) . "',";
			$sql .= "'" .  SQLite3::escapeString($values[service_var]) . "',";
			$sql .= "'" .  SQLite3::escapeString($values[daily]) . "',";
			$sql .= "'" .  SQLite3::escapeString($values[weekly]) . "',";
			$sql .= "'" .  SQLite3::escapeString($values[monthly]) . "',";
			$sql .= "'" .  SQLite3::escapeString($values[alias]) . "')";
		} else {
			$sql = "UPDATE autoreports set ";
			$sql .= "receipient='" . SQLite3::escapeString($values[receipient]) . "',";
			$sql .= "service_var='" . SQLite3::escapeString($values[service_var]) . "',";
			$sql .= "daily='" . SQLite3::escapeString($values[daily]) . "',";
			$sql .= "weekly='" . SQLite3::escapeString($values[weekly]) . "',";
			$sql .= "monthly='" . SQLite3::escapeString($values[monthly]) . "' ";
			$sql .= "alias='" . SQLite3::escapeString($values[alias]) . "' ";
			$sql .= " where id=" . (int)$values[ar_edit_node_id];
				
		}
		$this->db->exec($sql);
		$res->AddScript('noty({"text":"[AutoReports] Report saved!","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})'); //Notify User
		$res->AddScript('btl_force_reload_ui();'); // Force Reload
		$res->AddScript('ar_show_tab("ar_manage");'); // Change to Manage Tab
		return $res;
		//$values[ocl_date]
	}
	function ar_save_local_settings() {
			global $_GET;
			$re = new XajaxResponse();
			/*
			$_GET[xajaxargs][2] == UI PATH
			$_GET[xajaxargs][3] == CORE PATH
			*/

			$this->storage->save_key("local_smtp_host", $_GET[xajaxargs][2]);
			$this->storage->save_key("local_mail_from", $_GET[xajaxargs][3]);
			$re->AddScript('noty({"text":"[AutoReports] Settings saved","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
			return $re;
	}
	function AutoReports() {
		global $btl;
		$this->btl=$btl;
		$this->layout = new Layout();
		$this->storage = new BartlbyStorage("AutoReports");
		$this->DBSTR = "CREATE TABLE autoreports (id INTEGER PRIMARY  KEY AUTOINCREMENT, 
				receipient TEXT,
				service_var TEXT,
				daily INTEGER DEFAULT 0, 
				weekly INTEGER DEFAULT 0, 
				monthly INTEGER DEFAULT 0, 
				last_send TEXT				
				);";
		$this->db = $this->storage->SQLDB($this->DBSTR);
		$this->storage->db_has_field("alias","autoreports", "alter table autoreports add alias text default 'not-set'");
	

		//Load Local Conf
		//local_core_path TEXT,
		//local_ui_path TEXT,		
		$this->local_smtp_host=$this->storage->load_key("local_smtp_host");
		$this->local_mail_from=$this->storage->load_key("local_mail_from");
	}
	function _Menu() {
		$r =  $this->layout->beginMenu();
		$r .= $this->layout->addRoot("Auto Reports", "fa fa-repeat");
		$r .= $this->layout->addSub("Auto Reports", "Manage","extensions_wrap.php?script=AutoReports/index.php");
		$r .= $this->layout->endMenu();
		return $r;
	}

}

?>
