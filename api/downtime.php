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
            $app->get("/downtime(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_downtime_list($filter, false, $node);

            });       
            $app->get("/downtime(/node/:node)/:id", function($node=0, $id) use($app) {
                $filter = $app->request->params();
                $filter[downtime_id]=$id;
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;
                api_v1_downtime_list($filter, false, $node);
            });
            

        });
        $app->group("/stored", function() use($app) {
            //Running STUFF
 

            $app->post("/downtime(/node/:node)", function($node=0) use($app) {
                 $btl=btl_api_load_node($node);
                //ADD NEW
                 $API = new Btl_api($btl->RES);
                 $return = $API->add_downtime($app->request->getBody());
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully created";
                    $r[api][new_id]=$return;
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
                 
                 
            });
            $app->patch("/downtime(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
                 $API = new Btl_api($btl->RES);
                 $return = $API->modify_downtime($id , $app->request->getBody());
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully modified";
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });


            $app->delete("/downtime(/node/:node)/:id", function($node=0, $id) use($app) {
                 $btl=btl_api_load_node($node);
                //MODIFY
                 $API = new Btl_api($btl->RES);
                 $return = $API->delete_downtime($id);
                 $r[api][status_code]=$return;
                 if($return >= 0) {
                    $r[api][status_msg]="Successfully deleted";
                 } else {
                    $r[api][status_msg]="Failed";
                }
                echo json_format(json_encode($r));
            });
            $app->get("/downtime(/node/:node)", function($node=0) use($app) {
                $filter = $app->request->params();
            
                
                if(!$filter[from]) $filter[from]=0;
                if(!$filter[to]) $filter[to]=20;

                api_v1_downtime_list($filter, true, $node);

            }); 
            
            $app->get("/downtime(/node/:node)/:id", function($node=0, $id) use($app) {
                $btl=btl_api_load_node($node);
                
                $r=bartlby_get_downtime_by_id($btl->RES, $id);
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

function api_v1_downtime_list($filter=array(), $from_disk = false, $node_id = false) {

    
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
    echo '"downtimes":[';
    $rcnt =0;
    $avail=0;
    $btl->downtime_list_loop(function($svc, $shm) use(&$filter, &$rcnt, &$avail, &$from_disk, &$btl) {
        var_dump($svc);
        exit;
        if($filter[downtime_id] && (int)$svc[downtime_id] != (int)$filter[downtime_id]) return LOOP_CONTINUE;

        if($from_disk) {
              
                $svc=bartlby_get_downtime_by_id($btl->RES, $svc[downtime_id]);

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
    echo '"available_downtimes": ' . $avail . '';
    echo '}';
}




?>
