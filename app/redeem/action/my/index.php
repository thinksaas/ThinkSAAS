<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$arrGoodsUser = $db->fetch_all_assoc("select `goodsid` from ".dbprefix."redeem_user where `userid`='".$strUser['userid']."' group by goodsid");

foreach($arrGoodsUser as $key=>$item){
	$arrGoods[] = $new['redeem']->find('redeem_goods',array(
		'goodsid'=>$item['goodsid'],
	));
}

$title = '我的积分兑换';
include template('my/index');