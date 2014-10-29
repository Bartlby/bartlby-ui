<?php
//Example call
// client.php /api/v1/running/service/1
$uri = $argv[1];

$publicHash = '3441df0babc2a2dda551d7cd39fb235bc4e09cd1e4556bf261bb49188f548348';
$privateHash = 'e249c439ed7697df2a4b045d97d4b9b7e1854c3ff8dd668c779013653913572';
$content    = json_encode(array(
    'test' => 'content'
));


echo "OUT: '" . $content . $uri . "'\n";
$hash = hash_hmac('sha256', $content . $uri, $privateHash);

$headers = array(
    'X-Public: '.$publicHash,
    'X-Hash: '.$hash
);

$ch = curl_init('http://localhost' . $uri);
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$content);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");




$result = curl_exec($ch);
curl_close($ch);

echo "RESULT\n======\n".print_r($result, true)."\n\n";
?>