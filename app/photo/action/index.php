<?php 

$page = isset($_GET['page']) ? $_GET['page'] : '1';

$url = SITE_URL."index.php?app=photo&ac=index&page=";

$lstart = $page*28-28;

$arrAlbum = $db->fetch_all_assoc("select * from ".dbprefix."photo_album order by albumid desc limit $lstart,28");

$albumNum = $db->once_num_rows("select * from ".dbprefix."photo_album");

$pageUrl = pagination($albumNum, 28, $page, $url);

$arrComments = $db->fetch_all_assoc("select * from ".dbprefix."photo_comment order by commentid desc limit 8");

foreach($arrComments as $key=>$item){
	$arrComment[] = $item;
	$arrComment[$key]['user'] = aac('user')->getUserForApp($item['userid']);
	$arrComment[$key]['photo'] = $new['photo']->getPhotoForApp($item['photoid']);
}

$title = '最新相册';
include template("index");