<?
include "bartlby_sdk.php";
$priv_key="30391def8ac1bf34310ad32f2edf929a7ae642fc";
$pub_key="cbafc69f1134e866d8ac291d23c45e46d21f70b2";



$sdk = new BartlbyAPISDK($priv_key, $pub_key, "http://localhost");

$json_data="";
$params="";
$method="GET";
$request_uri="/api/v1/running/core";

$r = $sdk->doRequest($request_uri,$method, $params, $json_data);
$arr = json_decode($r,true);
var_dump($arr);


?>