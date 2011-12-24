<?php
defined('IN_TS') or die('Access Denied.');
$photoid = intval($_GET['photoid']);

if($photoid == 0){
	header("Location: ".SITE_URL.tsurl('photo'));
	exit;
}

$photoNum = $db->once_fetch_assoc("select count(*) from ".dbprefix."photo where `photoid`='$photoid'");

if($photoNum['count(*)']==0){
	header("Location: ".SITE_URL.tsurl('photo'));
	exit;
}

$strPhoto = $db->once_fetch_assoc("select * from ".dbprefix."photo where photoid='$photoid'");

$albumid = $strPhoto['albumid'];

$strAlbum = $db->once_fetch_assoc("select * from ".dbprefix."photo_album where albumid='$albumid'");

$arrPhotoIds = $db->fetch_all_assoc("select photoid from ".dbprefix."photo where albumid='$albumid' order by photoid desc");

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
$strUser = aac('user')->getUserForApp($userid);

//评论列表 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$url = "index.php?app=photo&ac=show&photoid=".$photoid."&page=";
$lstart = $page*10-10;
$arrComments = $db->fetch_all_assoc("select * from ".dbprefix."photo_comment where photoid='$photoid' limit $lstart,10");
foreach($arrComments as $key=>$item){
	$arrComment[] = $item;
	$arrComment[$key]['user'] = aac('user')->getUserForApp($item['userid']);
}

$comment_num = $db->once_num_rows("select * from ".dbprefix."photo_comment where photoid='$photoid'");
$pageUrl = pagination($comment_num, 10, $page, $url);


if($TS_USER['user']['userid'] == $userid){
	$title = '我的相册-'.$strAlbum['albumname'].'-第'.$nowPage.'张';
}else{
	$title = $strUser['username'].'的相册-'.$strAlbum['albumname'].'-第'.$nowPage.'张';
}

include template("photo_show");

$db->query("update ".dbprefix."photo set `count_view`=count_view+1 where photoid='$photoid'");