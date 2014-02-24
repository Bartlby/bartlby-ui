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

	$layout->do_auto_reload=false;

	/* Add Extension JS */

	$layout->addScript('<script src="extensions/Deploy/dp.js" type="text/javascript"></script>');

	$layout->OUT .= "<b>Deployment Settings</b><br>";
	$layout->OUT .= "Agent Binary Base Path:<br>";
	$layout->OUT .= "<input type=text value='' id=agent_binary_base_path>(e.g.:" . getcwd()  . "/store/Deploy/data_store/agent_binary/) (arch will be added automatically)<br>";
	

	$layout->OUT .= "Plugin Base Path:<br>";
	$layout->OUT .= "<input type=text value='' id=plugin_base_path>(e.g.:" . getcwd()  . "/store/Deploy/data_store/agent_plugins/) (arch will be added automatically)<br>";
	
	$layout->OUT .= "Agent Config Dir:<br>";
	$layout->OUT .= "<input type=text value='' id=config_base_path>(e.g.:" . getcwd()  . "/store/Deploy/data_store/agent_config/) (arch will be added automatically)<br>";
	
	$layout->OUT .= "<input type=button value='Save' id=dp_save_local><br>";

	$archs=$dp->archs;

	$agent_bin_tab = "<table class='table table-striped table-bordered dataTable dp_table'>";
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


	$plugin_bin_tab = "<table class='table table-striped table-bordered dataTable dp_table'>";
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


	$config_tab = "<table class='table table-striped table-bordered dataTable dp_table'>";
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



	$layout->Tab("Agent Binarys", $agent_bin_tab);
	$layout->Tab("Plugin Binarys", $plugin_bin_tab);

	$layout->Tab("Config Files", $config_tab);

	$layout->boxes_placed[MAIN]=false;
	$layout->display();
	
	

?>