<?php  
defined('IN_TS') or die('Access Denied.');


//资料库
$arrAlbum = $new['attach']->findAll('attach_album',array('isaudit'=>0),'addtime desc',null,15);
foreach($arrAlbum as $key=>$item){
	$arrAlbum[$key]['title'] = tsTitle($item['title']);
	$arrAlbum[$key]['content'] = tsDecode($item['content']);
	$arrAlbum[$key]['user'] = aac('user')->getOneUser($item['userid']);
	$new['attach'] ->update('attach_album',array(
			'albumid'=>$item['albumid'],
	),array(
			'count_attach'=>$arrAlbum[$key]['count_attach'],
	));
}


$arrAttach = $new['attach']->findAll('attach',null,'addtime desc',null,20);
foreach($arrAttachs as $key=>$item){
	$arrAttach[$key]['user'] = aac('user')->getOneUser($item['userid']);
	
}

$sitekey = $TS_APP['appkey'];
$sitedesc = $TS_APP['appdesc'];

include template("index");