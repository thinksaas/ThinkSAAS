<?php 
defined('IN_TS') or die('Access Denied.');

$arrUser = $new['user']->findAll('user_info',"`qq_openid`!=''");

foreach($arrUser as $key=>$item){
	$new['user']->create('user_open',array(
		'userid'=>$item['userid'],
		'sitename'=>'qq',
		'openid'=>$item['qq_openid'],
		'access_token'=>$item['qq_access_token'],
	));
}