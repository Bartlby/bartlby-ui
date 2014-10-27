<?

error_reporting(E_ERROR);
class BTL_User_Auth {
        public function __construct($btl, $pub)
        {

           $r=false;
           $btl->worker_list_loop(function($wrk, $shm) use(&$r, $pub)  {
      
                    if($wrk[api_pubkey] == $pub && strlen($pub)>10) {
                              
                        $r=$wrk;
                        return LOOP_BREAK;
                    }
           });
           if($r) {
                $this->WORKER=$r;
                $this->ALLOWED=$r[api_enabled];

           }
        }
        
        public function getPrivateKey() {
            
            return $this->WORKER[api_privkey];
        }
        public function isValidPublicKey($pubkey) {
            if($this->WORKER) {
                return true;
            }
        }
        public function isAllowToUseAPI() {
            if($this->ALLOWED != 1) {
                return false;
            }
            return true;
        }

}


function btl_api_load_node($node_id) {
    if(!$node_id) $node_id=0;
    $_GET[instance_id]=(int)$node_id;
    include "config.php";
    $btl=new BartlbyUi($Bartlby_CONF, false);
    return $btl;
}
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