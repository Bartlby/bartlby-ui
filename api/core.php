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
            $app->get("/core(/node/:node)", function($node=0) use($app) {
                    $btl=btl_api_load_node($node);
                    $i = bartlby_get_info($btl->RES);
                    $i[input]=$app->request->getBody();
                    
                    echo json_format(json_encode($i));

            });       
            $app->post("/core(/node/:node)/reload", function($node=0) use($app) {
                
                    $btl=btl_api_load_node($node);
                    $i=bartlby_reload($btl->RES);
                    echo json_format(json_encode($i));
            });   
            $app->get("/core(/node/:node)/file", function($node=0) use($app) {
                    $input = $app->request->params();
                    $file_name = $input["filename"];
                    $btl=btl_api_load_node($node);
                    //FIXME CHECK PERMISSIONS / ALLOWED FOLDER LIST?!
                    if(file_exists($file_name)) {
                        $cnt = file_get_contents($file_name);
                    }


                    $i[file]=$file_name;
                    $i[content]=base64_encode($cnt);
                    echo json_format(json_encode($i));
            });   

            
            

        });
       

});


$app->response['Content-Type'] = 'application/json';
$app->run();





?>
