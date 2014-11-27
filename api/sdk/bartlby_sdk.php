<?php

class BartlbyAPISDK {
	private $priv_key = "";
	private $pub_key = "";
	private $end_point = "";
	private $cipher = "";
	function __construct($priv_key, $pub_key, $endpoint) { 
		$this->priv_key=$priv_key;
		$this->pub_key=$pub_key;
		$this->end_point=$endpoint;
		$this->cipher = new Cipher($priv_key);
	}
	function doRequest($request_uri,$method="GET", $params, $json_data) {
		$content = $this->cipher->encrypt($json_data);
		$microtime = microtime(true);
		$hash = hash_hmac('sha256', $content . $request_uri . $params . $microtime, $this->priv_key);

		$headers = array(
		    'X-Public: '.$this->pub_key,
		    'X-Hash: '.$hash,
		    'X-Microtime:' . $microtime
		);

		$ch = curl_init($this->end_point . $request_uri);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
		curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$content);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		
		$result = curl_exec($ch);
		$header_info = curl_getinfo($ch,CURLINFO_HEADER_OUT); //Where $header_info contains the HTTP Request information
		
		$ret_code=curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if($ret_code != 200) return $result;
		
		return $this->cipher->decrypt($result);
	}

}



//Encryption / Decryption functions

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

?>
