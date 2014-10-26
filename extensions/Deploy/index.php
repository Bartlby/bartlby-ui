<?

	
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/Deploy/Deploy.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("Deploy");
	$dp = new Deploy();
	
	
	ini_set('display_errors', '1');
	error_reporting(E_ERROR);

	$layout= new Layout();
	$layout->setTitle("Deploy");
	$layout->set_menu("Deploy");
	$layout->setMainTabName("Master-Settings");


	$layout->Form("fm1", "");

	$layout->do_auto_reload=false;

	/* Add Extension JS */

	$layout->addScript('<script src="extensions/Deploy/dp.js" type="text/javascript"></script>');

	$layout->FormBox(array(
			0=>"Agent Binary Base Path",
			1=>$layout->Field("agent_binary_base_path", "text") . "(e.g.:" . getcwd()  . "/store/Deploy/data_store/agent_binary/) (arch will be added automatically)"
		));

	$layout->FormBox(array(
			0=>"Plugin Base Path",
			1=>$layout->Field("plugin_base_path", "text") . "(e.g.:" . getcwd()  . "/store/Deploy/data_store/agent_plugins/) (arch will be added automatically)"
		));
	
	$layout->FormBox(array(
			0=>"Agent Config Dir",
			1=>$layout->Field("config_base_path", "text") . "(e.g.:" . getcwd()  . "/store/Deploy/data_store/agent_config/) (arch will be added automatically)<br><br><input type=button value='Save' id=dp_save_local class='btn btn-success'>"
		));
	

	$archs=$dp->archs;

	$agent_bin_tab = "<table class='table table-bordered dataTable dp_table'>";
	$agent_bin_tab .= "<thead>";
	$agent_bin_tab .= "<tr>";
	$agent_bin_tab .= "<td>Architecture</td>";
	$agent_bin_tab .= "<td>Filename</td>";
	$agent_bin_tab .= "<td>SHA1</td>";
	$agent_bin_tab .= "</tr>";
	$agent_bin_tab .= "</thead><tbody>";
	for($x=0; $x<count($archs); $x++) {
		
		$dh = opendir($dp->agent_base_path . "/" . $archs[$x]);
		while($f = readdir($dh))  {
			
			if($f == "." || $f == ".." || $f == "1" || $f == "") continue;
			$sha1 = sha1_file($dp->agent_base_path . "/" . $archs[$x] . "/" . $f);
			$agent_bin_tab .= "<tr>";	
			$agent_bin_tab .= "<td><b>" .  $archs[$x] . "</b></td>";
			$agent_bin_tab .= "<td>" . $f . "</td>";
			$agent_bin_tab .= "<td>" . $sha1 . "</td>";
			$agent_bin_tab .= "</tr>";
		}
		closedir($dh);
	}
	$agent_bin_tab .= "</tbody></table>";


	$plugin_bin_tab = "<table class='table table-bordered dataTable dp_table'>";
	$plugin_bin_tab .= "<thead>";
	$plugin_bin_tab .= "<tr>";
	$plugin_bin_tab .= "<td>Architecture</td>";
	$plugin_bin_tab .= "<td>Filename</td>";
	$plugin_bin_tab .= "<td>SHA1</td>";
	$plugin_bin_tab .= "</tr>";
	$plugin_bin_tab .= "</thead><tbody>";


	for($x=0; $x<count($archs); $x++) {
		$dh = opendir($dp->plugin_base_path . "/" . $archs[$x]);
		while($f = readdir($dh))  {
			
			if($f == "." || $f == ".." || $f == "1" || $f == "") continue;
			$sha1 = sha1_file($dp->plugin_base_path . "/" . $archs[$x] . "/" . $f);
			$plugin_bin_tab .= "<tr>";	
			$plugin_bin_tab .= "<td><b>" .  $archs[$x] . "</b></td>";
			$plugin_bin_tab .= "<td>" . $f . "</td>";
			$plugin_bin_tab .= "<td>" . $sha1 . "</td>";
			$plugin_bin_tab .= "</tr>";
		}
		closedir($dh);
	}

	$plugin_bin_tab .= "</tbody></table>";


	$config_tab = "<table class='table table-bordered dataTable dp_table'>";
	$config_tab .= "<thead>";
	$config_tab .= "<tr>";
	$config_tab .= "<td>Architecture</td>";
	$config_tab .= "<td>Filename</td>";
	
	$config_tab .= "<td>SHA1</td>";
	$config_tab .= "</tr>";
	$config_tab .= "</thead><tbody>";

	for($x=0; $x<count($archs); $x++) {
		$dh = opendir($dp->config_base_path . "/" . $archs[$x]);
		while($f = readdir($dh))  {
			
			if($f == "." || $f == ".." || $f == "1" || $f == "") continue;
			$sha1 = sha1_file($dp->config_base_path . "/" . $archs[$x] . "/" . $f);
			$config_tab .= "<tr>";	
			$config_tab .= "<td><b>" .  $archs[$x] . "</b></td>";
			$config_tab .= "<td>" . $f . "</td>";
			$config_tab .= "<td>" . $sha1 . "</td>";
			$config_tab .= "</tr>";
		}
		closedir($dh);
	}


	$config_tab .= "</tbody></table>";





	$sync_tab = "<table class='table  table-bordered datatable'>";
	$sync_tab .= "<thead>";
	$sync_tab .= "<tr>";
	$sync_tab .= "<td>Server</td>";
	$sync_tab .= "<td>Last-Sync</td>";
	$sync_tab .= "</tr>";
	$sync_tab .= "</thead><tbody>";

	$sql = "select * from agent_deploy_log";
	$r = $dp->db_log->query($sql);
	foreach($r as $row) {		
		$srv = bartlby_get_server_by_id($btl->RES, $row[deploy_server_id]);
		$sync_tab .= "<tr>";	
		$sync_tab .= "<td><b>" .  $srv[server_name] . "</b></td>";
		$sync_tab .= "<td>" . $row[deploy_last_sync] . "</td>";
		
		$sync_tab .= "</tr>";
	}

	$sync_tab .= "</tbody></table>";




	$layout->Tab("Synchronization State", $sync_tab, "dp_sync", true);

	$layout->Tab("Agent Binarys", $agent_bin_tab, "dp_binarys", true);
	$layout->Tab("Plugin Binarys", $plugin_bin_tab, "dp_plugins", true);

	$layout->Tab("Config Files", $config_tab, "dp_config", true);

	$layout->boxes_placed[MAIN]=false;

	$layout->FormEnd();

	$layout->display();
	
	

?>
