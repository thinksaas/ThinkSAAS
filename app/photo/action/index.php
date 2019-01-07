<?php 
defined('IN_TS') or die('Access Denied.');

$page = isset($_GET['page']) ? intval($_GET['page']) : '1';

$url = tsUrl('photo','index',array('page'=>''));

$lstart = $page*30-30;

$arrAlbum = $new['photo']->findAll('photo_album',"`count_photo`>0 and `isaudit`=0",'albumid desc',null,$lstart.',30');

foreach($arrAlbum as $key=>$item){
	$arrAlbum[$key]['albumname'] = tstitle($item['albumname']);
	$arrAlbum[$key]['albumdesc'] = tstitle($item['albumdesc']);
}

$albumNum = $new['photo']->findCount('photo_album');

$pageUrl = pagination($albumNum, 30, $page, $url);

$title = '最新专辑';

$sitekey = $TS_APP['appkey'];
$sitedesc = $TS_APP['appdesc'];
include template("index");