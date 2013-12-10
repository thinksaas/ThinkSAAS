<?php 
defined('IN_TS') or die('Access Denied.');

//用户是否登录
$userid = aac('user')->isLogin();

$albumid = intval($_GET['albumid']);

$strAlbum = $new['photo']->find('photo_album',array(
	'albumid'=>$albumid,
));

if($userid != $strAlbum['userid']) {

	tsNotice('非法操作！');

}

$addtime = time();

$title = '上传照片';
include template("upload");