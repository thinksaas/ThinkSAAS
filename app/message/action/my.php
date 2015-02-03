<?php
defined('IN_TS') or die('Access Denied.');


$arrMsg = $new['message']->findAll('message',array(
	'touserid'=>$strUser['userid'],
	'isread'=>'0',
));

foreach($arrMsg as $key=>$item){
	$arrMsg[$key]['content'] = str_replace('[SITE_URL]',SITE_URL,$item['content']);
	if($item['userid']){
		$arrMsg[$key]['user'] = aac('user')->getOneUser($item['userid']);
	}
}

$title = '我的消息盒子';

include template("my");