<?
include_once "BTL_API.php";
class HMACAuth extends \Slim\Middleware
{
    /**
     * @var array
     */
    protected $settings = array(
        
    );

    /**
     * Constructor
     *
     * @param   array  $config   Configuration for Strong and Login Details
     * @param   \Strong\Strong $strong
     * @return  void
     */
    public function __construct(array $config = array(), \Strong\Strong $strong = null)
    {
        $this->userAuth = $config["userAuth"];
      
       
    }

    /**
     * Call
     *
     * @return void
     */
    public function call()
    {
        $req = $this->app->request();




        // Authentication Initialised
        $this->HMACAuth($req);

        
        $res = $this->app->response;
        $body=$res->getBody();

        //This One Crypts the Result
        $res->setBody($this->Cipher->encrypt($body));
        

        //This Decrypts the input BODY
        
        //$env['slim.input_original'] = $env['slim.input'];

         
      
       
    }
    function authFailed($msg) {
        $res = $this->app->response();
        $res->status(403);
        echo "Auhorization failed\n";
        //echo '"' .$content .  "/api" . $req->getPathInfo() . '"' . "\n";
        echo $msg;
        return;
    }


    /**
     * Form based authentication
     *
     * @param \Strong\Strong $auth
     * @param object $req
     */
    private function HMACAuth($req)
    {
        $app = $this->app;
        $config = $this->config;

            //$this->next->call(); -> GOOD
            //$res->status(403); -> BAD
            $publicHash  = $req->headers('X-Public');
            $contentHash = $req->headers('X-Hash');
            $microTime = $req->headers('X-Microtime');
            $local_microTime = microtime(true);
            
            $content     = $req->getBody();



            
            $privateHash = $this->userAuth->getPrivateKey();
            
            $this->Cipher = new Cipher($privateHash);

            $hash = hash_hmac('sha256', $content . $_SERVER[REQUEST_URI] . $microTime, $privateHash);

           if(!$this->userAuth->isAllowToUseAPI()) {
                $this->authFailed("Not Alled to use API");
                return;
           }
            if(($local_microTime-$microTime) >= 10) {
                //Replay attack?
                $this->authFailed("Hash error");

                return;
            }
            if ($hash != $contentHash){
                //MISSMATCH
                $this->authFailed("hash error 2");
                return;
                
                
            } else {
               $env = $this->app->environment;
               $env['slim.input'] = $this->Cipher->decrypt($env['slim.input']);
                
                
                $this->next->call();
            }

        
        
        
    }

   
}