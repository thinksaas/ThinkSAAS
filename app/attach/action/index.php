<?php  
defined('IN_TS') or die('Access Denied.');

$page = isset ( $_GET ['page'] ) ? intval ( $_GET ['page'] ) : 1;
$url = tsUrl ( 'attach', 'index', array ('page' => '') );
$lstart = $page * 20 - 20;

//资料库
$arrAlbum = $new['attach']->findAll('attach_album',array(
    'isaudit'=>0
),'addtime desc',null,$lstart.',20');
foreach($arrAlbum as $key=>$item){
	$arrAlbum[$key]['title'] = tsTitle($item['title']);
	$arrAlbum[$key]['content'] = tsTitle($item['content']);
	$arrAlbum[$key]['user'] = aac('user')->getOneUser($item['userid']);
	$new['attach'] ->update('attach_album',array(
			'albumid'=>$item['albumid'],
	),array(
			'count_attach'=>$arrAlbum[$key]['count_attach'],
	));
}

$albumNum = $new ['attach']->findCount ( 'attach_album', array (
    'isaudit' => '0'
) );

$pageUrl = pagination ( $albumNum, 20, $page, $url );


$sitekey = $TS_APP['appkey'];
$sitedesc = $TS_APP['appdesc'];

include template("index");