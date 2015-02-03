<?php 
defined('IN_TS') or die('Access Denied.');

$goodsid = intval($_GET['id']);

$strGoods = $new['redeem']->find('redeem_goods',array(
	'goodsid'=>$goodsid,
));

$strGoods['content'] = tsDecode($strGoods['content']);

//谁兑换
$arrUsers = $new['redeem']->findAll('redeem_user',array(
	'goodsid'=>$goodsid,
));
foreach($arrUsers as $key=>$item){
	
	
	if(aac('user')->isUser($item['userid'])==false){
		$new['redeem']->delete('redeem_user',array(
			'userid'=>$item['userid'],
			'goodsid'=>$item['goodsid'],
		));
	}else{
		$arrUser[$key] = aac('user')->getOneUser($item['userid']);
		$arrUser[$key]['isreturn'] = $item['isreturn'];
	}
	
	
}

//最新兑换
$arrNewGoods = $new['redeem']->findAll('redeem_goods',null,'addtime desc',null,10);

$title = $strGoods['title'];

include template('goods');