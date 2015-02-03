<?php 
defined ( 'IN_TS' ) or die ( 'Access Denied.' );


$page = isset($_GET['page']) ? intval($_GET['page']) : '1';

$url = tsUrl('photo','my',array('my'=>'index','page'=>''));

$lstart = $page*6-6;

$arrAlbum = $new['photo']->findAll('photo_album',array(
	'userid'=>$strUser['userid'],
),'albumid desc',null,$lstart.',6');

foreach($arrAlbum as $key=>$item){
	$arrAlbum[$key]['albumname'] = tsDecode($item['albumname']);
	$arrAlbum[$key]['albumdesc'] = tsDecode($item['albumdesc']);
}

$albumNum = $new['photo']->findCount('photo_album',array(

	'userid'=>$strUser['userid'],

));

$pageUrl = pagination($albumNum, 6, $page, $url);

$title = '我的相册';
include template('my/index');