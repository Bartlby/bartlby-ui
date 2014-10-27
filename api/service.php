<?

include "../bartlby-ui.class.php";
include "Slim/Slim.php";
include "BTL_API.php";
error_reporting(E_ALL);
\Slim\Slim::registerAutoloader();

ini_set("display_errors", "true");
include "HMACAuth.php";

$app = new \Slim\Slim();

include "bartlby_api_global.php";

//AS LOADING UI STUFF IS PATH DEPENDAND MOVE OUT OF API FOLDER - FROM HERE :)
chdir("../");
$btl = btl_api_load_node(0); // ATTACH TO API-NODE (this is the one you auth against)

$app->add(new HMACAuth(array(
                            "userAuth"=>new BTL_User_Auth($btl, $_SERVER["HTTP_X_PUBLIC"])
                       )));


$app->notFound(function () {
    $r[api][status_code]=-404;
    $r[api][status_msg]="Call not found1";
    echo json_format(json_encode($r));
    exit;
});
 
error_reporting(E_ERROR);
ini_set("display_errors", "true");


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
                        return LOOP_BREAK;
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
                        return LOOP_BREAK;
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
                        return LOOP_BREAK;
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
                        return LOOP_BREAK;
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
                 $API = new Btl_api($btl->RES);
                 $return = $API->add_service($app->request->getBody());
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully created";
                    $r[api][new_id]=$return;
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
                 
                 
            });
            $app->patch("/service(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
                 $API = new Btl_api($btl->RES);
                 $return = $API->modify_service($id , $app->request->getBody());
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully modified";
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });


            $app->delete("/service(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
                 $API = new Btl_api($btl->RES);
                 $return = $API->delete_service($id);
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully deleted";
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });
            $app->get("/service(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
            
                
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_svc_list($filter, true, $node);

            }); 
            
            $app->get("/service(/node/:node)/:id", function($node=0, $id) use($app) {
                $btl=btl_api_load_node($node);
                $r=bartlby_get_service_by_id($btl->RES, $id);
                if($r) {
                    $r[api][status_code]=0;
                    $r[api][status_msg]="Success";
                } else {
                    $r[api][status_code]=-1;
                    $r[api][status_msg]="Failed to Fetch Service";
                }
                echo json_format(json_encode($r));

                 
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
        if($from_disk) {
                $svc=bartlby_get_service_by_id($btl->RES, $svc[service_id]);
        }
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
            
            //get color and beauty state
            
            
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
