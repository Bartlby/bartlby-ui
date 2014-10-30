<?

class BTL_API {
	function BTL_API($btl_resource) {
		$this->RESOURCE=$btl_resource;
	}
    function add_service($options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_add_service($this->RESOURCE, $options_array);
       if(!$rtc) {
       		$rtc =-1;
       };
       return $rtc;
    }
    function modify_service($svc_id, $options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_modify_service($this->RESOURCE,  $svc_id, $options_array);
       if(!$rtc) {
       		$rtc =-1;
       };
       return $rtc;
    }    
    function delete_service($svc_id) {
       $rtc=bartlby_delete_service($this->RESOURCE,  $svc_id);
       if(!$rtc) {
       		$rtc =-1;
       };
       return $rtc;
    }        
}