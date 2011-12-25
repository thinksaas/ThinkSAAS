<?php 
/**
 * PHP SDK for QQ登录 OpenAPI
 *
 * @version 1.3
 * @author connect@qq.com
 * @copyright © 2011, Tencent Corporation. All rights reserved.
 */

require_once("utils.php");

 /*
 * @brief 获取用户信息.请求需经过URL编码，编码时请遵循 RFC 1738
 * 
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function get_user_info($appid, $appkey, $access_token, $access_token_secret, $openid)
{
	//获取用户信息的接口地址, 不要更改!!
    $url    = "http://openapi.qzone.qq.com/user/get_user_info";
    $info   = do_get($url, $appid, $appkey, $access_token, $access_token_secret, $openid);
    $arr = array();
    $arr = json_decode($info, true);

    return $arr;
}

//接口调用示例：
$arr = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);

$title = "QQ帐号登录信息完善";

include 'get_user_info.html';