<?php
$xajax = new xajax("xajax_dispatcher.php");
$xajax->registerFunction("AddModifyWorker");
$xajax->registerFunction("AddModifyClient");
$xajax->registerFunction("AddModifyServerGroup");
$xajax->registerFunction("AddModifyServiceGroup");
$xajax->registerFunction("AddModifyTrap");


$xajax->registerFunction("AddModifyService");
$xajax->registerFunction("CreateReport");
$xajax->registerFunction("CreatePackage");
$xajax->registerFunction("AddDowntime");
$xajax->registerFunction("QuickLook");
$xajax->registerFunction("ServerSearch");
$xajax->registerFunction("jumpToServerId");

$xajax->registerFunction("toggle_service_check");
$xajax->registerFunction("toggle_service_notify_check");


$xajax->registerFunction("toggle_servergroup_check");
$xajax->registerFunction("toggle_servergroup_notify_check");

$xajax->registerFunction("toggle_servicegroup_check");
$xajax->registerFunction("toggle_servicegroup_notify_check");

$xajax->registerFunction("toggle_service_handled");

$xajax->registerFunction("toggle_server_check");
$xajax->registerFunction("toggle_extension");
$xajax->registerFunction("toggle_server_notify_check");

$xajax->registerFunction("service_noaction");
$xajax->registerFunction("set_service_search_noact");


$xajax->registerFunction("ServiceSearch");
$xajax->registerFunction("jumpToServiceId");

$xajax->registerFunction("UserSearch");
$xajax->registerFunction("PluginSearch");
$xajax->registerFunction("SelectPlugin");

$xajax->registerFunction("jumpToUserId");

$xajax->registerFunction("removeDIV");

$xajax->registerFunction("updatePerfHandler");

$xajax->registerFunction("ExtensionAjax");
$xajax->registerFunction("group_search");
$xajax->registerFunction("forceCheck");

$xajax->registerFunction("BulkServiceSearch");
$xajax->registerFunction("IphoneOverView");

$xajax->registerFunction("setWorkerState");
$xajax->registerFunction("updateServiceDetail");

$xajax->registerFunction("bulkForce");
$xajax->registerFunction("bulkEnableChecks");
$xajax->registerFunction("bulkEnableNotifys");
$xajax->registerFunction("bulkDisableChecks");
$xajax->registerFunction("bulkDisableNotifys");

$xajax->registerFunction("bulkEditValues");
$xajax->registerFunction("bulkEditValuesServer");
$xajax->registerFunction("bulkEditValuesTrap");
$xajax->registerFunction("trapTester");
$xajax->registerFunction("showTrapData");
$xajax->registerFunction("setServiceDisplayPrio");

$xajax->registerFunction("regen_keys");

?>
