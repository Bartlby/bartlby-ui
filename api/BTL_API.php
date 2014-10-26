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
    //DOWNTIME
    function add_downtime($options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_add_downtime($this->RESOURCE, $options_array);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }
    function modify_downtime($svc_id, $options) {
       $options_array = json_decode($options, true);
       $rtc=bartlby_modify_downtime($this->RESOURCE,  $svc_id, $options_array);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }    
    function delete_downtime($svc_id) {
       $rtc=bartlby_delete_downtime($this->RESOURCE,  $svc_id);
       if(!$rtc) {
          $rtc =-1;
       };
       return $rtc;
    }  



}


class Cipher
{

    private $securekey;
    private $iv_size;

    function __construct($textkey)
    {
        $this->iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $this->securekey = hash('sha256', $textkey, TRUE);
    }

    function encrypt($input)
    {
        $iv = mcrypt_create_iv($this->iv_size,MCRYPT_DEV_URANDOM);
        return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->securekey, $input, MCRYPT_MODE_CBC, $iv));
    }

    function decrypt($input)
    {
        $input = base64_decode($input);
        $iv = substr($input, 0, $this->iv_size);
        $cipher = substr($input, $this->iv_size);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->securekey, $cipher, MCRYPT_MODE_CBC, $iv));
    }

}