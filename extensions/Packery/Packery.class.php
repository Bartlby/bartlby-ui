<?

include "config.php";
include_once "bartlbystorage.class.php";

class Packery {
        function Packery() {
                $this->layout = new Layout();
                $this->storage=new bartlbyStorage("Packery");

        }


        function _About() {
                return "Packery Version 0.1 by h.januschka";
        }
  			function loadPackery() {
  				global $_GET, $btl;
      		
      		$this->storage=new bartlbyStorage("Packery");
      		$stored = $this->storage->load_key($btl->user . "_dashboard");
      		
      		$res = new XajaxResponse();
      		$res->AddScript("loadPackeryLocal('" . $stored . "')");
      		
      		return $res;
  			}
      	function storePackery() {
      		//xajax_ExtensionAjax('Packery', 'storePackery');
      		global $_GET, $btl;
      		$json=$_GET[xajaxargs][2];
      		$this->storage=new bartlbyStorage("Packery");
      		$stored = $this->storage->save_key($btl->user . "_dashboard", $json);
      		
      		$res = new XajaxResponse();
      		$res->AddScript('noty({"text":"Packery Saved!","timeout": 600, "layout":"center","type":"success","animateOpen": {"opacity": "show"}})');
      		return $res;
      		
      		
      		
      	} 
      	
      	function _Menu() {
					$r =  $this->layout->beginMenu();
					$r .= $this->layout->addRoot("Packery");
					$r .= $this->layout->addSub("Packery", "View","extensions_wrap.php?script=Packery/packery.php");
					
					
					
					$r .= $this->layout->endMenu();
					return $r;
				}
      
       
}

?>
