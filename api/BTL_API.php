<?

class BTL_API {
    function add_service($btl, $options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_add_service($btl->RES, $options_array);
       if(!$rtc) {
       		$rtc =-1;
       };
       return $rtc;
    }
    function modify_service($btl, $svc_id, $options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_modify_service($btl->RES,  $svc_id, $options_array);
       if(!$rtc) {
       		$rtc =-1;
       };
       return $rtc;
    }    
    function delete_service($btl, $svc_id) {
       $rtc=bartlby_delete_service($btl->RES,  $svc_id);
       if(!$rtc) {
       		$rtc =-1;
       };
       return $rtc;
    }        
}