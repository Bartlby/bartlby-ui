<?
//Run UI-Extensions from shell 
//Sample Call:
// php automated.php \
//   	username=hjanuschka \
//   	password=gizmo1 \
//   	script=BnR/do_backup.php \
//   	package_with_comment=Automated_Backup
//   	package_with_plugins=checked \
//   	package_with_perf=checked \
//   	package_with_config=checked 2>&1>/dev/null;
//   	
// Will produce a full backup

session_start();
chdir("../");
for($x=0; $x<$argc; $x++) {
	$t=explode("=", $argv[$x]);
	$_GET[$t[0]]=$t[1];
}
$_SESSION[username]=$_GET["username"];
$_SESSION[password]=sha1($_GET["password"]);

include("extensions/"  . $_GET[script]);



?>
