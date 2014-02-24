<?

	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/Deploy/Deploy.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("Deploy");
	$dp = new Deploy();

	//$_GET[arch]
	switch($_GET[mode]) {
		case 'agent-list':
			$d = opendir($dp->agent_base_path . "/" . $_GET[arch]);
			while($f = readdir($d)) {
				if($f == "." || $f == ".." || $f == "1") continue;
				$sha1 = sha1_file($dp->agent_base_path . "/" . $_GET[arch] . "/" . $f);
				echo $f . "\t" . $sha1 . "\t" . $_GET[arch] . "\n";
			}
			closedir($d);



		break;
		case "get-agent-pull":
			$fn = "extensions/Deploy/agent_pull.sh";
			echo file_get_contents($fn);
		break;
		case "get-agent-pull-list":
			$fn = "extensions/Deploy/agent_pull.sh";
			echo "agent_pull.sh\t" . sha1_file($fn) . "\tall\n";
		break;
		case "get-agent-bin":
			$cnt = file_get_contents($dp->agent_base_path . "/" . $_GET[arch] . "/" . $_GET[fn]);
			echo $cnt;
		break;
		case 'agentcfg-list':
			$d = opendir($dp->config_base_path . "/" . $_GET[arch]);
			while($f = readdir($d)) {
				if($f == "." || $f == ".." || $f == "1") continue;
				$sha1 = sha1_file($dp->config_base_path . "/" . $_GET[arch] . "/" . $f);
				echo $f . "\t" . $sha1 . "\t" . $_GET[arch] . "\n";
			}
			closedir($d);



		break;
		case "get-agent-cfg":
			$cnt = file_get_contents($dp->config_base_path . "/" . $_GET[arch] . "/" . $_GET[fn]);
			echo $cnt;
		break;

		case "plugin-list":
			$btl->service_list_loop(function($svc, $shm) use(&$_GET, &$dp) {
				if($svc[server_id] == $_GET[server_id]) {
					//Check if plugin exists in arch folder otherwise check in "all"
					if(file_exists($dp->plugin_base_path . "/" . $_GET[arch] . "/" . $svc[plugin])) {
						$plg_file=$dp->plugin_base_path . "/" . $_GET[arch] . "/" . $svc[plugin];
						$arch_used=$_GET[arch];
					} else {
						$plg_file=$dp->plugin_base_path . "/all/" . $svc[plugin];
						$arch_used="all";
					}
					$sha = sha1_file($plg_file);
					echo $svc[plugin] . "\t" . $sha . "\t" . $arch_used . "\n";
				}
				
			});
			#required for being able to use bartlby plugins!!
			$sha = sha1_file($dp->plugin_base_path . "/all/bartlby.funcs");
			echo "bartlby.funcs\t" . $sha . "\tall\n";
		break;
		case "get-plugin":
			$plg_file=$dp->plugin_base_path . "/" .  $_GET[arch] . "/" . $_GET[plugin];
			echo file_get_contents($plg_file);
		break;
		case 'update-sync-time':
			//Check if we already have a timestamp
			$sql = "select * from agent_deploy_log where deploy_server_id=" . $_GET[server_id];
			$r = $dp->db_log->query($sql);
			foreach($r as $row) {
				$sql = "update agent_deploy_log set deploy_last_sync=datetime('now') where server_id=" . $_GET[server_id];
				$dp->db_log->exec($sql);

				exit;
			}
			$sql = "insert into agent_deploy_log (deploy_server_id, deploy_last_sync) values(" . $_GET[server_id] . ", datetime('now'))";
			$dp->db_log->exec($sql);
			
			exit;
		break;

	}

?>