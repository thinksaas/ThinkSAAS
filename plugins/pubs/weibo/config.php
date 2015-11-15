<?php
defined('IN_TS') or die('Access Denied.');

//读取数据
$arr_weibvo = fileRead('data/plugins_pubs_weibo.php');
if($arr_weibvo==''){
	$arr_weibvo = $tsMySqlCache->get('plugins_pubs_weibo');
}

define( "WB_AKEY" , $arr_weibvo['wb_akey']);
define( "WB_SKEY" , $arr_weibvo['wb_skey'] );
define( "WB_CALLBACK_URL" , $arr_weibvo['siteurl']."index.php?app=pubs&ac=plugin&plugin=weibo&in=callback" );