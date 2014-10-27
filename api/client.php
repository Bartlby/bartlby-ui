<?php
//Example call
// client.php /api/v1/running/service/1
include "bartlby_api_global.php";

$uri = $argv[1];


$publicHash = 'f4a0fd26ed0e524216eea9769bfe212a304927a0';
//a85529e5e4abd47169d0db2e6ad630ae19946597';
$privateHash = '61084ac1734c1865b81c6c0e6c5fd6b59e0ca787';
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
$content=$portier_content;

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
