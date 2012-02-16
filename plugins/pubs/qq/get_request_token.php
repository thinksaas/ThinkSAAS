<?php
/**
 * PHP SDK for QQ登录 OpenAPI
 *
 * @version 1.3
 * @author connect@qq.com
 * @copyright © 2011, Tencent Corporation. All rights reserved.
 */


require_once("utils.php");

 /**
 * @brief 请求临时token.请求需经过URL编码，编码时请遵循 RFC 1738
 *  
 * @param $appid
 * @param $appkey
 *
 * @return 返回字符串格式为：oauth_token=xxx&oauth_token_secret=xxx
 */
function get_request_token($appid, $appkey)
{
    //请求临时token的接口地址, 不要更改!!
    $url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token?";


    //生成oauth_signature签名值。签名值生成方法详见（http://wiki.opensns.qq.com/wiki/【QQ登录】签名参数oauth_signature的说明）
    //（1） 构造生成签名值的源串（HTTP请求方式 & urlencode(uri) & urlencode(a=x&b=y&...)）
	$sigstr = "GET"."&".rawurlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token")."&";

	//必要参数
    $params = array();
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = time();
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;

    //对参数按照字母升序做序列化
    $normalized_str = get_normalized_string($params);
    $sigstr        .= rawurlencode($normalized_str);
   
	
	//（2）构造密钥
    $key = $appkey."&";


 	//（3）生成oauth_signature签名值。这里需要确保PHP版本支持hash_hmac函数
    $signature = get_signature($sigstr, $key);
    
		
	//构造请求url
    $url      .= $normalized_str."&"."oauth_signature=".rawurlencode($signature);

    //echo "$sigstr\n";
    //echo "$url\n";

    return file_get_contents($url);
}

//get_request_token接口调用示例：
//echo get_request_token($_SESSION["appid"], $_SESSION["appkey"]);
?>
