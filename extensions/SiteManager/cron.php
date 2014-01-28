<?
// php automated.php \
//   	username=admin \
//   	password=password \
//   	script=SiteManager/cron.php 
//		var1=a //in $_GET
//   	Needs to be run as root

	ini_set('display_errors', '1');
	error_reporting(E_ERROR);
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/SiteManager/SiteManager.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("sitemanager");
	$sm = new SiteManager();
	
	
	

	$sync = $_GET[sync];
	if(!$sync) $sync="SHM";
	$r = $sm->db->query("select * from sm_remotes");
	foreach($r as $row) {
		switch($sync) {
			case "SHM":
				switch($row[mode]) {
					case 'pull':
						//PULL SHM from remote
						//Check for /node folders
						//Check for Key
						//Dump SHM on remote side
						//import SHM on local side
						echo "DO SHM MAGIC\n";
					break;
					default;
						echo $row[mode] . " unkown\n";
				}

			break;
			default:
				echo $sync . " mode unkown\n";
		}	
	}

?>