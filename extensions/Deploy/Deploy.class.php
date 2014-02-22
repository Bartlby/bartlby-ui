<?
include "config.php";

class Deploy {

	function dp_save_local_settings() {
			global $_GET;
			$re = new XajaxResponse();
			/*
			$_GET[xajaxargs][2] == UI PATH
			$_GET[xajaxargs][3] == CORE PATH
			*/

			$this->storage->save_key("agent_base_path", $_GET[xajaxargs][2]);
			$this->storage->save_key("plugin_base_path", $_GET[xajaxargs][3]);
			$this->storage->save_key("config_base_path", $_GET[xajaxargs][4]);
			$re->AddScript('noty({"text":"[Deploy] Settings saved","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
	
			return $re;
	}

	function dp_set_local_settings() {
			global $_GET;
			$re = new XajaxResponse();
			/*
			$_GET[xajaxargs][1] == UI PATH
			$_GET[xajaxargs][2] == CORE PATH
			*/
			$re->AddScript("dp_local_settings_update('" . $this->agent_base_path . "','" . $this->plugin_base_path .  "', '" . $this->config_base_path . "');");
			return $re;
	}


	function Deploy() {
		$this->layout = new Layout();
		$this->storage = new BartlbyStorage("Deploy");
		$this->DBSTR = "CREATE TABLE agent_deploy_log (id INTEGER PRIMARY  KEY AUTOINCREMENT, 
				deploy_last_sync DATETIME,
				deploy_desc TEXT,
				deploy_server_id INTEGER				
				);";
		$this->db_log = $this->storage->SQLDB($this->DBSTR, "deploy_log.db");
		$this->agent_base_path=$this->storage->load_key("agent_base_path");
		$this->plugin_base_path=$this->storage->load_key("plugin_base_path");
		$this->config_base_path=$this->storage->load_key("config_base_path");
		
		

	}

	function _Menu() {
		$r =  $this->layout->beginMenu();
		$r .= $this->layout->addRoot("Deploy");
		$r .= $this->layout->addSub("Deploy", "Manage","extensions_wrap.php?script=Deploy/index.php");
	
		$r .= $this->layout->endMenu();
		return $r;
	}
	function _About() {
		$snotice="Manage Client/Agent Deployment";
		return $snotice;
			
	}
}