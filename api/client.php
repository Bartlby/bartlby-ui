<?php
//Example call
// client.php /api/v1/running/service/1
include "BTL_API.php";

$uri = $argv[1];


$publicHash = 'cbafc69f1134e866d8ac291d23c45e46d21f70b2';
//a85529e5e4abd47169d0db2e6ad630ae19946597';
$privateHash = '30391def8ac1bf34310ad32f2edf929a7ae642fc';
$microtime = microtime(true);
$cipher = new Cipher($privateHash);




$content    = json_encode(array(
    'worker_state' => '2'
));

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




curl_close($ch);




echo print_r($cipher->decrypt($result), true) . "\n\n";
//echo "RESULT\n======\n".print_r($result, true)."\n\n";

?>
