<?

include "config.php";

class PluginArgumentQuickLook {
        function PluginArgumentQuickLook() {
                $this->layout = new Layout();

        }


        function _About() {
                return "lets you search the plugin arguments via quick look Version 0.1 by h.januschka";
        }
       
        /*
        function _overview() {
                return "_overview";
        }
        function _services() {
                return "_services";
        }
        function _processInfo() {
                return "_processInfo";
        }
        */
        /*
        function _serverDetail() {
                return "";
        }
        */
	function _quickLook() {
		global $_GET, $rq, $btl;
		
		$servers=$_GET["servers"];
		$rq .= "<tbody class='no-border-y'>";
		$rq .= "<tr>";
		$rq .= "<td colspan=2>";
		$rq .= "<center><b>Plugin Argument</b></center>";
		$rq .= "</td></tr>";
		
		
		$svcfound=false;
		@reset($servers);
		
		$btl->service_list_loop(function($svc, $shm) use (&$svcfound, &$rq, &$btl) {
				global $_GET;
				
				if(@preg_match("/" . $_GET[search] . "/i", $svc[plugin_arguments])) {
				
				
					$rq .= "<tr><td><a href='service_detail.php?service_place=" . $svc[shm_place] . "'>" . $svc[server_name] . "/" . $svc[service_name] . "</A></td><td width='20%'>" . $btl->getServiceOptions($svc, $layout) . "</td>";	
					$svcfound=true;
				}
			
		});
			
		if($svcfound == false) {
			$rq .= "<tr><td colspan=2><i>no argument line matched</i></td></tr>";
		}


		$rq .= "</table>";

		$_GET[rq] = $rq; //damn return to extensCaller
		return "";
	}

        
}

?>
