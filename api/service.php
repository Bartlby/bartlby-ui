<?

include "../bartlby-ui.class.php";
include "Slim/Slim.php";

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

error_reporting(E_ERROR);
ini_set("display_errors", "true");

include "bartlby_api_global.php";


$app->group("/v1", function() use($app) {
        //V1 API
        //FIXME AUTH THIS REQUEST
        $app->group("/running", function() use($app) {
            //Running STUFF
            $app->get("/service(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_svc_list($filter, false, $node);

            });       
            $app->get("/service(/node/:node)/:id", function($node=0, $id) use($app) {
                $filter = $app->request->params();
                $filter[service_id]=$id;
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;
                api_v1_svc_list($filter, false, $node);
            });
            $app->post("/service(/node/:node)/:id/force", function($node=0, $id) use($app) {
                //Force Service at ID
                ////FIXME
                $btl=btl_api_load_node($node);
                //FIXME    


                $btl->service_list_loop(function($svc, $shm) use ($id, &$btl) {
                    if($svc[service_id] == $id) {
                        bartlby_check_force($btl->RES, $shm);
                    }                    
                });
                $r[api][status_code]=0;
                $r[api][status_msg]="Check forced";
                echo json_encode($r);                                
            });
            $app->post("/service(/node/:node)/:id/trigger", function($node=0, $id) use($app) {
                //Force Service at ID
                ////FIXME
                $btl=btl_api_load_node($node);
                //FIXME    

                $input = $app->request->params();
                $rcode=-1;
                $btl->service_list_loop(function($svc, $shm) use ($id, &$btl, &$input, &$rcode) {
                    if($svc[service_id] == $id) {
                        $rcode=bartlby_toggle_service_notify($btl->RES, $shm, 1);
                    }                    
                });
                $r[api][status_code]=$rcode;
                $r[api][status_msg]="Toggled Notifications ";
                echo json_encode($r);                                
            });
            $app->post("/service(/node/:node)/:id/active", function($node=0, $id) use($app) {
                //Force Service at ID
                ////FIXME
                $btl=btl_api_load_node($node);
                //FIXME    

                $input = $app->request->params();
                $rcode=-1;
                $btl->service_list_loop(function($svc, $shm) use ($id, &$btl, &$input, &$rcode) {
                    if($svc[service_id] == $id) {
                        $rcode=bartlby_toggle_service_active($btl->RES, $shm, 1);
                    }                    
                });
                $r[api][status_code]=$rcode;
                $r[api][status_msg]="Toggled Check";
                echo json_encode($r);                                
            });
            $app->post("/service(/node/:node)/:id/handle", function($node=0, $id) use($app) {
                //Force Service at ID
                ////FIXME
                $btl=btl_api_load_node($node);
                //FIXME    

                $input = $app->request->params();
                $rcode=-1;
                $btl->service_list_loop(function($svc, $shm) use ($id, &$btl, &$input, &$rcode) {
                    if($svc[service_id] == $id) {
                        $rcode=bartlby_toggle_service_handled($btl->RES, $shm, 1);
                    }                    
                });
                $r[api][status_code]=$rcode;
                $r[api][status_msg]="Toggled Handle";
                echo json_encode($r);                                
            });

        });
        $app->group("/stored", function() use($app) {
            //Running STUFF
            $app->post("/service(/node/:node)", function($node=0) use($app) {
                 $btl=btl_api_load_node($node);
                //ADD NEW
            });
            $app->patch("/service(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
            });
            $app->get("/service(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_svc_list($filter, true, $node);

            });       
            $app->get("/service(/node/:node)/:id", function($node=0, $id) use($app) {
                $filter = $app->request->params();
                $filter[service_id]=$id;
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;
                api_v1_svc_list($filter, true, $node);
            });

        });

});


$app->response['Content-Type'] = 'application/json';
$app->run();

function api_v1_svc_list($filter=array(), $from_disk = false, $node_id = false) {

    ////FIXME
    $btl=btl_api_load_node($node_id);
    
    $status_code=1;
    $status_msg="loaded";
    $filter[node_id] = $node_id;
    echo "{";
    echo '"api": {';
    echo '"status_code":' . $status_code;
    echo ',"status_msg":"' . $status_msg . '"';
    echo '},';
    echo '"filter": ';
    echo json_encode($filter);
    echo ',';
    echo '"services":[';
    $rcnt =0;
    $avail=0;
    $btl->service_list_loop(function($svc, $shm) use(&$filter, &$rcnt, &$avail, &$from_disk, &$btl) {

        if($filter[service_id] && (int)$svc[service_id] != (int)$filter[service_id]) return LOOP_CONTINUE;
        if($filter[server_id] && $filter[server_id] != $svc[server_id]) {
                return LOOP_CONTINUE;   
        }
        if($filter[servergroup_id] && !isInServerGroup($svc, $filter[servergroup_id])) {
            return LOOP_CONTINUE;   
        }
        if($filter[servicegroup_id] && !isInServiceGroup($svc, $filter[servicegroup_id])) {
            return LOOP_CONTINUE;   
        }
        if($filter[service_id] != "" && $svc[service_id] != $filter[service_id]) {
                    
            return LOOP_CONTINUE;   
        }
        if($filter[downtime] == "" && $filter[invert] == "" && $filter[expect_state] != "" && $svc[current_state] != $filter[expect_state]) {
            return LOOP_CONTINUE;   
        }
        if($filter[downtime] == "" &&  $filter[invert] && $filter[expect_state] != "" && $svc[current_state] == $filter[expect_state] ) {
            return LOOP_CONTINUE;   
        }
        if($filter[invert] && $filter[expect_state] != "" && $svc[handled] == 1) {
            return LOOP_CONTINUE;   
        }       
        if($filter[invert] && $filter[expect_state] != "" && $svc[current_state] == 4) {
            return LOOP_CONTINUE;   
        }
        if($filter[downtime] && $svc[is_downtime] != 1) {
            return LOOP_CONTINUE;               
        }
        if($filter[expect_state] != "" && $svc[is_downtime] == 1) {
            return LOOP_CONTINUE;   
        }
        if($filter[expect_state] != "" && $svc[handled] == 1) {
            return LOOP_CONTINUE;   
        }
        if(($filter[handled] == "yes"||$filter[handled] == true) && $svc[handled] != 1) {
            return LOOP_CONTINUE;
        }
        if($filter[acks] == "yes" && $svc[service_ack_current] != 2) {
            return LOOP_CONTINUE;   
        }
        if($filter[text_search] && !$btl->bartlby_service_matches_string($svc, $filter[text_search])) {
            return LOOP_CONTINUE;
        }
        
        if($shm >= $filter[from] && $rcnt <= $filter[to]) { 
            if($from_disk) {
                $svc=bartlby_get_service_by_id($btl->RES, $svc[service_id]);
            }
            //get color and beauty state
            
            if((int)$filter[service_expand_ui] == 1) {
                //Expand with UI friendly objects:
                $svc[beauty_color]=$btl->getColor($svc[current_state]);
                $svc[beauty_state]=$btl->getState($svc[current_state]);
                $svc[beauty_type]=$btl->getSVCType($svc[service_type]);
                $svc[beauty_dead_marker]=$btl->resolveDeadMarker($svc[server_dead]);
                $svc[beauty_service_enabled]=$svc[service_active] == 1 ? "true" : "false";
                $svc[beauty_fires_events]=$svc[fires_events] == 1 ? "true" : "false";
                $svc[beauty_check_plan] = $btl->resolveServicePlan($svc[exec_plan]);
                if($svc[beauty_check_plan] == "") $svc[beauty_check_plan] = "none";
                $svc[beauty_triggers] = implode(",", explode("|", $svc[enabled_triggers]));
                if($svc[beauty_triggers] == "") $svc[beauty_triggers] = "all";
                $svc[beauty_handled]=$svc[handled] == 1 ? "HANDLED" : "UNHANDLED";
                $svc[beauty_check_is_running] = $svc[check_is_running] ? "true" : "false";
                $svc[beauty_notify_enabled] = $svc[notify_enabled] ? "true" : "false";
                $svc[beauty_server_enabled] = $svc[server_enabled] ? "true" : "false";
            }
            echo json_format(json_encode($svc));
            $rcnt++;
            echo ",";
        }
        $avail++;
        

    });
    echo "null";
    echo '], ';
    echo '"available_services": ' . $avail . '';
    echo '}';
}




?>
