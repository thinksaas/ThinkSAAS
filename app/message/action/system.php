<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 系统消息盒子
 */
$userid = '0';
$touserid= aac('user')->isLogin();

$arrMessage = $new['message']->findAll('message',array(
	'userid'=>0,
	'touserid'=>$touserid,
),'addtime desc',null,10);

foreach($arrMessage as $key=>$item){
    $arrMessage[$key]['content'] = tsTitle($item['content']);
}

//isread设为已读
$new['message']->update('message',array(
	'userid'=>0,
	'touserid'=>$touserid,
	'isread'=>0,
),array(
	'isread'=>1,
));

$title = '系统消息';

include template("system");