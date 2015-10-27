<?

include "config.php";
include_once "bartlbystorage.class.php";

class Pusher {
        function Pusher() {
              $this->layout = new Layout();
		$this->storage=new bartlbyStorage("Pusher");
	
        }


        function _About() {
                return "Pusher Version 0.1 by h.januschka";
        }
        
        function PushAPIKey($nr) {
        	return $this->layout->FormBox(array(
        			0=>"API Key (used in Prowl/NMA):",
        			1=>$this->layout->Field("PusherAPIKey", "text", $nr)
        		), true);
        	//return "<table width=100% class='table no-strip borderless'><tr><td>API-Key (used in prowl/nma):</td><td><input type='text' name='PusherAPIKey' value='$nr' class=></td></tr></table>";	
       }
        function _PRE_add_worker() {
					global $layout, $defaults, $btl;
		
			
		return $this->PushAPIKey("");
		
	}

	/*

	*/
	function _PRE_add_downtime() {
		
	
		
	}
	
	
	
	function _PRE_modify_downtime() {
		
	}
	function _POST_add_downtime() {
		
	}
	function _POST_modify_downtime() {
		
	}
	function _POST_add_worker() {
		global $layout, $_GET, $defaults, $btl;
		$k = $btl->user . "_PusherAPIKey";
		$this->storage->save_key($k, $_GET["PusherAPIKey"]);
		return "APIKey: <b>" . $_GET["PusherAPIKey"] . "</b> stored for user: " . $btl->user;
	}
	function _POST_modify_worker() {
		global $layout, $_GET, $defaults, $btl;
		$k = $_GET[worker_name] . "_PusherAPIKey";
		$this->storage->save_key($k, $_GET["PusherAPIKey"]);
		return "APIKey: <b>" . $_GET["PusherAPIKey"] . "</b> stored";		
	}

	function _workerDetails() {
		global $layout, $_GET, $defaults, $btl;
		$k = $defaults[name] . "_PusherAPIKey";
		$v = $this->storage->load_key($k);
		if(!$v) $v = "unset";
				return "Api Key:" . $v;	
		
		
	}

	function _PRE_modify_worker() {
		global $layout, $_GET, $defaults, $btl;
		$k = $defaults[name] . "_PusherAPIKey";
		$v = $this->storage->load_key($k);
		
		return $this->PushAPIKey($v);	
		
		
	}
        
        
}

?>
