<?php
defined('IN_TS') or die('Access Denied.');

$photoid = tsIntval($_GET['id']);

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

$strPhoto['title'] = tsTitle($strPhoto['title']);
$strPhoto['photodesc'] = tsTitle($strPhoto['photodesc']);

#原图
if($TS_SITE['file_upload_type']==1){
	#阿里云oss
	$strPhoto['photo_url'] = $TS_SITE['alioss_bucket_url'].'/uploadfile/photo/'.$strPhoto['photourl'];
}else{
	#本地
	$strPhoto['photo_url'] = SITE_URL.'uploadfile/photo/'.$strPhoto['photourl'];
}

$albumid = $strPhoto['albumid'];

//图片标签
$strPhoto['tags'] = aac('tag')->getObjTagByObjid('photo', 'photoid', $strPhoto['photoid']);

//用户 
$strPhoto['user'] = aac('user')->getSimpleUser($strPhoto['userid']);

//相册下所有图片
$arrPhoto = $new['photo']->findAll('photo',array(
	'albumid'=>$strPhoto['albumid'],
),null,null,8);

//所在专辑
$strAlbum = $new['photo']->find('photo_album',array(
	'albumid'=>$albumid,
));

$strAlbum['albumname'] = tsTitle($strAlbum['albumname']);
$strAlbum['albumdesc'] = tsTitle($strAlbum['albumdesc']);

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
$strUser = aac('user')->getSimpleUser($userid);

//评论列表 
$page = tsIntval($_GET['page'],1);
$url = tsUrl('photo','show',array('id'=>$photoid,'page'=>''));
$lstart = $page*15-15;
$arrComment = aac('pubs')->getCommentList('photo','photoid',$strPhoto['photoid'],$page,$lstart,$strPhoto['userid']);
$commentNum = aac('pubs')->getCommentNum('photo','photoid',$strPhoto['photoid']);
$pageUrl = pagination($commentNum, 15, $page, $url);



$title = $strAlbum['albumname'].'(第'.$nowPage.'张)';


include template("show");

$new['photo']->update('photo',array(
	'photoid'=>$strPhoto['photoid'],
),array(
	'count_view'=>$strPhoto['count_view']+1,
));