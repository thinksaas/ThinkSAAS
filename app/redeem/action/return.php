<?php 
defined('IN_TS') or die('Access Denied.');

if(intval($TS_USER['isadmin'])==0){
	echo '0';exit;//非法操作
}

$goodsid = intval($_POST['goodsid']);
$userid = intval($_POST['userid']);

$strGoodsUser=$new['redeem']->find('redeem_user',array(
	'goodsid'=>$goodsid,
	'userid'=>$userid,
));

if($strGoodsUser==''){
	echo '1';exit;//非法操作
}

if($strGoodsUser['isreturn']=='1'){
	echo '2';exit;//非法操作
}

$strGoods = $new['redeem']->find('redeem_goods',array(
	'goodsid'=>$goodsid,
));

if($strGoods['return']=='0'){
	echo '3';exit;//非法操作
}

$new['redeem']->update('redeem_user',array(
	'goodsid'=>$goodsid,
	'userid'=>$userid,
),array(
	'isreturn'=>'1',
));

// 增加积分
aac('user')->addScore($userid,'积分兑换('.$strGoods['goodsid'].')返还积分',$strGoods['return']);

//系统消息
$msg_userid = '0';
$msg_touserid = $userid;
$msg_content = '你有积分兑换返还'.$strGoods['return'].'积分，快去看看吧：'.tsUrl('redeem','goods',array('id'=>$goodsid));
aac('message')->sendmsg($msg_userid,$msg_touserid,$msg_content);

echo '4';exit;