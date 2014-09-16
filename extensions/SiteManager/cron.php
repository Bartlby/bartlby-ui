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
//Removes demoted nodes
//0 0 * * * (cd /var/www/bartlby-ui/extensions/; php automated.php username=admin password=password script=SiteManager/cron.php sync=CLEANUP)

/* DEFAULT PULL folders
/var/www/bartlby-ui/rights/:%UINODEPATH%/rights/
/var/www/bartlby-ui/store/:%UINODEPATH%/store/
/var/www/bartlby-ui/rrd/:%UINODEPATH%/rrd/
/opt/bartlby/var/log/:%CORENODEPATH%/log/

PUSH folders on RW node:
%UINODEPATH%/rights/:/var/www/bartlby-ui/rights/

*/


$c = new Color();

$_GLO[debug_commands]=true;

// highlight('green') === bg('green') === bg_green()
// white() === fg('white')
	ini_set('implicit_flush', '1');
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
	$r = $sm->db->query("select * from sm_remotes where sync_active = 1");
	
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

		echo $c("Random: $randomness")->white()->bold() . PHP_EOL;
		if(!$key_file || !$user || !$host || !file_exists($key_file)) {
			setLastOutput($row[id], "SSH PARAMS ERROR");
			echo $c("Error on Node => " . $row[remote_alias] . " SSH PARAMS skipping!\n")->red()->bold() . PHP_EOL;
			continue;
		}


		switch($sync) {
			case "GENCONF":
				//Does not matter if push or pull
				if($row[mode] == "pull" || $row[mode] == "push") {
					$local_shm_hex = runLocalCMD($local_core_path . "/bin/bartlby_shmt ftok " . $local_core_replication_path . "/" . $row[id]);
					$local_shm_size=runLocalCMD("ipcs -m|grep " .  $local_shm_hex . "|awk '{print \$5}'");
					$local_megs=(floor($local_shm_size/1024/1024)*10);
				}
				
				if($row[mode] == "arch-ind-pull") $local_megs=30;
				
//Generates a CFG file required by bartlby-php
				$cfg_file = "
############ BARTLBY CONF
data_library=/opt/bartlby/lib/mysql.so
shm_key=" . $local_core_replication_path . "/" . $row[id] . "
shm_size=" . $local_megs . "
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


				if($row[mode] != "orch-node") {
					$ui_cfg  .= '
							$a[file] = "' . $local_core_replication_path . "/" . $row[id] . "/bartlby.cfg" .  '";
							$a[remote] = true;
							$a[db_sync] = ' . $db_sync . ';
							$a[display_name] = "' .  $row[remote_alias] . '";
							$a[uniq_id] = ' . $row[id] . ';		
							array_push($confs, $a);			
					';
				} else {
					$ui_cfg .= '$_BARTLBY[orch_nodes][]=array(orch_id=>' . $row[id] . ', orch_alias=>"' . $row[remote_alias] . '");';
					
				}

				echo $c("bartlby.cfg  for Node $row[remote_alias]  generated\n")->green;		
			break;
			case "CLEANUP":
				$configured_node_ids[] = $row[id];
			break;
			case "DB":
				if($row[mode] == "pull" || $row[mode] == "arch-ind-pull") {

					//GET THE MYSQL DB from remote side		
					//check if DB exists localy
					$conn = mysql_connect($row[local_db_host], $row[local_db_user], $row[local_db_pass]);
					$ssh_conn=checkSSHConn($ssh_cmd_str);
					if($conn && $ssh_conn) {
						$r = mysql_select_db($row[local_db_name]);
						
						if($r == false) {
							mysql_query("create database " . $row[local_db_name]);
							echo $c("create database " . $row[local_db_name] . PHP_EOL)->green;

						}
						//RELOAD
						if($row[reload_before_db_sync] == 1) {
							runSSHCMD($ssh_cmd_str, $row[remote_core_path] . "/bin/bartlby_shmt " . $row[remote_core_path] . "/etc/bartlby.cfg " . " lock_for_db");
							runSSHCMD($ssh_cmd_str, $row[remote_core_path] . "/bin/bartlby_shmt " . $row[remote_core_path] . "/etc/bartlby.cfg " . " unlock_for_db");
						}
						//DUMP remote SITE
						runSSHCMD($ssh_cmd_str, "mkdir " . $tmp_dir);
						$d = runSSHCMD($ssh_cmd_str, "mysqldump -u " . $row[remote_db_user] . " --password='" . $row[remote_db_pass] . "' " . $row[remote_db_name] . " > " . $tmp_dir . "/mysql.dump; gzip " . $tmp_dir . "/mysql.dump");
						runLocalCMD("scp " . $ssh_cmd_str . ":" . $tmp_dir . "/mysql.dump.gz " . $tmp_dir . "/mysql.dump.gz; gunzip " . $tmp_dir . "/mysql.dump.gz");
						
						runLocalCMD("gunzip " . $tmp_dir . "/mysql.dump");
						runLocalCMD("mysql -u " . $row[local_db_user] . " --password='" . $row[local_db_pass] . "' " . $row[local_db_name] . " < " . $tmp_dir . "/mysql.dump");

						touch($local_ui_replication_path . "/" . $row[id] . "/last_sync_db");
					}	 else {
						setLastOutput($row[id], "SSH OR DB ERROR");
						echo $c("$row[remote_alias] ERROR on connecting to local DB host 1" . PHP_EOL)->red->bold;
						continue 2;
					}
					if($row[mode] == "arch-ind-pull") {
						//Populate SHM
						$r = runLocalCMD($local_core_path . "/bin/bartlby -d -s -r " . $local_core_replication_path . "/" . $row[id] . "/bartlby.cfg");		
						touch($local_ui_replication_path . "/" . $row[id] . "/last_sync_shm");			
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
						
						if($r == false) {
							mysql_query("create database " . $row[local_db_name]);
							
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
						setLastOutput($row[id], "SSH or DB ERROR");
						echo $c("$row[remote_alias] ERROR on connecting to local DB host" . PHP_EOL)->red->bold;
						continue 2;
					}		



				}
			break;
			case "FOLDERS":
				//PULL fOLDERS
				$ssh_conn=checkSSHConn($ssh_cmd_str);
				if(!$ssh_conn) {
					echo $c("ERROR on $row[remote_alias] ssh does not work fix it!\n")->red->bold;
					continue 2;
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
								
								flush();
								//echo $scm . "\n";

						}
					
				}
				echo $c("DONE PULL FOLDERS FOR NODE: $row[remote_alias]\n")->green->bold;
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
				echo $c("DONE PUSH FOLDERS FOR NODE: $row[remote_alias]\n")->green->bold;

			break;
			case "SHM":

					if($row[mode] == "push" || $row[mode] == "pull") {
						echo $c("Checking SHM Segment from $row[remote_alias]\n")->white->bold;
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
									
									echo $c("SHM synced for Node $row[remote_alias] \n")->green->bold;
									
									touch($local_ui_replication_path . "/" . $row[id] . "/last_sync_shm");
								} else {
									setLastOutput($row[id], "ARCH missmatch");
									echo $c("ERROR on $row[remote_alias] ARCH does not match $local_arch $remote_arch" . PHP_EOL)->red->bold;	
									continue 2;
								}
							} else {
								setLastOutput($row[id], "EXPECTCORE MISSMATCH");
								echo $c("ERROR on $row[remote_alias] expectcore not matching maybe you forgot to upgrade bartlby-core on remote node versions must match" . PHP_EOL)->red->bold;
								continue 2;
							}
							
						}  else {
							setLastOutput($row[id], "SSH ERROR");
							echo $c("ERROR on $row[remote_alias] ssh does not work fix it!" . PHP_EOL)->red->bold;
							continue 2;
						}
				} else {
					continue 2;
				}

			break;
			default:
				setLastOutput($row[id], "SYNC MODE UNKOWN");
				echo $c($sync . " mode unkown\n")->grey->bold;
				continue 2;
			}
			echo $c("Done Node: " . $row[remote_alias] . PHP_EOL)->green->bold;
			runLocalCMD("rm -vfr /tmp/bartlby_sync." . $randomness);
			runSSHCMD($ssh_cmd_str, "rm -vfr /tmp/bartlby_sync." . $randomness);
			$q = "update sm_remotes set last_sync=datetime('now'), last_output='OK' where id="  . $row[id];
			echo $q . PHP_EOL;
			$sm->db->exec($q);
	}
	if($sync == "GENCONF") {
		echo $c("CREATED: " . $local_ui_replication_path . "/uinodes.php\n")->green->bold;
		file_put_contents($local_ui_replication_path . "/uinodes.php", "<?\n" . $ui_cfg . "\n?>");

	}
	if($sync == "CLEANUP") {
		$d = opendir($local_core_replication_path);
		while($f = readdir($d)) {
			if(is_dir($local_core_replication_path . "/" . $f) && preg_match("/[0-9]+/", $f) ){
				if(!in_array($f, $configured_node_ids)) {
					runLocalCMD("rm -vfr " . $local_core_replication_path . "/" . $f ."/");
					runLocalCMD("rm -vfr " . $local_ui_replication_path . "/" . $f ."/");
				}
			}
		}		
	}
function setLastOutput($id, $str) {
	global $sm;
	$q = "update sm_remotes set last_output='" . $str . "' where id=" . $id;
	echo $q . PHP_EOL;
	$sm->db->exec($q);
}
function runLocalCMD($str) {
	global $_GLO;
	$rcmd = "$str  2>/dev/null";
	
	if($_GLO[debug_commands]) {
		$c = new Color();
		echo "\t " . $c($rcmd)->light_magenta->italic . PHP_EOL;
	}
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
	global $_GLO;
	$rcmd = "ssh $str -o 'ConnectTimeout 2' -C '$cmd' 2>/dev/null";
	if($_GLO[debug_commands]) {
		$c = new Color();
		echo  "\t"  .  $c($rcmd)->light_cyan->italic . PHP_EOL;
	}

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
	$rcmd = "ssh -o 'ConnectTimeout 2' $str -C 'echo 1' 2>/dev/null";

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



//https://github.com/kevinlebrun/colors.php/blob/master/src/Colors/Color.php

class Color
{
    const FORMAT_PATTERN = '#<([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)>(.*?)</\\1?>#s';
    // http://www.php.net/manual/en/functions.user-defined.php
    const STYLE_NAME_PATTERN = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

    const ESC = "\033[";
    const ESC_SEQ_PATTERN = "\033[%sm";

    protected $initial = '';
    protected $wrapped = '';
    // italic and blink may not work depending of your terminal
    protected $styles = array(
        'reset'            => '0',
        'bold'             => '1',
        'dark'             => '2',
        'italic'           => '3',
        'underline'        => '4',
        'blink'            => '5',
        'reverse'          => '7',
        'concealed'        => '8',

        'default'          => '39',
        'black'            => '30',
        'red'              => '31',
        'green'            => '32',
        'yellow'           => '33',
        'blue'             => '34',
        'magenta'          => '35',
        'cyan'             => '36',
        'light_gray'       => '37',

        'dark_gray'        => '90',
        'light_red'        => '91',
        'light_green'      => '92',
        'light_yellow'     => '93',
        'light_blue'       => '94',
        'light_magenta'    => '95',
        'light_cyan'       => '96',
        'white'            => '97',

        'bg_default'       => '49',
        'bg_black'         => '40',
        'bg_red'           => '41',
        'bg_green'         => '42',
        'bg_yellow'        => '43',
        'bg_blue'          => '44',
        'bg_magenta'       => '45',
        'bg_cyan'          => '46',
        'bg_light_gray'    => '47',

        'bg_dark_gray'     => '100',
        'bg_light_red'     => '101',
        'bg_light_green'   => '102',
        'bg_light_yellow'  => '103',
        'bg_light_blue'    => '104',
        'bg_light_magenta' => '105',
        'bg_light_cyan'    => '106',
        'bg_white'         => '107',
    );
    protected $userStyles = array();
    protected $isStyleForced = false;

    public function __construct($string = '')
    {
        $this->setInternalState($string);
    }

    public function __invoke($string)
    {
        return $this->setInternalState($string);
    }

    public function __call($method, $args)
    {
        if (count($args) >= 1) {
            return $this->apply($method, $args[0]);
        }

        return $this->apply($method);
    }

    public function __get($name)
    {
        return $this->apply($name);
    }

    public function __toString()
    {
        return $this->wrapped;
    }

    public function setForceStyle($force)
    {
        $this->isStyleForced = (bool) $force;
    }

    public function isStyleForced()
    {
        return $this->isStyleForced;
    }

    /**
     * https://github.com/symfony/Console/blob/master/Output/StreamOutput.php#L93-112
     * @codeCoverageIgnore
     */
    public function isSupported()
    {
    	global $_GET;
    	if($_GET[force_color] == 1) return true;
        if (DIRECTORY_SEPARATOR === '\\') {
            return false !== getenv('ANSICON');
        }

        return function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }

    /**
     * @codeCoverageIgnore
     */
    public function are256ColorsSupported()
    {
        return DIRECTORY_SEPARATOR === '/' && false !== strpos(getenv('TERM'), '256color');
    }

    protected function setInternalState($string)
    {
        $this->initial = $this->wrapped = (string) $string;
        return $this;
    }

    protected function stylize($style, $text)
    {
        if (!$this->shouldStylize()) {
            return $text;
        }

        $style = strtolower($style);

        if ($this->isUserStyleExists($style)) {
            return $this->applyUserStyle($style, $text);
        }

        if ($this->isStyleExists($style)) {
            return $this->applyStyle($style, $text);
        }

        if (preg_match('/^((?:bg_)?)color\[([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\]$/', $style, $matches)) {
            $option = $matches[1] == 'bg_' ? 48 : 38;
            return $this->buildEscSeq("{$option};5;{$matches[2]}") . $text . $this->buildEscSeq($this->styles['reset']);
        }

        //throw new NoStyleFoundException("Invalid style $style");
    }

    protected function shouldStylize()
    {
        return $this->isStyleForced() || $this->isSupported();
    }

    protected function isStyleExists($style)
    {
        return array_key_exists($style, $this->styles);
    }

    protected function applyStyle($style, $text)
    {
        return $this->buildEscSeq($this->styles[$style]) . $text . $this->buildEscSeq($this->styles['reset']);
    }

    protected function buildEscSeq($style)
    {
        return sprintf(self::ESC_SEQ_PATTERN, $style);
    }

    protected function isUserStyleExists($style)
    {
        return array_key_exists($style, $this->userStyles);
    }

    protected function applyUserStyle($userStyle, $text)
    {
        $styles = (array) $this->userStyles[$userStyle];

        foreach ($styles as $style) {
            $text = $this->stylize($style, $text);
        }

        return $text;
    }

    public function apply($style, $text = null)
    {
        if ($text === null) {
            $this->wrapped = $this->stylize($style, $this->wrapped);
            return $this;
        }

        return $this->stylize($style, $text);
    }

    public function fg($color, $text = null)
    {
        return $this->apply($color, $text);
    }

    public function bg($color, $text = null)
    {
        return $this->apply('bg_' . $color, $text);
    }

    public function highlight($color, $text = null)
    {
        return $this->bg($color, $text);
    }

    public function reset()
    {
        $this->wrapped = $this->initial;
        return $this;
    }

    public function center($width = 80, $text = null)
    {
        if ($text === null) {
            $text = $this->wrapped;
        }

        $centered = '';
        foreach (explode(PHP_EOL, $text) as $line) {
            $line = trim($line);
            $lineWidth = strlen($line) - mb_strlen($line, 'UTF-8') + $width;
            $centered .= str_pad($line, $lineWidth, ' ', STR_PAD_BOTH) . PHP_EOL;
        }

        $this->setInternalState(trim($centered, PHP_EOL));
        return $this;
    }

    protected function stripColors($text)
    {
        return preg_replace('/' . preg_quote(self::ESC) . '\d+m/', '', $text);
    }

    public function clean($text = null)
    {
        if ($text === null) {
            $this->wrapped = $this->stripColors($this->wrapped);
            return $this;
        }

        return $this->stripColors($text);
    }

    public function strip($text = null)
    {
        return $this->clean($text);
    }

    public function isAValidStyleName($name)
    {
        return preg_match(self::STYLE_NAME_PATTERN, $name);
    }

    /**
     * @deprecated
     */
    public function setTheme(array $theme)
    {
        return $this->setUserStyles($theme);
    }

    public function setUserStyles(array $userStyles)
    {
        foreach ($userStyles as $name => $styles) {
            if (!$this->isAValidStyleName($name)) {
                //throw new InvalidStyleNameException("$name is not a valid style name");
            }
        }

        $this->userStyles = $userStyles;
        return $this;
    }

    protected function colorizeText($text)
    {
        return preg_replace_callback(self::FORMAT_PATTERN, array($this, 'replaceStyle'), $text);
    }

    /**
     * https://github.com/symfony/Console/blob/master/Formatter/OutputFormatter.php#L124-162
     */
    public function colorize($text = null)
    {
        if ($text === null) {
            $this->wrapped = $this->colorizeText($this->wrapped);
            return $this;
        }

        return $this->colorizeText($text);
    }

    protected function replaceStyle($matches)
    {
        return $this->apply($matches[1], $this->colorize($matches[2]));
    }
}
?>
