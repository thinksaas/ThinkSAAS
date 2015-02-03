<?php
defined('IN_TS') or die('Access Denied.');
/* 
 * 系统消息盒子
 */
$userid = '0';
$touserid= aac('user')->isLogin();

$arrMessages = $new['message']->findAll('message',array(
	'userid'=>0,
	'touserid'=>$touserid,
),'addtime desc',null,10);

$pattern='/(http:\/\/|https:\/\/|ftp:\/\/)([\w:\/\.\?=&-_]+)/is';

if(is_array($arrMessages)){
	foreach($arrMessages as $key=>$item){
		$arrMessage[] = $item;
		$arrMessage[$key]['content'] = nl2br(@preg_replace($pattern, '<a href="\1\2">\1\2</a>', $item['content']));
		$arrMessage[$key]['content'] = stripslashes(str_replace('[SITE_URL]',SITE_URL,$arrMessage[$key]['content']));
	}
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