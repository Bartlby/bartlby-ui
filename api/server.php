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
            $app->get("/server(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_srv_list($filter, false, $node);

            });       
            $app->get("/server(/node/:node)/:id", function($node=0, $id) use($app) {
                $filter = $app->request->params();
                $filter[server_id]=$id;
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;
                api_v1_srv_list($filter, false, $node);
            });
            $app->post("/server(/node/:node)/:id/force", function($node=0, $id) use($app) {
                //Force Service at ID
                ////FIXME
                $btl=btl_api_load_node($node);
                //FIXME    


                $btl->service_list_loop(function($svc, $shm) use ($id, &$btl) {
                    if($svc[server_id] == $id) {
                        bartlby_check_force($btl->RES, $shm);
                        return LOOP_BREAK;
                    }                    
                });
                $r[api][status_code]=0;
                $r[api][status_msg]="All Checks forced";
                echo json_encode($r);                                
            });
            $app->post("/server(/node/:node)/:id/trigger", function($node=0, $id) use($app) {
                //Force Service at ID
                ////FIXME
                $btl=btl_api_load_node($node);
                //FIXME    

                $input = $app->request->params();
                $rcode=-1;
                $btl->server_list_loop(function($svc, $shm) use ($id, &$btl, &$input, &$rcode) {
                    if($svc[server_id] == $id) {
                        $rcode=bartlby_toggle_server_notify($btl->RES, $shm, 1);
                        return LOOP_BREAK;
                    }                    
                });
                $r[api][status_code]=$rcode;
                $r[api][status_msg]="Toggled Notifications ";
                echo json_encode($r);                                
            });
            $app->post("/server(/node/:node)/:id/active", function($node=0, $id) use($app) {
                //Force Service at ID
                ////FIXME
                $btl=btl_api_load_node($node);
                //FIXME    

                $input = $app->request->params();
                $rcode=-1;
                $btl->server_list_loop(function($svc, $shm) use ($id, &$btl, &$input, &$rcode) {
                    if($svc[server_id] == $id) {
                        $rcode=bartlby_toggle_server_active($btl->RES, $shm, 1);
                        return LOOP_BREAK;
                    }                    
                });
                $r[api][status_code]=$rcode;
                $r[api][status_msg]="Toggled Check";
                echo json_encode($r);                                
            });
            $app->post("/server(/node/:node)/:id/handle", function($node=0, $id) use($app) {
                //Force Service at ID
                ////FIXME
                $btl=btl_api_load_node($node);
                //FIXME    

                $input = $app->request->params();
                $rcode=-1;
                $btl->service_list_loop(function($svc, $shm) use ($id, &$btl, &$input, &$rcode) {
                    if($svc[server_id] == $id) {
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
 

            $app->post("/server(/node/:node)", function($node=0) use($app) {
                 $btl=btl_api_load_node($node);
                //ADD NEW
                 $API = new Btl_api($btl->RES);
                 $return = $API->add_server($app->request->getBody());
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully created";
                    $r[api][new_id]=$return;
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
                 
                 
            });
            $app->patch("/server(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
                 $API = new Btl_api($btl->RES);
                 $return = $API->modify_server($id , $app->request->getBody());
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully modified";
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });


            $app->delete("/server(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
                 $API = new Btl_api($btl->RES);
                 $return = $API->delete_server($id);
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully deleted";
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });
            $app->get("/server(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
            
                
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_srv_list($filter, true, $node);

            }); 
            
            $app->get("/server(/node/:node)/:id", function($node=0, $id) use($app) {
                $btl=btl_api_load_node($node);
                $r=bartlby_get_server_by_id($btl->RES, $id);
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

function api_v1_srv_list($filter=array(), $from_disk = false, $node_id = false) {

    
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
    echo '"servers":[';
    $rcnt =0;
    $avail=0;
    $btl->server_list_loop(function($svc, $shm) use(&$filter, &$rcnt, &$avail, &$from_disk, &$btl) {
        if($filter[server_id] && (int)$svc[server_id] != (int)$filter[server_id]) return LOOP_CONTINUE;

        if($from_disk) {
                $svc=bartlby_get_server_by_id($btl->RES, $svc[server_id]);
        }
      
        if($filter[text_search] && !$btl->bartlby_service_matches_string($svc, $filter[text_search])) {
            return LOOP_CONTINUE;
        }
        
        if($shm >= $filter[from] && $rcnt <= $filter[to]) { 
            echo json_format(json_encode($svc));
            $rcnt++;
            echo ",";
        }
        $avail++;
        

    });
    echo "null";
    echo '], ';
    echo '"available_servers": ' . $avail . '';
    echo '}';
}




?>
