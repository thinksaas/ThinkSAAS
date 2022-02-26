<?php
defined('IN_TS') or die('Access Denied.');


$arrMessage = $new['message']->findAll('message',array(
	'touserid'=>$strUser['userid'],
	'isread'=>'0',
));

foreach($arrMessage as $key=>$item){
	$arrMessage[$key]['content'] = tsTitle($item['content']);
	if($item['userid']){
		$arrMessage[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
	}
}

$title = '我的消息盒子';

include template("my");