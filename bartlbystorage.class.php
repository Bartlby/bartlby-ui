<?php

	class bartlbyStorage {
		
		function bartlbyStorage($plugin_name) {
			global $Bartlby_CONF_IDX;
			$this->plugin_name=$plugin_name;
			$this->base_dir="store";
			if($Bartlby_CONF_IDX>0) {
				$this->base_dir = "nodes/" . $Bartlby_CONF_IDX . "/store/";
			}
		
			$this->save_path=$this->base_dir . "/" . $this->plugin_name;
				
			if(!is_dir($this->save_path)) {
				if(!@mkdir($this->save_path, 0777, true)) {
					return false;
				}
			}
		}
		function db_has_field($f,$table,  $create) {
			if(!$this->db) return;
			$r=$this->db->exec("select " . $f  . " from " . $table);
			if($r == false) {
				$this->db->exec($create);
			}
		}

		function SQLDB($cr, $fixed_name="") {
			$first_run=false;
			try {
				$fna = "sql.db." . md5($cr);
				if($fixed_name != "") {
					$fna = $fixed_name;
				}
				if(!file_exists($this->save_path . "/" . $fna)) {
					$this->db = new PDO('sqlite:' . $this->save_path . "/" . $fna);	
					$tables = explode(";", $cr);
					for($x=0; $x<count($tables); $x++) {
						$e=$this->db->exec($tables[$x]);
						
					}
				} else {
					$this->db = new PDO('sqlite:' . $this->save_path . "/" . $fna);	
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