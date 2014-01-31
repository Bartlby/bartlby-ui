<?
// php automated.php \
//   	username=admin \
//   	password=password \
//   	script=SiteManager/cron.php 
//		var1=a //in $_GET
//   	Needs to be run as root
//Re-Do https://github.com/Bartlby/Bartlby/blob/master/multi_instances_HA/sync_bartlby_shm.sh as a PHP script
//cronjobs you require
//*/2 * * * * (cd /var/www/bartlby-ui/extensions/; php automated.php username=admin password=password script=SiteManager/cron.php sync=SHM)
//*/5 * * * * (cd /var/www/bartlby-ui/extensions/; php automated.php username=admin password=password script=SiteManager/cron.php sync=DB)
//*/10 * * * * (cd /var/www/bartlby-ui/extensions/; php automated.php username=admin password=password script=SiteManager/cron.php sync=GENCONF)
//*/10 * * * * (cd /var/www/bartlby-ui/extensions/; php automated.php username=admin password=password script=SiteManager/cron.php sync=FOLDERS)

/* DEFAULT PULL folders
/var/www/bartlby-ui/rights/:%UINODEPATH%/rights/
/var/www/bartlby-ui/store/:%UINODEPATH%/store/
/var/www/bartlby-ui/rrd/:%UINODEPATH%/rrd/
/opt/bartlby/var/log/:%CORENODEPATH%/log/

PUSH folders on RW node:
%UINODEPATH%/rights/:/var/www/bartlby-ui/rights/

*/




	ini_set('display_errors', '1');
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	include "config.php";
	include "layout.class.php";
	include "bartlby-ui.class.php";
	
	include "extensions/SiteManager/SiteManager.class.php";
	
	$btl=new BartlbyUi($Bartlby_CONF);
	$btl->hasRight("sitemanager");
	$sm = new SiteManager();
	
	$local_core_path=$sm->storage->load_key("local_core_path");
	$local_ui_path=$sm->storage->load_key("local_ui_path");
	$local_ui_replication_path=$sm->storage->load_key("local_ui_replication_path");
	$local_core_replication_path=$sm->storage->load_key("local_core_replication_path");
	

	$sync = $_GET[sync];
	if(!$sync) $sync="SHM";
	$r = $sm->db->query("select * from sm_remotes");
	foreach($r as $row) {
		$key_file=$row[ssh_key];
		$user=$row[ssh_username];
		$host=$row[ssh_ip];
		$ssh_cmd_str="-i " . $key_file . " " . $user .  "@" . $host;
		$randomness=sha1(microtime(true).mt_rand(10000,90000));
		$tmp_dir="/tmp/bartlby_sync." . $randomness;

		mkdir($tmp_dir);
		if(!is_dir($local_core_replication_path . "/" . $row[id])) {
			mkdir($local_core_replication_path . "/" . $row[id], 0777, true);
			
		}
		if(!is_dir($local_ui_replication_path . "/" . $row[id])) {
			mkdir($local_ui_replication_path . "/" . $row[id], 0777, true);
		}

		echo "Random: $randomness \n";
		if(!$key_file || !$user || !$host || !file_exists($key_file)) {
			echo "Error on Node => " . $row[remote_alias] . " SSH PARAMS skipping!\n";
			continue;
		}


		switch($sync) {
			case "GENCONF":
				//Does not matter if push or pull
				$local_shm_hex = runLocalCMD($local_core_path . "/bin/bartlby_shmt ftok " . $local_core_replication_path . "/" . $row[id]);
				$local_shm_size=runLocalCMD("ipcs -m|grep " .  $local_shm_hex . "|awk '{print \$5}'");
				
//Generates a CFG file required by bartlby-php
				$cfg_file = "
############ BARTLBY CONF
data_library=/opt/bartlby/lib/mysql.so
shm_key=" . $local_core_replication_path . "/" . $row[id] . "
shm_size=" . (floor($local_shm_size/1024/1024)*10) . "
max_load=10
logfile=" . $local_core_replication_path . "/" . $row[id] . "/log/bartlby
mysql_host=" . $row[local_db_host] . "
mysql_user=" .$row[local_db_user] . "
mysql_pw=" . $row[local_db_pass] . "
mysql_db=" . $row[local_db_name] . "
performance_dir=" . $local_core_path . "/perf/
basedir=" . $local_core_path  . "
rrd_web_path=nodes/" . $row[id] . "/rrd/
performance_rrd_htdocs=" . $local_ui_replication_path . "/" . $row[id] . "/rrd/
###########################################
				";
				//SAVE CFG
				file_put_contents($local_core_replication_path . "/" . $row[id] . "/bartlby.cfg", $cfg_file);
				$db_sync="false";
				if($row[mode] == "push") {
					$db_sync="true";
				}
				$ui_cfg  .= '
						$a[file] = "' . $local_core_replication_path . "/" . $row[id] . "/bartlby.cfg" .  '";
						$a[remote] = true;
						$a[db_sync] = ' . $db_sync . ';
						$a[display_name] = "' .  $row[remote_alias] . '";
						$a[uniq_id] = ' . $row[id] . ';		
						array_push($confs, $a);			
				';


				echo "bartlby.cfg  for Node $row[remote_alias]  generated\n";		
			break;
			case "DB":
				if($row[mode] == "pull") {
					//GET THE MYSQL DB from remote side		
					//check if DB exists localy
					$conn = mysql_connect($row[local_db_host], $row[local_db_user], $row[local_db_pass]);
					$ssh_conn=checkSSHConn($ssh_cmd_str);
					if($conn && $ssh_conn) {
						$r = mysql_select_db($row[local_db_name]);
						var_dump($r);
						if($r == false) {
							mysql_query("create database " . $row[local_db_name]);
							echo "create database " . $row[local_db_name];

						}
						//DUMP remote SITE
						runSSHCMD($ssh_cmd_str, "mkdir " . $tmp_dir);
						$d = runSSHCMD($ssh_cmd_str, "mysqldump -u " . $row[remote_db_user] . " --password='" . $row[remote_db_pass] . "' " . $row[remote_db_name] . " > " . $tmp_dir . "/mysql.dump; gzip " . $tmp_dir . "/mysql.dump");
						runLocalCMD("scp " . $ssh_cmd_str . ":" . $tmp_dir . "/mysql.dump.gz " . $tmp_dir . "/mysql.dump.gz; gunzip " . $tmp_dir . "/mysql.dump.gz");
						echo $tmp_dir . "/mysql.dump.gz";
						runLocalCMD("gunzip " . $tmp_dir . "/mysql.dump");
						runLocalCMD("mysql -u " . $row[local_db_user] . " --password='" . $row[local_db_pass] . "' " . $row[local_db_name] . " < " . $tmp_dir . "/mysql.dump");
						echo "mysql -u " . $row[local_db_user] . " --password='" . $row[local_db_pass] . "' " . $row[local_db_name] . " < " . $tmp_dir . "/mysql.dump";
						touch($local_ui_replication_path . "/" . $row[id] . "/last_sync_db");
					}	 else {
						echo "$row[remote_alias] ERROR on connecting to local DB host";
					}		
				} 
				if($row[mode] == "push") {
					//Check if exists
					// if is not existing pull once from remote!
					//push to remote side


					$conn = mysql_connect($row[local_db_host], $row[local_db_user], $row[local_db_pass]);
					$ssh_conn=checkSSHConn($ssh_cmd_str);
					if($conn && $ssh_conn) {
						$r = mysql_select_db($row[local_db_name]);
						var_dump($r);
						if($r == false) {
							mysql_query("create database " . $row[local_db_name]);
							echo "create database " . $row[local_db_name];
							//Make a new DB
							runLocalCMD("mysql -u " . $row[local_db_user] . " --password='" . $row[local_db_pass] . "' " . $row[local_db_name] . " < " . " " . $local_core_path . "/mysql.shema");

						}
						runSSHCMD($ssh_cmd_str, "mkdir " . $tmp_dir);
						

						//GET REMOTE DB
						
						//MERGE IT WITH LOCAL DB (for doUpdate calls)

						//Dump Local
						runLocalCMD("mysqldump -u " . $row[local_db_user] . " --password='" . $row[local_db_pass] . "' " . $row[local_db_name] . " > " . $tmp_dir . "/" . "mysql.dump; gzip " . $tmp_dir . "/mysql.dump");
						runLocalCMD("scp -i " . $key_file . " " . $tmp_dir . "/mysql.dump.gz " . $user . "@" . $host . ":" . $tmp_dir . "/mysql.dump.gz");

						runSSHCMD($ssh_cmd_str, $row[remote_core_path] . "/bin/bartlby_shmt " . $row[remote_core_path] . "/etc/bartlby.cfg " . " lock_for_db");
						runSSHCMD($ssh_cmd_str, "gunzip " . $tmp_dir . "/mysql.dump.gz; mysql  -u " . $row[remote_db_user] . " --password='" . $row[remote_db_pass] . "' " . $row[remote_db_name] . " < " . $tmp_dir . "/mysql.dump");
						runSSHCMD($ssh_cmd_str, $row[remote_core_path] . "/bin/bartlby_shmt " . $row[remote_core_path] . "/etc/bartlby.cfg " . " unlock_for_db");
						
						

						//SCP to remote
						// Schedule reload on remote side
						//DUMP remote SITE
						touch($local_ui_replication_path . "/" . $row[id] . "/last_sync_db");
					}	 else {
						echo "$row[remote_alias] ERROR on connecting to local DB host";
					}		



				}
			break;
			case "FOLDERS":
				//PULL fOLDERS
				$ssh_conn=checkSSHConn($ssh_cmd_str);
				if(!$ssh_conn) {
					echo "ERROR on $row[remote_alias] ssh does not work fix it!\n";
					continue;
				}
				$folder_str = $row[additional_folders_pull];
				$folder_str = str_replace("%UINODEPATH%", $local_ui_replication_path . "/" . $row[id] . "/", $folder_str);
				$folder_str = str_replace("%CORENODEPATH%", $local_core_replication_path . "/" . $row[id] . "/", $folder_str);
				$li = explode("\n", $folder_str);
				for($x=0; $x<count($li); $x++) {
					$ll = explode(":", $li[$x]);
						if(strlen($ll[0]) > 3 != "" && strlen($ll[1]) > 2) {
								$scm="rsync -e 'ssh -i " .  $key_file . "' -azv " . $user . "@" . $host . ":" . $ll[0] . " " . $ll[1];
								runLocalCMD($scm);

								//echo $scm . "\n";

						}
					
				}
				echo "DONE PULL FOLDERS FOR NODE: $row[remote_alias]\n";
				$folder_str = $row[additional_folders_push];
				$folder_str = str_replace("%UINODEPATH%", $local_ui_replication_path . "/" . $row[id] . "/", $folder_str);
				$folder_str = str_replace("%CORENODEPATH%", $local_core_replication_path . "/" . $row[id] . "/", $folder_str);
				$li = explode("\n", $folder_str);
				for($x=0; $x<count($li); $x++) {
					$ll = explode(":", $li[$x]);
						if(strlen($ll[0]) > 3 != "" && strlen($ll[1]) > 2) {
								$scm="rsync -e 'ssh -i " .  $key_file . "' -azv " . $ll[0] . " " . $user . "@" . $host . ":" . $ll[1];
								runLocalCMD($scm);


						}
					
				}
				echo "DONE PUSH FOLDERS FOR NODE: $row[remote_alias]\n";

			break;
			case "SHM":
					echo "Checking SHM Segment from $row[remote_alias]\n";
					if(checkSSHConn($ssh_cmd_str)) {
						//Get Expect Core Version
						//Get Arch
						$local_expectcore = runLocalCMD($local_core_path . "/bin/bartlby_shmt expectcore");
						$remote_expectcore = runSSHCMD($ssh_cmd_str, $row[remote_core_path] . "/bin/bartlby_shmt expectcore");

						if($row[mode] == "push") {
							//Writeback services so next mysql sync has new states
							$wb = runLocalCMD($local_core_path . "/bin/bartlby -w -d " . $local_core_replication_path . "/" . $row[id] . "/bartlby.cfg");
							

						}
						if($local_expectcore == $remote_expectcore)  {
							$local_arch= runLocalCMD("uname -m");
							$remote_arch = runSSHCMD($ssh_cmd_str, "uname -m");
							if($local_arch == $remote_arch) {
								//OK so pull the SHM segment from remote side
								runSSHCMD($ssh_cmd_str, "mkdir " . $tmp_dir);
								$d = runSSHCMD($ssh_cmd_str, $row[remote_core_path] . "/bin/bartlby_shmt dump " . $row[remote_core_path] . " " . $tmp_dir . "/shm.dump; gzip " . $tmp_dir . "/shm.dump");
								runLocalCMD("scp " . $ssh_cmd_str . ":" . $tmp_dir . "/shm.dump.gz " . $tmp_dir . "/shm.dump.gz; gunzip " . $tmp_dir . "/shm.dump.gz");
								$local_shm_hex = runLocalCMD($local_core_path . "/bin/bartlby_shmt ftok " . $local_core_replication_path . "/" . $row[id]);
								$local_shm_size=runLocalCMD("ipcs -m|grep " .  $local_shm_hex . "|awk '{print \$5}'");
								$local_shm_id=runLocalCMD("ipcs -m|grep " .  $local_shm_hex . "|awk '{print \$2}'");
								$si = filesize($tmp_dir . "/shm.dump");
								//echo "Node: $row[remote_alias] requires " . $si . " has " . $local_shm_size;
								$si += 0;
								if($si > $local_shm_size) {
									runLocalCMD("ipcrm -m " . $local_shm_id);
								}
								$r=runLocalCMD($local_core_path . "/bin/bartlby_shmt replay " . $local_core_replication_path . "/" . $row[id]  . " " . $tmp_dir . "/shm.dump " .  $si);
								echo $r;
								echo "SHM synced for Node $row[remote_alias] \n";
								$sm->db->exec("update sm_remotes set last_sync=datetime('now') where id=" . (int) $row[id]);
								touch($local_ui_replication_path . "/" . $row[id] . "/last_sync_shm");
							} else {
								echo "ERROR on $row[remote_alias] ARCH does not match $local_arch $remote_arch";	
							}
						} else {
							echo "ERROR on $row[remote_alias] expectcore not matching maybe you forgot to upgrade bartlby-core on remote node versions must match";
						}
						
					}  else {
						echo "ERROR on $row[remote_alias] ssh does not work fix it!";
						continue;
					}

			break;
			default:
				echo $sync . " mode unkown\n";
		}	
	}
	if($sync == "GENCONF") {
		echo "CREATED: " . $local_ui_replication_path . "/uinodes.php\n";
		file_put_contents($local_ui_replication_path . "/uinodes.php", "<?\n" . $ui_cfg . "\n?>");

	}

function runLocalCMD($str) {
	$rcmd = "$str  2>/dev/null";
	
	$fp = popen($rcmd, "r");
	while(!feof($fp)) {
		$s = fgets($fp);
		$s = rtrim($s, "\r\n");
		$rr .= $s;
	}
	pclose($fp);
	return $rr;

}
function runSSHCMD($str, $cmd) {
	$rcmd = "ssh $str -C '$cmd' 2>/dev/null";
	$fp = popen($rcmd, "r");
	while(!feof($fp)) {
		$s = fgets($fp);
		$s = rtrim($s, "\r\n");
		$rr .= $s;
	}
	pclose($fp);
	return $rr;

}
function checkSSHConn($str) {
	$rcmd = "ssh $str -C 'echo 1' 2>/dev/null";

	$fp = popen($rcmd, "r");
	while(!feof($fp)) {
		$s = fgets($fp);
		$s = rtrim($s, "\r\n");
		if($s == "1") {
			pclose($fp);
			return true;
		}
	}
	pclose($fp);
	return false;
}

?>
