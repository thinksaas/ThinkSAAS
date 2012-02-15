<?php 
defined('IN_TS') or die('Access Denied.');

$arrPhotos = $db->findAll("select * from ".dbprefix."photo order by photoid desc limit 60");

foreach($arrPhotos as $key=>$item){
	$arrPhoto[] = $item;
	$arrPhoto[$key]['user'] = aac('user')->getOneUser($item['userid']);
}

include template("pinte");