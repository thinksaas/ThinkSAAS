<?php
defined('IN_TS') or die('Access Denied.');

//消息盒子
$userid = aac('user')->isLogin();

$touserid= tsIntval($_GET['userid']);

if($userid == 0 || $touserid == 0) {
	exit;
};

$msgCount = $new['message']->findCount('message',"(userid='$userid' and touserid='$touserid') or (userid='$touserid' and touserid='$userid')");

if($msgCount ==0) {
	exit;
};

$arrMessage = $new['message']->findAll('message',"(userid='$userid' and touserid='$touserid') or (userid='$touserid' and touserid='$userid')",'addtime desc',null,10);


foreach($arrMessage as $key=>$item){
    $arrMessage[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
    $arrMessage[$key]['content'] = tsTitle($item['content']);
}

$arrMessage = array_reverse($arrMessage);


//isread设为已读
$new['message']->update('message',array(
	'userid'=>$touserid,
	'touserid'=>$userid,
	'isread'=>0,
),array(
	'isread'=>1,
));

$title = '消息盒子';

include template("msgbox");