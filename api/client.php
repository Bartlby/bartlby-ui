<?php
//Example call
// client.php /api/v1/running/service/1
include "bartlby_api_global.php";

$uri = $argv[1];


$publicHash = 'cbafc69f1134e866d8ac291d23c45e46d21f70b2';
//a85529e5e4abd47169d0db2e6ad630ae19946597';
$privateHash = '30391def8ac1bf34310ad32f2edf929a7ae642fc';
$microtime = microtime(true);
$cipher = new Cipher($privateHash);




$content    = json_encode(array(
    'worker_state' => '2'
));

$portier_content = json_encode(array(
	"method" => "set_passive",
	"service_id" => 1, 
	"state" =>2,
	"passive_text" => "asdsasddsa"
	));

$modify_server = '{"server_name":"localhos1","server_ip":"127.0.0.1","server_port":"9030","server_icon":"01generic.gif","server_enabled":1,"server_notify":0,"server_flap_seconds":"120","server_ssh_keyfile":"asdasd","server_ssh_passphrase":"asddasdasdasd","server_ssh_username":"dasdasdas","server_dead":null,"default_service_type":"1","enabled_triggers":"|4|3|2|1|7|","exec_plan":"0=00:00-23:59|1=00:00-23:59|2=00:00-23:59|3=00:00-23:59|4=00:00-23:59|5=00:00-23:59|6=00:00-23:59|","orch_id":"0"}';

$content=$modify_server;

$content = $cipher->encrypt($content);


echo "OUT: '" . $content . $uri . "'\n";
$hash = hash_hmac('sha256', $content . $uri . $params . $microtime, $privateHash);

$headers = array(
    'X-Public: '.$publicHash,
    'X-Hash: '.$hash,
    'X-Microtime:' . $microtime
);

$ch = curl_init('http://localhost' . $uri);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$content);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");




$result = curl_exec($ch);

$header_info = curl_getinfo($ch,CURLINFO_HEADER_OUT); //Where $header_info contains the HTTP Request information

echo $result;


curl_close($ch);




echo print_r($cipher->decrypt($result), true) . "\n\n";
//echo "RESULT\n======\n".print_r($result, true)."\n\n";

?>
