<?

class HMACAuth extends \Slim\Middleware
{
    /**
     * @var array
     */
    protected $settings = array(
        'privateHash' => 'e249c439ed7697df2a4b045d97d4b9b7e1854c3ff8dd668c779013653913572'
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
        $this->config = array_merge($this->settings, $config);
        
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
       
    }
    function authFailed($msg) {
        $res = $this->app->response();
        $res->status(403);
        echo "Auhorization failed\n";
        //echo '"' .$content .  "/api" . $req->getPathInfo() . '"' . "\n";
        echo $msg;
        return;
    }
    function isValidPublicHash($publicHash) {
            return true;
            return true;
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
            $privateHash  = $config["privateHash"];
            $content     = $req->getBody();

            $hash = hash_hmac('sha256', $content . $_SERVER[REQUEST_URI] . $microTime, $privateHash);

            if(!$this->isValidPublicHash($publicHash)) {
                //Unkown Public Hash?
                $this->authFailed("Invalid Public Hash");
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
                
                $this->next->call();
            }

        
        
        
    }

    /**
     * HTTPAuth based authentication
     *
     * This method will check the HTTP request headers for previous authentication. If
     * the request has already authenticated, the next middleware is called. Otherwise,
     * a 401 Authentication Required response is returned to the client.
     *
     * @param \Strong\Strong $auth
     * @param object $req
     */
    private function httpAuth($auth, $req)
    {
        $res = $this->app->response();
        $authUser = $req->headers('PHP_AUTH_USER');
        $authPass = $req->headers('PHP_AUTH_PW');

        if ($authUser && $authPass && $auth->login($authUser, $authPass)) {
            $this->next->call();
        } else {
            $res->status(401);
            $res->header('WWW-Authenticate', sprintf('Basic realm="%s"', $this->config['realm']));
        }
    }
}