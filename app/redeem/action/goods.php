<?php 
defined('IN_TS') or die('Access Denied.');

$goodsid = intval($_GET['id']);

$strGoods = $new['redeem']->find('redeem_goods',array(

	'goodsid'=>$goodsid,

));

//谁兑换
$arrUsers = $new['redeem']->findAll('redeem_user',array(
	'goodsid'=>$goodsid,
));
foreach($arrUsers as $key=>$item){
	$arrUser[] = aac('user')->getOneUser($item['userid']);
	$arrUser[$key]['isreturn'] = $item['isreturn'];
}

$title = $strGoods['title'];

include template('goods');