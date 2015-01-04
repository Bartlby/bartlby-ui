<?
$r[output]="";
$r[exit_code]=2;



/*
input is like {
					"plugin": "bartlby_load",
					"parameters": " -w 10 -c 20 -p"
				}

return data:
	{
		"output": "SOME OUTPUT",
		"exit_code": 2‚
	}
*/


$fp = popen("/opt/bartlby-agent/plugins/bartlby_load -w 10 -c 20 -p", "r");
while($s = fgets($fp)) {
	$r[output] .= $s;

}
$ex = pclose($fp);

$r[exit_code]=$ex;


echo json_encode($r);

?>
