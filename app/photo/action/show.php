<?php
defined('IN_TS') or die('Access Denied.');

$photoid = intval($_GET['id']);

$strPhoto = $new['photo']->find('photo',array(
	'photoid'=>$photoid,
));

//404
if($strPhoto == ''){
	header("HTTP/1.1 404 Not Found");
	header("Status: 404 Not Found");
	$title = '404';
	include pubTemplate("404");
	exit;
}

$strPhoto['photoname'] = tsTitle($strPhoto['photoname']);
$strPhoto['photodesc'] = tsDecode($strPhoto['photodesc']);

$albumid = $strPhoto['albumid'];

//图片标签
$strPhoto['tags'] = aac('tag')->getObjTagByObjid('photo', 'photoid', $strPhoto['photoid']);

//用户 
$strPhoto['user'] = aac('user')->getOneUser($strPhoto['userid']);

//相册下所有图片
$arrPhoto = $new['photo']->findAll('photo',array(
	'albumid'=>$strPhoto['albumid'],
),null,null,8);

//所在专辑
$strAlbum = $new['photo']->find('photo_album',array(
	'albumid'=>$albumid,
));

$strAlbum['albumname'] = tsTitle($strAlbum['albumname']);
$strAlbum['albumdesc'] = tsDecode($strAlbum['albumdesc']);

$arrPhotoIds = $new['photo']->findAll('photo',array(

	'albumid'=>$albumid,

),'photoid desc');

foreach($arrPhotoIds as $item){
	$arrPhotoId[] = $item['photoid'];
}

rsort($arrPhotoId);

$nowkey = array_search($photoid,$arrPhotoId);
$nowPage =  $nowkey+1 ;
$conutPage = count($arrPhotoId);
$prev = $arrPhotoId[$nowkey - 1];
$next = $arrPhotoId[$nowkey +1];

$userid = $strAlbum['userid'];
$strUser = aac('user')->getOneUser($userid);

//评论列表 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = tsUrl('photo','show',array('id'=>$photoid,'page'=>''));

$lstart = $page*10-10;

$arrComments = $new['photo']->findAll('photo_comment',array(
	'photoid'=>$photoid,
),'addtime desc',null,$lstart.',10');

foreach($arrComments as $key=>$item){
	$arrComment[] = $item;
	$arrComment[$key]['user'] = aac('user')->getOneUser($item['userid']);
	$arrComment[$key]['content'] = tsDecode($item['content']);
}

$comment_num = $new['photo']->findCount('photo_comment',array(
	'photoid'=>$photoid,
));

$pageUrl = pagination($comment_num, 10, $page, $url);


if($TS_USER['userid'] == $userid){
	$title = '我的相册-'.$strAlbum['albumname'].'-第'.$nowPage.'张';
}else{
	$title = $strUser['username'].'的相册-'.$strAlbum['albumname'].'-第'.$nowPage.'张';
}

include template("show");

$new['photo']->update('photo',array(
	'photoid'=>$strPhoto['photoid'],
),array(
	'count_view'=>$strPhoto['count_view']+1,
));