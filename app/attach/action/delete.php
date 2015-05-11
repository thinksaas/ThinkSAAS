<?php   
defined('IN_TS') or die('Access Denied.');
$userid = aac('user')->isLogin();

$attachid = intval($_GET['attachid']);

$strAttach = $new['attach']->find('attach',array(
	'attachid'=>$attachid,
));

if($TS_USER['isadmin']==1 || $strAttach['userid']==$userid){

	unlink('uploadfile/attach/'.$strAttach['attachurl']);
	$new['attach']->delete('attach',array(
		'attachid'=>$attachid,
	));
	
	tsNotice('删除成功！');

}else{
	
	tsNotice('非法操作');

}