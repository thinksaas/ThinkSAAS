<?php 
defined('IN_TS') or die('Access Denied.');

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';

$url = tsUrl('photo','index',array('page'=>''));

$lstart = $page*25-25;

$arrAlbum = $new['photo']->findAll('photo_album',null,'albumid desc',null,$lstart.',25');

foreach($arrAlbum as $key=>$item){
	$arrAlbum[$key]['albumname'] = htmlspecialchars($item['albumname']);
}

$albumNum = $new['photo']->findCount('photo_album');

$pageUrl = pagination($albumNum, 25, $page, $url);

$title = '最新专辑';
include template("index");