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
            //Running STUFF
            $app->get("/extension(/node/:node)/:extension/:function", function($node=0, $extension, $function) use($app) {
                    $btl=btl_api_load_node($node);

                    $i=$btl->getOneExtensionReturn($extension, $function);
                    echo json_format(json_encode($i));

            });       
            $app->post("/extension(/node/:node)/:extension/:function", function($node=0, $extension, $function) use($app) {
                    $btl=btl_api_load_node($node);

                    $i=$btl->getOneExtensionReturn($extension, $function);
                    echo json_format(json_encode($i));

            });      
            $app->delete("/extension(/node/:node)/:extension/:function", function($node=0, $extension, $function) use($app) {
                    $btl=btl_api_load_node($node);

                    $i=$btl->getOneExtensionReturn($extension, $function);
                    echo json_format(json_encode($i));

            });      

            


});


$app->response['Content-Type'] = 'application/json';
$app->run();


/* Fix for extensions requiring Layout*/

class Layout {
}


?>
