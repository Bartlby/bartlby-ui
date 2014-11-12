<?

include "../bartlby-ui.class.php";
include "Slim/Slim.php";

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
            $app->get("/worker(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_worker_list($filter, false, $node);

            });       
            $app->get("/worker(/node/:node)/:id", function($node=0, $id) use($app) {
                $filter = $app->request->params();
                $filter[worker_id]=$id;
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;
                api_v1_worker_list($filter, false, $node);
            });
          
            $app->post("/worker(/node/:node)/:id/activity", function($node=0, $id) use ($app) {
                $filter = json_decode($app->request->getBody());
                $filter = json_decode($filter, true);
               
                $btl=btl_api_load_node($node);
                $rtc = -1;
                $btl->worker_list_loop(function($wrk, $shm) use (&$rtc, $id, $filter, $btl) {
                        if($wrk[worker_id] == $id) {
                            if($filter[worker_state]) {
                                $rtc=bartlby_set_worker_state($btl->RES, $shm, $filter[worker_state]); 
                            } else {
                                $rtc = -1;
                            }
                            return LOOP_BREAK;           
                        }
                });
                
                if($rtc >= 0) {
                    $r[api][status_msg]="Worker Activity set to:" . $filter[worker_state];
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });

        });
        $app->group("/stored", function() use($app) {
            //Running STUFF
 

            $app->post("/worker(/node/:node)", function($node=0) use($app) {
                 $btl=btl_api_load_node($node);
                //ADD NEW
                 
                 $return = bartlby_add_worker($btl->RES,json_decode($app->request->getBody(), true));
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully created";
                    $r[api][new_id]=$return;
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
                 
                 
            });
            $app->patch("/worker(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
                 
                 $return = bartlby_modify_worker($btl->RES, $id , json_decode($app->request->getBody(),true));
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully modified";
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });


            $app->delete("/worker(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
                 
                 $return = bartlby_delete_worker($btl->RES,$id);
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully deleted";
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });
            $app->get("/worker(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
            
                
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_worker_list($filter, true, $node);

            }); 
            
            $app->get("/worker(/node/:node)/:id", function($node=0, $id) use($app) {
                $btl=btl_api_load_node($node);
                $r=bartlby_get_worker_by_id($btl->RES, $id);

                $r[api_pubkey]="";
                $r[api_privkey]="";
                $r[password]="";
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

function api_v1_worker_list($filter=array(), $from_disk = false, $node_id = false) {

    
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
    echo '"workers":[';
    $rcnt =0;
    $avail=0;
    $btl->worker_list_loop(function($svc, $shm) use(&$filter, &$rcnt, &$avail, &$from_disk, &$btl) {
        
        if($filter[worker_id] && (int)$svc[worker_id] != (int)$filter[worker_id]) return LOOP_CONTINUE;

        if($from_disk) {
                $svc=bartlby_get_worker_by_id($btl->RES, $svc[worker_id]);
        }
      
        if($filter[text_search] && !$btl->bartlby_service_matches_string($svc, $filter[text_search])) {
            return LOOP_CONTINUE;
        }
        
        $svc[api_pubkey]="";
        $svc[api_privkey]="";
        $svc[password]="";
        
        if($shm >= $filter[from] && $rcnt <= $filter[to]) { 
            echo json_format(json_encode($svc));
            $rcnt++;
            echo ",";
        }
        $avail++;
        

    });
    echo "null";
    echo '], ';
    echo '"available_workers": ' . $avail . '';
    echo '}';
}




?>
