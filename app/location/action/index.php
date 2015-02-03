<?php
defined('IN_TS') or die('Access Denied.');

$userid = aac('user')->isLogin();

$strUser = aac('user')->getOneUser($userid);

if($strUser['locationid']==0){
	$arrLocation = $new['location']->findAll('location');
	if($arrLocation==''){
		tsNotice('同城未做配置，暂时还不能加入！');
	}
	
	$title = '选择加入同城';
	include template('index');
	exit;
}


header('Location: '.tsUrl('location','show',array('id'=>$strUser['locationid'])));
exit;