<?php
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

$arrMsg = $new['message']->findAll('message',array(
	'touserid'=>$userid,
	'isread'=>'0',
));

foreach($arrMsg as $key=>$item){
	if($item['userid']){
		$arrMsg[$key]['user'] = aac('user')->getOneUser($item['userid']);
	}
}

$title = '我的消息盒子';

include template("my");