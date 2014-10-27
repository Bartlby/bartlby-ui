<?php
//Example call
// client.php /api/v1/running/service/1
include "BTL_API.php";

$uri = $argv[1];


$publicHash = '02e229d268a16d40605933b82f5e8acb2c6288161';
$privateHash = '5ef8ae34695c0cee752a305fd7ad38e8a3b305f7';
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
