<?
include "bartlby_sdk.php";
$priv_key="0b76bedb4bfc7176d8bc3df1c090f7f7eaa522c7";
$pub_key="0b76bedb4bfc7176d8bc3df1c090f7f7eaa522c7";



$sdk = new BartlbyAPISDK($priv_key, $pub_key, "http://localhost");

$json_data="";
$params="";
$method="GET";
$request_uri="/api/v1/running/trap/2";

$r = $sdk->doRequest($request_uri,$method, $params, $json_data);
$arr = json_decode($r,true);
var_dump($arr);


?>