<?

include "config.php";
include_once "bartlbystorage.class.php";

class Dashboards {
        function Dashboards() {
                $this->layout = new Layout();
                $this->storage=new bartlbyStorage("Dashboards");

        }


        function _About() {
                return "Dashboards Version 0.1 by h.januschka";
        }
  			function loadDashboard() {
  				global $_GET, $btl;
      		
      		$this->storage=new bartlbyStorage("Dashboards");
      		$stored = $this->storage->load_key($btl->user . "_dashboard");
      		
      		$res = new XajaxResponse();
      		$res->AddScript("loadDashboardLocal('" . $stored . "')");
      		
      		return $res;
  			}
      	function storeDashboard() {
      		//xajax_ExtensionAjax('Dashboards', 'storeDashboard');
      		global $_GET, $btl;
      		$json=$_GET[xajaxargs][2];
      		$this->storage=new bartlbyStorage("Dashboards");
      		$stored = $this->storage->save_key($btl->user . "_dashboard", $json);
      		
      		$res = new XajaxResponse();
      		$res->AddScript('noty({"text":"Dashboard Saved!","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
      		return $res;
      		
      		
      		
      	} 
      	
      	function _Menu() {
					$r =  $this->layout->beginMenu();
					$r .= $this->layout->addRoot("Dashboard");
					$r .= $this->layout->addSub("Dashboard", "View","extensions_wrap.php?script=Dashboards/gridster.php");
					
					
					
					$r .= $this->layout->endMenu();
					return $r;
				}
      
       
}

?>
