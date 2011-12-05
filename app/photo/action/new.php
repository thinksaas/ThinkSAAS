<?php 

$arrPhotos = $db->fetch_all_assoc("select * from ".dbprefix."photo order by photoid desc limit 60");

foreach($arrPhotos as $key=>$item){
	$arrPhoto[] = $item;
	$arrPhoto[$key]['user'] = aac('user')->getSimpleUser($item['userid']);
}

$title = '最新上传图片';

include template("new");