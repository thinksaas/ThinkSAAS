<?php 
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

if(intval($TS_USER['user']['isadmin'])==0){
	tsNotice('非法操作！');
}

$goodsid = intval($_GET['goodsid']);

$strGoods = $new['redeem']->find('redeem_goods',array(
	'goodsid'=>$goodsid,
));


unlink('uploadfile/redeem/'.$strGoods['photo']);

$new['redeem']->delete('redeem_goods',array(
	'goodsid'=>$goodsid,
));

header('Location: '.SITE_URL.tsUrl('redeem'));