<?php
require_once("config.php");
require_once("utils.php");

function get_user_info()
{
    $get_user_info = "https://graph.qq.com/user/get_user_info?"
        . "access_token=" . $_SESSION['access_token']
        . "&oauth_consumer_key=" . $_SESSION["appid"]
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";

    $info = get_url_contents($get_user_info);
    $arr = json_decode($info, true);

    return $arr;
}

//获取用户基本资料
$arr = get_user_info();

$title = "QQ帐号登录信息完善";

include 'get_user_info.html';