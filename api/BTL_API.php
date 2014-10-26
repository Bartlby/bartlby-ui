<?

class BTL_API {
  	function BTL_API($btl_resource) {
  		$this->RESOURCE=$btl_resource;
  	}
    ////SERVICES
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

    ////SERVER

    function add_server($options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_add_server($this->RESOURCE, $options_array);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }
    function modify_server($svc_id, $options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_modify_server($this->RESOURCE,  $svc_id, $options_array);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }    
    function delete_server($svc_id) {
       $rtc=bartlby_delete_server($this->RESOURCE,  $svc_id);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }   
    ////SERVERGROUP
    function add_servergroup($options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_add_servergroup($this->RESOURCE, $options_array);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }
    function modify_servergroup($svc_id, $options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_modify_servergroup($this->RESOURCE,  $svc_id, $options_array);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }    
    function delete_servergroup($svc_id) {
       $rtc=bartlby_delete_servergroup($this->RESOURCE,  $svc_id);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }  
    //SERVICEGROUP
    function add_servicegroup($options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_add_servicegroup($this->RESOURCE, $options_array);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }
    function modify_servicegroup($svc_id, $options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_modify_servicegroup($this->RESOURCE,  $svc_id, $options_array);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }    
    function delete_servicegroup($svc_id) {
       $rtc=bartlby_delete_servicegroup($this->RESOURCE,  $svc_id);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }  




}