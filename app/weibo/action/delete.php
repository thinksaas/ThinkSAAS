<?php 
defined('IN_TS') or die('Access Denied.');
//用户是否登录
$userid = aac('user')->isLogin();

$weiboid = intval($_GET['weiboid']);

$strWeibo = $new['weibo']->find('weibo',array(
	'weiboid'=>$weiboid,
));

if($userid == $strWeibo['userid'] || $TS_USER['user']['isadmin']==1){
	$new['weibo']->delete('weibo',array(
		'weiboid'=>$weiboid,
	));
	
	$new['weibo']->delete('weibo_comment',array(
		'weiboid'=>$weiboid,
	));
	
	//删除图片
	if($strWeibo['photo']){
		unlink('uploadfile/weibo/'.$strWeibo['photo']);
	}
	
	header('Location: '.tsUrl('weibo'));
	
}else{
	tsNotice('非法操作！');
}