<?php
/**
 * 极验行为式验证安全平台，php 网站主后台包含的库文件
 */
class Geetest{
	const GT_API_SERVER  = 'http://api.geetest.com';
	const GT_SSL_SERVER  = 'https://api.geetest.com';
	const GT_SDK_VERSION  = 'php_2.15.4.2.2';
	private $captcha_id;
	private $private_key;
	public function __construct() {
		$this->challenge = "";
	}
	public function set_captchaid($captcha_id) {
		$this->captcha_id = $captcha_id;
	}
	public function set_privatekey($private_key) {
		$this->private_key = $private_key;
	}
	public function register() {
		$this->challenge = $this->send_request("/register.php", array("gt"=>$this->captcha_id));
		if (strlen($this->challenge) != 32) {
			return 0;
		}
		return 1;
	}
	public function get_widget($product, $popupbtnid = "", $ssl = FALSE) {
		$params = array(
			"gt" => $this->captcha_id,
			"challenge" => $this->challenge,
			"product" => $product,
		);
		if ($product == "popup") {
			$params["popupbtnid"] = $popupbtnid;
		}
		$server = $ssl ? self::GT_SSL_SERVER : self::GT_API_SERVER;
		return "<script type='text/javascript' src='". $server ."/get.php?". http_build_query($params) ."'></script>";
	}
	public function validate($challenge, $validate, $seccode) {
		if ( ! $this->check_validate($challenge, $validate)) {
			return FALSE;
		}
		$data = array(
			"seccode"=>$seccode,
			"sdk"=>self::GT_SDK_VERSION,
		);
		$url = "http://api.geetest.com/validate.php";
		$codevalidate = $this->_request($url, $data);
		if (strlen($codevalidate) > 0 && $codevalidate == md5($seccode)) {
			return TRUE;
		} else if ($codevalidate == "false"){
			return FALSE;
		} else {
			return $codevalidate;
		}
	}
	private function check_validate($challenge, $validate) {
		if (strlen($validate) != 32) {
			return FALSE;
		}
		if (md5($this->private_key.'geetest'.$challenge) != $validate) {
			return FALSE;
		}
		return TRUE;
	}
	private function send_request($path, $data, $method = "GET") {
		if ($method == "GET") {
			$opts = array(
			    'http'=>array(
				    'method'=>"GET",
				    'timeout'=>2,
			    )
		    );
		    $context = stream_context_create($opts);
			$response = file_get_contents(self::GT_API_SERVER.$path."?". http_build_query($data), false, $context);
			return $response;
		}
	}
	    private function _request($url, $postdata = null){
	    	$data = http_build_query($postdata);
	    	if(function_exists('curl_exec')){
	    		$ch = curl_init();
	    		curl_setopt($ch, CURLOPT_URL, $url);
	    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    		if(!$postdata){
	    			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	    			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	    		}else{
	    			curl_setopt($ch, CURLOPT_POST, 1);
	    			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    		}
	    		$data = curl_exec($ch);
	    		curl_close($ch);
	    	}else{
	    		if($postdata){
		    		$url = $url.'?'.$data;
				$opts = array(
					'http' => array (
			            		'method' => 'POST',
			            		'header'=> "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($data) . "\r\n",
			            		'content' => $data
			            		)
				    );
				$context = stream_context_create($opts);
		    		$data = file_get_contents($url, false, $context);
	    		}
	    	}
    	return $data;
    }
}
?>