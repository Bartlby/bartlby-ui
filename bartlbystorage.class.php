<?php

	class bartlbyStorage {
		
		function bartlbyStorage($plugin_name) {
			global $Bartlby_CONF_IDX;
			$this->plugin_name=$plugin_name;
			$this->base_dir="store";
			if($Bartlby_CONF_IDX>0) {
				$this->base_dir = "store-" . $Bartlby_CONF_IDX;
			}
		
			$this->save_path=$this->base_dir . "/" . $this->plugin_name;
				
			if(!is_dir($this->save_path)) {
				if(!@mkdir($this->save_path, 0777, true)) {
					return false;
				}
			}
		}

		function SQLDB($cr) {
			$first_run=false;
			try {
				if(!file_exists($this->save_path . "/sql.sqlite." . md5($cr))) {
					$this->db = new PDO('sqlite:' . $this->save_path . "/sql.db." . md5($cr));	
					$tables = explode(";", $cr);
					for($x=0; $x<count($tables); $x++) {
						$e=$this->db->exec($tables[$x]);
						
					}
				} else {
					$this->db = new PDO('sqlite:' . $this->save_path . "/sql.db." . md5($cr));	
				}
			} catch(Exception $e) {
				
				return false;
			}

			return $this->db;
			
			
			
		}	
		function save_key($key, $value) {
			$sk = md5($key);
			$fp = @fopen($this->save_path . "/" . $sk, "w");
			
			if(!$fp) {
				return false;
			}
			fwrite($fp, $value);
			fclose($fp);
			return true;
			
		}
		function load_key($key) {
			$sk = md5($key);
			if(!file_exists($this->save_path . "/" . $sk)) {
				return false;
			}
			return file_get_contents($this->save_path . "/" . $sk);
			
		}
	}

?>