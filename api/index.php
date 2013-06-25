<?
include "../config.php";
include "../bartlby-ui.class.php";
include "Slim/Slim.php";

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->bartlby=$btl=new BartlbyUi($Bartlby_CONF);


$app->get('/service', function () use ($app) {
    
    $r = $app->bartlby->GetSVCMap();
    if(!$r) {
        $app->response->status(404);
        return;     
    }
    $app->response->body(json_format(json_encode($r)));


});
$app->get('/service/cfg/:id', function ($id) use ($app) {
    
    $r = bartlby_get_service_by_id($app->bartlby->RES, $id);
    if(!$r) {
        $app->response->status(404);
        $r[btl_error_code]=-1;
        $r[btl_error_message]="Service does not exist";

    }
    $app->response->body(json_format(json_encode($r)));


});

$app->get('/service/:id', function ($id) use ($app) {
    $shm_id=$app->bartlby->findSHMPlace($id);    
    $r = bartlby_get_service($app->bartlby->RES, $shm_id);
    if(!$r) {
        $app->response->status(404);
        $r[btl_error_code]=-1;
        $r[btl_error_message]="Service does not exist";

    }
    $app->response->body(json_format(json_encode($r)));


});

$app->post('/service/:id', function ($id) use ($app) {
	//FIXME SNMP names do not match
	$input_data=json_decode($app->request->getBody(), true);	//GET JSON DATA and convert it to an ARRAY
	
	$keys = array(
			"plugin",
			"service_name",
			"plugin_arguments",
			"notify_enabled",
			"check_interval",
			"service_type",
			"service_passive_timeout",
			"server_id",
			"service_check_timeout",
			"service_var",
			"exec_plan",
			"service_ack_enabled",
			"service_retain",
			"service_active",
			"flap_seconds",
			"escalate_divisor",
			"renotify_interval",
			"fires_events",
			"enabled_triggers",
			"snmp_community",
			"snmp_textmatch",
			"snmp_objid",
			"snmp_version",
			"snmp_warning",
			"snmp_critical",
			"snmp_type"
	);
	$missing_keys=array_has_keys($input_data, $keys);
	if($missing_keys == "") {
		$r = bartlby_modify_service($app->bartlby->RES,$id,$input_data); //MODIFY THE BTL OBJECT
		if($r != 0) {
			$app->response->status(404);
			$r[btl_error_code]=-1;
            $r[btl_error_message]="modify service failed!";
		}
		$app->response->body(json_format(json_encode(true)));
	} else {
		$app->response->status(503);
        $r[btl_error_code]=-1;
        $r[btl_error_message]="missing the following keys " . $missing_keys;
		$app->response->body(json_format(json_encode(true)));
		return;				
	}

});









$app->response['Content-Type'] = 'application/json';
$app->run();


function array_has_keys($array = array(), $keys = array()) {
	$missing_keys = "";
	for($x; $x<count($keys);$x++) {
		if(!array_key_exists($keys[$x], $array)) {
			if($keys[$x] == "") continue;
			$missing_keys .= $keys[$x] . ",";
			
		}

	}
	return $missing_keys;
}
function json_format($json) 
{ 
    $tab = "  "; 
    $new_json = ""; 
    $indent_level = 0; 
    $in_string = false; 

    $json_obj = json_decode($json); 

    if($json_obj === false) 
        return false; 

    $json = json_encode($json_obj); 
    $len = strlen($json); 

    for($c = 0; $c < $len; $c++) 
    { 
        $char = $json[$c]; 
        switch($char) 
        { 
            case '{': 
            case '[': 
                if(!$in_string) 
                { 
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1); 
                    $indent_level++; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case '}': 
            case ']': 
                if(!$in_string) 
                { 
                    $indent_level--; 
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case ',': 
                if(!$in_string) 
                { 
                    $new_json .= ",\n" . str_repeat($tab, $indent_level); 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case ':': 
                if(!$in_string) 
                { 
                    $new_json .= ": "; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case '"': 
                if($c > 0 && $json[$c-1] != '\\') 
                { 
                    $in_string = !$in_string; 
                } 
            default: 
                $new_json .= $char; 
                break;                    
        } 
    } 

    return $new_json; 
} 


?>
